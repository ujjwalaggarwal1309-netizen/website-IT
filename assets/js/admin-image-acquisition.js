jQuery(document).ready(function ($) {
	'use strict';

	const ajaxUrl = ithsAcquisition.ajaxUrl;
	const nonce = ithsAcquisition.nonce;

	// Tab Switching
	$('.nav-tab').on('click', function (e) {
		e.preventDefault();
		$('.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		const target = $(this).attr('href');
		$('.iths-tab-content').removeClass('active');
		$(target).addClass('active');
	});

	function doSearch($row) {
		return new Promise((resolve) => {
			const $btn = $row.find('.btn-search-image');
			if (!$btn.length || $btn.prop('disabled')) return resolve();

			const productId = $row.data('product-id');
			const oemPn = $row.data('oem-pn');
			const brand = $row.data('brand');
			const type = $row.data('type');

			$btn.prop('disabled', true).text('Searching...');
			$row.find('.status-badge').attr('class', 'status-badge searching').text('Searching');

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: { action: 'iths_img_search', nonce: nonce, product_id: productId, oem_pn: oemPn, brand: brand, type: type },
				success: function (response) {
					if (response.success) {
						$row.find('.candidate-cell').html(response.data.candidate_html);
						$row.find('.action-cell').html(response.data.action_html);
						$row.find('.status-badge').attr('class', 'status-badge candidates_found').text('Candidates Found');
					} else {
						$row.find('.status-badge').attr('class', 'status-badge failed').text('Failed');
						$row.find('.candidate-cell').text(response.data || 'No results found.');
						$btn.prop('disabled', false).text('Search Again');
					}
					resolve();
				},
				error: function () {
					$row.find('.status-badge').attr('class', 'status-badge failed').text('Error');
					$btn.prop('disabled', false).text('Search Again');
					resolve();
				}
			});
		});
	}

	function doApprove($row, $btn) {
		return new Promise((resolve) => {
			if (!$btn.length || $btn.prop('disabled')) return resolve();

			const productId = $row.data('product-id');
			const oemPn = $row.data('oem-pn');
			
			const imgUrl = $btn.data('img-url');
			const sourceUrl = $btn.data('source-url');
			const sourceDomain = $btn.data('source-domain');
			const confidence = $btn.data('confidence');

			$row.find('.btn').prop('disabled', true);
			$btn.text('Downloading...');
			$row.find('.status-badge').attr('class', 'status-badge').text('Downloading...');

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'iths_img_approve', nonce: nonce, product_id: productId, oem_pn: oemPn,
					img_url: imgUrl, source_url: sourceUrl, source_domain: sourceDomain, confidence: confidence
				},
				success: function (response) {
					if (response.success) {
						$row.find('.status-badge').attr('class', 'status-badge assigned').text('Assigned');
						$row.find('.candidate-cell').html(`<img src="${response.data.url}" class="existing-hero-thumb"> <br><small>${response.data.filename}</small>`);
						$row.find('.action-cell').html('✅ Done');
						
						let missingCount = parseInt($('#count-missing').text(), 10) || 0;
						let assignedCount = parseInt($('#count-assigned').text(), 10) || 0;
						$('#count-missing').text(Math.max(0, missingCount - 1));
						$('#count-assigned').text(assignedCount + 1);
					} else {
						$row.find('.status-badge').attr('class', 'status-badge failed').text('Failed');
						$row.find('.btn').prop('disabled', false);
						$btn.text('Approve');
					}
					resolve();
				},
				error: function () {
					$row.find('.status-badge').attr('class', 'status-badge failed').text('Error');
					$row.find('.btn').prop('disabled', false);
					$btn.text('Approve');
					resolve();
				}
			});
		});
	}

	// Single Action Bindings
	$(document).on('click', '.btn-search-image', function () {
		doSearch($(this).closest('tr'));
	});

	$(document).on('click', '.btn-approve-image', function () {
		doApprove($(this).closest('tr'), $(this));
	});

	$(document).on('click', '.btn-reject-image', function () {
		const $btn = $(this);
		const $row = $btn.closest('tr');
		const productId = $row.data('product-id');

		$row.find('.btn').prop('disabled', true);
		$btn.text('Skipping...');

		$.ajax({
			url: ajaxUrl,
			type: 'POST',
			data: { action: 'iths_img_reject', nonce: nonce, product_id: productId },
			success: function (response) {
				if (response.success) {
					$row.find('.status-badge').attr('class', 'status-badge needs_review').text('Needs Review');
					$row.find('.action-cell').html('Skipped');
				} else {
					$row.find('.btn').prop('disabled', false);
					$btn.text('Reject');
				}
			}
		});
	});

	// Bulk Actions
	$('#btn-bulk-search').on('click', async function () {
		const $btn = $(this);
		const $rows = $('.iths-acquisition-table tbody tr').filter(function() {
			return $(this).find('.btn-search-image').length > 0;
		});

		if ($rows.length === 0) {
			alert('No products left to search.');
			return;
		}

		$btn.prop('disabled', true);
		$('#btn-bulk-approve').prop('disabled', true);
		
		for (let i = 0; i < $rows.length; i++) {
			$('#bulk-status').text(`Searching ${i + 1} of ${$rows.length}...`);
			await doSearch($($rows[i]));
		}
		
		$('#bulk-status').text('Bulk search complete!');
		$btn.prop('disabled', false);
		$('#btn-bulk-approve').prop('disabled', false);
	});

	$('#btn-bulk-approve').on('click', async function () {
		const $btn = $(this);
		const $rows = $('.iths-acquisition-table tbody tr').filter(function() {
			const $approveBtn = $(this).find('.btn-approve-image');
			return $approveBtn.length > 0 && $approveBtn.data('confidence') === 'HIGH';
		});

		if ($rows.length === 0) {
			alert('No products with HIGH confidence candidates found.');
			return;
		}

		$btn.prop('disabled', true);
		$('#btn-bulk-search').prop('disabled', true);
		
		for (let i = 0; i < $rows.length; i++) {
			$('#bulk-status').text(`Approving ${i + 1} of ${$rows.length}...`);
			const $row = $($rows[i]);
			await doApprove($row, $row.find('.btn-approve-image'));
		}
		
		$('#bulk-status').text('Bulk approval complete!');
		$btn.prop('disabled', false);
		$('#btn-bulk-search').prop('disabled', false);
	});

});
