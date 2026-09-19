/**
 * Infinity Import Engine — Admin JavaScript
 * Handles file upload, preview, dry run, batch import loop, and rollback.
 */

/* global ithsImport, jQuery */
( function ( $ ) {
	'use strict';

	// ── State ──────────────────────────────────────────────────────────────

	var state = {
		sessionId:    null,
		totalBatches: 0,
		currentBatch: 0,
		accStats:     { created: 0, updated: 0, skipped: 0, duplicates: 0, failed: 0, warnings: 0, missing_images: 0, missing_taxonomies: 0, missing_meta: 0, errors: [] },
		startTime:    null,
		filename:     null,
	};

	// ── DOM Refs ───────────────────────────────────────────────────────────

	var $form          = $( '#iths-upload-form' );
	var $fileInput     = $( '#iths-file' );
	var $btnPreview    = $( '#iths-btn-preview' );
	var $btnDryRun     = $( '#iths-btn-dry-run' );
	var $btnImport     = $( '#iths-btn-import' );
	var $previewSummary = $( '#iths-preview-summary' );
	var $progressWrap  = $( '#iths-progress-wrap' );
	var $reportWrap    = $( '#iths-report-wrap' );
	var $progressBar   = $( '#iths-progress-bar' );
	var $progressText  = $( '#iths-progress-text' );
	var $progressLabel = $( '#iths-progress-label' );
	var $progressLog   = $( '#iths-progress-log' );
	var $reportContent = $( '#iths-report-content' );
	var $rollbackBtn   = $( '#iths-btn-rollback' );
	var $rollbackResult = $( '#iths-rollback-result' );
	var $releaseLockBtn = $( '#iths-btn-release-lock' );

	// ── File Input ─────────────────────────────────────────────────────────

	$fileInput.on( 'change', function () {
		var hasFile = $( this ).val() !== '';
		$btnPreview.prop( 'disabled', ! hasFile );
		$btnDryRun.prop( 'disabled', true );
		$btnImport.prop( 'disabled', true );
		// Reset UI on new file selection.
		$previewSummary.hide();
		$progressWrap.hide();
		$reportWrap.hide();
	} );

	// ── Preview ────────────────────────────────────────────────────────────

	$btnPreview.on( 'click', function () {
		var file = $fileInput[ 0 ].files[ 0 ];
		if ( ! file ) { return; }

		$btnPreview.prop( 'disabled', true ).text( '⏳ Loading…' );
		$previewSummary.hide();
		$reportWrap.hide();

		var formData = buildFormData();
		formData.append( 'action', 'iths_import_preview' );

		$.ajax( {
			url:         ithsImport.ajaxUrl,
			type:        'POST',
			data:        formData,
			processData: false,
			contentType: false,
			success: function ( res ) {
				if ( ! res.success ) {
					alert( '⚠ Preview failed: ' + ( res.data.message || 'Unknown error' ) );
					return;
				}
				var d = res.data;
				updateSummaryBar( d.summary );
				$( '#iths-preview-table-wrap' ).html( d.table_html );
				$previewSummary.show();
				$btnDryRun.prop( 'disabled', false );
				$btnImport.prop( 'disabled', false );
			},
			error: function () {
				alert( '⚠ Network error during preview. Please try again.' );
			},
			complete: function () {
				$btnPreview.prop( 'disabled', false ).text( '👁 Preview Import' );
			},
		} );
	} );

	// ── Dry Run ────────────────────────────────────────────────────────────

	$btnDryRun.on( 'click', function () {
		$btnDryRun.prop( 'disabled', true ).text( '⏳ Running…' );
		$reportWrap.hide();

		$.ajax( {
			url:  ithsImport.ajaxUrl,
			type: 'POST',
			data: { action: 'iths_import_dry_run', nonce: ithsImport.nonce },
			success: function ( res ) {
				if ( ! res.success ) {
					alert( '⚠ Dry Run failed: ' + ( res.data.message || 'Unknown error' ) );
					return;
				}
				$reportContent.html( res.data.report_html );
				$reportWrap.find( 'h3' ).text( ithsImport.i18n.dryRunLabel );
				$reportWrap.show();
				$( 'html, body' ).animate( { scrollTop: $reportWrap.offset().top - 60 }, 400 );
			},
			error: function () {
				alert( '⚠ Network error during Dry Run.' );
			},
			complete: function () {
				$btnDryRun.prop( 'disabled', false ).text( '🧪 Dry Run' );
			},
		} );
	} );

	// ── Import ─────────────────────────────────────────────────────────────

	$btnImport.on( 'click', function () {
		if ( ! window.confirm( ithsImport.i18n.confirmImport ) ) { return; }

		$btnImport.prop( 'disabled', true );
		$btnPreview.prop( 'disabled', true );
		$btnDryRun.prop( 'disabled', true );
		$reportWrap.hide();

		// Reset accumulated stats.
		state.accStats  = { created: 0, updated: 0, skipped: 0, duplicates: 0, failed: 0, warnings: 0, missing_images: 0, missing_taxonomies: 0, missing_meta: 0, errors: [] };
		state.startTime = Date.now();

		$.ajax( {
			url:  ithsImport.ajaxUrl,
			type: 'POST',
			data: { action: 'iths_import_start', nonce: ithsImport.nonce },
			success: function ( res ) {
				if ( ! res.success ) {
					alert( '⚠ Import start failed: ' + ( res.data.message || 'Unknown error' ) );
					re_enable_buttons();
					return;
				}
				state.sessionId    = res.data.session_id;
				state.totalBatches = res.data.total_batches;
				state.currentBatch = 0;

				showProgress();
				runNextBatch();
			},
			error: function () {
				alert( '⚠ Network error starting import.' );
				re_enable_buttons();
			},
		} );
	} );

	// ── Batch Loop ─────────────────────────────────────────────────────────

	function runNextBatch() {
		if ( state.currentBatch >= state.totalBatches ) {
			finalizeImport();
			return;
		}

		var batchNum = state.currentBatch;
		var labelTpl = ithsImport.i18n.batchProgress
			.replace( '{done}', batchNum + 1 )
			.replace( '{total}', state.totalBatches );
		$progressLabel.text( labelTpl );

		$.ajax( {
			url:  ithsImport.ajaxUrl,
			type: 'POST',
			data: {
				action:      'iths_import_batch',
				nonce:       ithsImport.nonce,
				session_id:  state.sessionId,
				batch_index: batchNum,
			},
			success: function ( res ) {
				if ( ! res.success ) {
					logEntry( '⚠ Batch ' + batchNum + ' error: ' + ( res.data.message || 'Unknown' ) );
				} else {
					var d = res.data;
					state.accStats.created   += d.created   || 0;
					state.accStats.updated   += d.updated   || 0;
					state.accStats.skipped   += d.skipped   || 0;
					state.accStats.failed    += d.failed    || 0;
					state.accStats.warnings  += ( d.warnings  || [] ).length;
					if ( d.errors && d.errors.length ) {
						state.accStats.errors = state.accStats.errors.concat( d.errors );
					}
					logEntry( 'Batch ' + ( batchNum + 1 ) + '/' + state.totalBatches + ' — Created: ' + d.created + ' Updated: ' + d.updated + ' Skipped: ' + d.skipped + ' Failed: ' + d.failed );
				}

				state.currentBatch++;
				updateProgress();
				runNextBatch();
			},
			error: function () {
				logEntry( '⚠ Network error on batch ' + batchNum + '. Retrying…' );
				setTimeout( runNextBatch, 2000 );
			},
		} );
	}

	function finalizeImport() {
		$progressLabel.text( ithsImport.i18n.importing + ' Finalizing…' );

		var duration = ( ( Date.now() - state.startTime ) / 1000 ).toFixed( 1 );
		state.accStats.duration_s = parseFloat( duration );

		$.ajax( {
			url:  ithsImport.ajaxUrl,
			type: 'POST',
			data: {
				action:     'iths_import_finalize',
				nonce:      ithsImport.nonce,
				session_id: state.sessionId,
				stats:      JSON.stringify( state.accStats ),
			},
			success: function ( res ) {
				$progressWrap.hide();
				if ( res.success ) {
					$reportContent.html( res.data.report_html );
				} else {
					$reportContent.html( '<p>⚠ Finalization error: ' + ( res.data.message || '' ) + '</p>' );
				}
				$reportWrap.find( 'h3' ).text( ithsImport.i18n.complete );
				$reportWrap.show();
				$( 'html, body' ).animate( { scrollTop: $reportWrap.offset().top - 60 }, 400 );
				re_enable_buttons();
			},
			error: function () {
				$progressLabel.text( '⚠ Finalization network error. Import may still have completed.' );
				re_enable_buttons();
			},
		} );
	}

	// ── Rollback ───────────────────────────────────────────────────────────

	$rollbackBtn.on( 'click', function () {
		if ( ! window.confirm( ithsImport.i18n.confirmRollback ) ) { return; }

		var sessionId = $( this ).data( 'session' );
		$rollbackBtn.prop( 'disabled', true ).text( '⏳ Rolling back…' );

		$.ajax( {
			url:  ithsImport.ajaxUrl,
			type: 'POST',
			data: {
				action:     'iths_import_rollback',
				nonce:      ithsImport.nonce,
				session_id: sessionId,
			},
			success: function ( res ) {
				if ( res.success ) {
					$rollbackResult.html( '<div class="notice notice-success inline"><p>✅ ' + res.data.message + '</p></div>' );
				} else {
					$rollbackResult.html( '<div class="notice notice-error inline"><p>⚠ ' + res.data.message + '</p></div>' );
				}
			},
			error: function () {
				$rollbackResult.html( '<div class="notice notice-error inline"><p>⚠ Network error during rollback.</p></div>' );
			},
			complete: function () {
				$rollbackBtn.prop( 'disabled', false ).text( '↩ Rollback This Session' );
			},
		} );
	} );

	// ── Release Lock ───────────────────────────────────────────────────────

	$releaseLockBtn.on( 'click', function () {
		$.post( ithsImport.ajaxUrl, { action: 'iths_release_lock', nonce: ithsImport.nonce }, function ( res ) {
			if ( res.success ) {
				location.reload();
			}
		} );
	} );

	// ── UI Helpers ─────────────────────────────────────────────────────────

	function buildFormData() {
		var formData = new FormData();
		formData.append( 'nonce', ithsImport.nonce );
		if ( $fileInput[ 0 ].files[ 0 ] ) {
			formData.append( 'iths_file', $fileInput[ 0 ].files[ 0 ] );
		}
		return formData;
	}

	function updateSummaryBar( summary ) {
		$( '#iths-count-create' ).text( summary.create || 0 );
		$( '#iths-count-update' ).text( summary.update || 0 );
		$( '#iths-count-skip' ).text( summary.skip    || 0 );
		$( '#iths-count-warning' ).text( summary.warning || 0 );
	}

	function showProgress() {
		$progressLog.empty();
		$progressBar.css( 'width', '0%' );
		$progressText.text( '0%' );
		$progressLabel.text( ithsImport.i18n.importing );
		$progressWrap.show();
		$( 'html, body' ).animate( { scrollTop: $progressWrap.offset().top - 60 }, 300 );
	}

	function updateProgress() {
		var pct = state.totalBatches > 0
			? Math.round( ( state.currentBatch / state.totalBatches ) * 100 )
			: 100;
		$progressBar.css( 'width', pct + '%' );
		$progressText.text( pct + '%' );
	}

	function logEntry( msg ) {
		var $li = $( '<li>' ).text( msg );
		$progressLog.prepend( $li );
		// Cap log at 50 entries.
		$progressLog.children().slice( 50 ).remove();
	}

	function re_enable_buttons() {
		$btnPreview.prop( 'disabled', false );
		$btnDryRun.prop( 'disabled', false );
		$btnImport.prop( 'disabled', false );
	}

} )( jQuery );
