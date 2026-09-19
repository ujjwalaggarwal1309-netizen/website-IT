(function ($) {
	'use strict';

	$(document).ready(function () {
		const $form = $('#archive-filters-form');
		const $grid = $('#archive-product-grid');
		const $paginationWrap = $('#archive-pagination');
		const $totalCount = $('#archive-total-count');
		const $showingCount = $('#archive-showing-count');
		const $sortSelect = $('#archive-sort');
		const $searchInput = $('#archive-search-input');
		const $activeFiltersBar = $('#active-filters-bar');
		const ajaxUrl = ithsArchive.ajaxUrl;
		const nonce = ithsArchive.nonce;
		let debounceTimer;


		// ── Sprint 5.1: Enterprise Search Dropdown ───────────────────────────────
		const $searchWrapper = $('.search-field-wrapper');
		$searchWrapper.css('position', 'relative');
		const $suggestionsDropdown = $('<div class="search-suggestions-dropdown" role="listbox" aria-label="Search suggestions" style="display:none;"></div>').appendTo($searchWrapper);

		// Popular chips config — first chip gets orange accent
		const POPULAR_CHIPS = ['SAS HDD', 'SSD', 'RAID Controller', 'Power Supply', 'Network Card', 'Memory'];

		// In-memory cache + abort controller
		const searchCache = {};
		let currentXHR = null;
		let suggestTimer;
		let activeIndex = -1;

		// ── localStorage helpers ─────────────────────────────────────────────────

		function getRecentSearches() {
			try {
				const raw = localStorage.getItem('iths_recent_searches');
				const list = raw ? JSON.parse(raw) : [];
				return Array.isArray(list) ? list : [];
			} catch (e) { return []; }
		}

		function isValidTerm(term) {
			if (!term || typeof term !== 'string') return false;
			const t = term.trim();
			if (t.length < 2) return false;
			if (/^\d+$/.test(t)) return false; // purely numeric
			return true;
		}

		function addRecentSearch(term) {
			if (!isValidTerm(term)) return;
			let recent = getRecentSearches();
			recent = recent.filter(t => t.toLowerCase() !== term.toLowerCase());
			recent.unshift(term);
			if (recent.length > 5) recent = recent.slice(0, 5);
			try { localStorage.setItem('iths_recent_searches', JSON.stringify(recent)); } catch (e) {}
		}

		// ── Keyboard / active-index helpers ─────────────────────────────────────

		function getSelectableItems() {
			return $suggestionsDropdown.find('.suggestion-item, .suggestion-chip');
		}

		function setActiveItem(index) {
			const $items = getSelectableItems();
			$items.removeClass('is-active').attr('aria-selected', 'false');
			if (index < 0 || index >= $items.length) { activeIndex = -1; return; }
			activeIndex = index;
			$items.eq(activeIndex).addClass('is-active').attr('aria-selected', 'true');
		}

		function closeDropdown() {
			$suggestionsDropdown.hide();
			activeIndex = -1;
			getSelectableItems().removeClass('is-active').attr('aria-selected', 'false');
		}

		// ── Render: Popular Searches ─────────────────────────────────────────────

		function renderPopularSearches() {
			let html = '<div class="suggestions-header">Popular Searches</div>';
			html += '<div class="suggestions-chips-wrap">';
			POPULAR_CHIPS.forEach((chip, i) => {
				const accentClass = i === 0 ? ' chip--accent' : '';
				html += `<button type="button" class="suggestion-chip${accentClass}" role="option" aria-selected="false" data-term="${chip}">${chip}</button>`;
			});
			html += '</div>';
			$suggestionsDropdown.html(html).show();
			activeIndex = -1;
		}

		// ── Render: Recent Searches ──────────────────────────────────────────────

		function renderRecentSearches() {
			const recent = getRecentSearches();
			if (recent.length === 0) {
				renderPopularSearches();
				return;
			}
			const clockSVG = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
			let html = '<div class="suggestions-header">Recent Searches</div>';
			recent.forEach(term => {
				html += `<div class="suggestion-item recent-item" role="option" aria-selected="false" data-term="${escHtml(term)}">${clockSVG}<span class="suggestion-recent-term">${escHtml(term)}</span></div>`;
			});
			$suggestionsDropdown.html(html).show();
			activeIndex = -1;
		}

		// ── Render: Live AJAX Suggestions ────────────────────────────────────────

		function renderSuggestions(items, term) {
			const chevronSVG = `<svg class="suggestion-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>`;
			const productSVG = `<svg class="suggestion-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>`;

			let html = '';
			items.forEach(item => {
				const oem   = escHtml(item.oem_pn || '');
				const title = escHtml(item.title  || '');
				const type  = escHtml(item.type   || item.brand || '');
				const url   = item.url || '#';
				html += `
				<a href="${url}" class="suggestion-item suggestion-item--product" role="option" aria-selected="false" data-term="${oem || title}">
					${productSVG}
					<div class="suggestion-content">
						<span class="suggestion-oem">${oem}</span>
						<span class="suggestion-title">${title}</span>
						<span class="suggestion-category">${type}</span>
					</div>
					${chevronSVG}
				</a>`;
			});

			$suggestionsDropdown.html(html).show();
			activeIndex = -1;
		}

		// ── Render: Empty State ──────────────────────────────────────────────────

		function renderEmptyState() {
			const searchSVG = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>`;
			const contactUrl = (typeof ithsArchive !== 'undefined' && ithsArchive.contactUrl) ? ithsArchive.contactUrl : '/contact/';
			const html = `
			<div class="suggestion-empty">
				<div class="suggestion-empty__icon">${searchSVG}</div>
				<p class="suggestion-empty__title">No matching products found</p>
				<p class="suggestion-empty__sub">Try another OEM Part Number or <a href="${contactUrl}" class="suggestion-empty__link">contact our sales team</a>.</p>
			</div>`;
			$suggestionsDropdown.html(html).show();
			activeIndex = -1;
		}

		// ── Utility: HTML escape ─────────────────────────────────────────────────

		function escHtml(str) {
			return String(str)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#39;');
		}

		// ── Frontend validation of AJAX results ──────────────────────────────────

		function filterValidItems(data) {
			const seenOem = new Set();
			const seenUrl = new Set();
			return data.filter(item => {
				if (!item.title || !item.url) return false;
				if (/^\d+$/.test(item.title.trim())) return false;
				if (item.oem_pn) {
					if (/^\d+$/.test(item.oem_pn.trim())) return false;
					if (seenOem.has(item.oem_pn)) return false;
					seenOem.add(item.oem_pn);
				}
				if (seenUrl.has(item.url)) return false;
				seenUrl.add(item.url);
				return true;
			}).slice(0, 6);
		}

		// ── AJAX fetch suggestions ───────────────────────────────────────────────

		function fetchSuggestions(term) {
			if (term.length < 2) {
				renderRecentSearches();
				return;
			}

			// Cache hit
			if (searchCache[term]) {
				const valid = filterValidItems(searchCache[term]);
				if (valid.length > 0) { renderSuggestions(valid, term); } else { renderEmptyState(); }
				return;
			}

			// Abort previous request
			if (currentXHR && currentXHR.abort) { currentXHR.abort(); }

			currentXHR = $.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'iths_search_suggestions',
					nonce: nonce,
					search: term
				},
				success: function (response) {
					currentXHR = null;
					if (response.success && Array.isArray(response.data)) {
						searchCache[term] = response.data;
						const valid = filterValidItems(response.data);
						if (valid.length > 0) { renderSuggestions(valid, term); } else { renderEmptyState(); }
					} else {
						renderEmptyState();
					}
				},
				error: function (xhr) {
					currentXHR = null;
					if (xhr.statusText !== 'abort') { renderEmptyState(); }
				}
			});
		}

		// ── Event: Focus ─────────────────────────────────────────────────────────

		$searchInput.on('focus', function () {
			const term = $(this).val().trim();
			if (term.length < 2) {
				renderRecentSearches();
			} else {
				fetchSuggestions(term);
			}
		});

		// ── Event: Input (debounced) ──────────────────────────────────────────────

		$searchInput.on('input', function () {
			const term = $(this).val().trim();
			clearTimeout(suggestTimer);
			if (term.length < 2) {
				renderRecentSearches();
			} else {
				suggestTimer = setTimeout(function () { fetchSuggestions(term); }, 300);
			}

			// Also trigger main archive fetch (existing logic — untouched)
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () {
				addRecentSearch(term);
				fetchProducts(1);
			}, 600);
		});

		// ── Event: Keyboard navigation ────────────────────────────────────────────

		$searchInput.on('keydown', function (e) {
			if (!$suggestionsDropdown.is(':visible')) return;
			const $items = getSelectableItems();
			const total = $items.length;
			if (total === 0) return;

			if (e.key === 'ArrowDown') {
				e.preventDefault();
				setActiveItem(activeIndex < total - 1 ? activeIndex + 1 : 0);
			} else if (e.key === 'ArrowUp') {
				e.preventDefault();
				setActiveItem(activeIndex > 0 ? activeIndex - 1 : total - 1);
			} else if (e.key === 'Enter') {
				if (activeIndex >= 0) {
					e.preventDefault();
					const $active = $items.eq(activeIndex);
					if ($active.is('a')) {
						const term = $active.data('term');
						addRecentSearch(term);
						window.location.href = $active.attr('href');
					} else {
						const term = $active.data('term');
						$searchInput.val(term);
						addRecentSearch(term);
						closeDropdown();
						fetchProducts(1);
					}
				}
			} else if (e.key === 'Escape') {
				e.preventDefault();
				closeDropdown();
			}
		});

		// ── Event: Click on dropdown items ────────────────────────────────────────

		$suggestionsDropdown.on('click', '.recent-item, .suggestion-chip', function (e) {
			e.preventDefault();
			const term = $(this).data('term');
			if (!term) return;
			$searchInput.val(term);
			addRecentSearch(term);
			closeDropdown();
			fetchProducts(1);
		});

		$suggestionsDropdown.on('click', '.suggestion-item--product', function () {
			const term = $(this).data('term');
			addRecentSearch(term);
			closeDropdown();
		});

		// ── Event: Click outside ──────────────────────────────────────────────────

		$(document).on('click', function (e) {
			if (!$(e.target).closest('.search-field-wrapper').length) {
				closeDropdown();
			}
		});


		// Initialize from URL params
		function initFromURL() {
			const params = new URLSearchParams(window.location.search);
			let hasParams = false;

			$form.find('input[type="checkbox"]').each(function () {
				const $checkbox = $(this);
				let name = $checkbox.attr('name');
				if (name.endsWith('[]')) name = name.slice(0, -2);
				
				const val = $checkbox.val();
				const paramVals = params.get(name);
				
				if (paramVals) {
					hasParams = true;
					const arr = paramVals.split(',');
					if (arr.includes(val)) {
						$checkbox.prop('checked', true);
					}
				}
			});

			if (params.get('search')) {
				$searchInput.val(params.get('search'));
				hasParams = true;
			}

			if (params.get('sort')) {
				$sortSelect.val(params.get('sort'));
				hasParams = true;
			}

			if (hasParams) {
				fetchProducts(params.get('page') || 1);
			}
			updateActiveFilters();
		}

		// Update URL
		function updateURL(page) {
			const params = new URLSearchParams();
			
			const grouped = {};
			$form.find('input[type="checkbox"]:checked').each(function () {
				let name = $(this).attr('name');
				if (name.endsWith('[]')) name = name.slice(0, -2);
				if (!grouped[name]) grouped[name] = [];
				grouped[name].push($(this).val());
			});

			for (const key in grouped) {
				params.set(key, grouped[key].join(','));
			}

			if ($searchInput.val().trim() !== '') {
				params.set('search', $searchInput.val().trim());
			}

			if ($sortSelect.val() !== 'newest') {
				params.set('sort', $sortSelect.val());
			}

			if (page > 1) {
				params.set('page', page);
			}

			const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
			window.history.pushState({ path: newUrl }, '', newUrl);
		}

		// Update Active Filters Bar UI
		function updateActiveFilters() {
			$activeFiltersBar.empty();
			let html = '<div class="active-filters">';
			let count = 0;

			$form.find('input[type="checkbox"]:checked').each(function () {
				let label = $(this).siblings('.label-text').text().trim();

				const val = $(this).val();
				const name = $(this).attr('name');
				html += `<button type="button" class="active-filter-chip" data-name="${name}" data-val="${val}">
							${label}
							<span class="active-filter-chip__remove">×</span>
						 </button>`;
				count++;
			});

			if (count > 0) {
				html += `<button type="button" class="active-filter-clear" id="active-filter-clear">Clear All</button>`;
			}
			html += '</div>';

			if (count > 0) {
				$activeFiltersBar.html(html);
			}

			$('.mobile-filter-count').text(count > 0 ? `(${count})` : '');
		}

		// Fetch Products
		let activeXHR = null;
		function fetchProducts(page = 1) {
			// Abort any in-flight request to prevent race conditions
			if (activeXHR) {
				activeXHR.abort();
				activeXHR = null;
			}

			$grid.addClass('is-loading');
			$totalCount.text('Searching...');

			updateURL(page);
			updateActiveFilters();

			const data = new FormData($form[0]);
			data.append('action', 'iths_filter_products');
			data.append('nonce', nonce);
			data.append('paged', page);
			data.append('sort', $sortSelect.val());
			data.append('search', $searchInput.val().trim());

			activeXHR = $.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				success: function (response) {
					activeXHR = null;
					$grid.removeClass('is-loading');

					if (response.success) {
						// Inject HTML directly — no animations to interfere
						$grid[0].innerHTML = response.data.html;

						// CRITICAL: Product cards start at opacity:0 via animations.css
						// The IntersectionObserver in animations.js only runs at DOMContentLoaded.
						// New AJAX-injected cards are never observed, so we must make them visible manually.
						if (typeof window.ithsRevealCards === 'function') {
							window.ithsRevealCards($grid[0]);
						} else {
							// Fallback: directly add is-visible if animations.js hasn't loaded yet
							$grid.find('.product-card').addClass('is-visible').css('transition-delay', '0ms');
						}

						if ($('.view-btn--list').hasClass('active')) {
							$grid.addClass('product-grid--list');
						} else {
							$grid.removeClass('product-grid--list');
						}

						// Force the grid to be visible
						$grid[0].style.removeProperty('opacity');
						$grid[0].style.removeProperty('display');

						$paginationWrap.html(response.data.pagination);

						if (response.data.empty) {
							$totalCount.text('0 Products');
							$showingCount.text('No products to display');
						} else {
							const hasFilters = $searchInput.val().trim() !== '' || $form.find('input[type="checkbox"]:checked').length > 0;
							$totalCount.text(hasFilters ? `${response.data.count} Matching Products` : `${response.data.count} Enterprise Products`);
							const perPage = 12;
							const start = (page - 1) * perPage + 1;
							const end = Math.min(page * perPage, response.data.count);
							$showingCount.text(`Showing ${start}\u2013${end} of ${response.data.count} Products`);
						}
					}
				},
				error: function (xhr, status) {
					activeXHR = null;
					if (status === 'abort') return; // Intentional abort, ignore
					console.error('Archive AJAX Error:', xhr);
					$grid.removeClass('is-loading');
					$totalCount.text('Error Loading Products');
				}
			});
		}

		// Event Listeners
		$form.on('change', 'input[type="checkbox"]', function () {
			fetchProducts(1);
		});

		$sortSelect.on('change', function () {
			fetchProducts(1);
		});

		$('.enterprise-search-btn').on('click', function() {
			const term = $searchInput.val().trim();
			addRecentSearch(term);
			$suggestionsDropdown.hide();
			fetchProducts(1);
		});

		$(document).on('click', '#active-filter-clear', function () {
			$form.find('input[type="checkbox"]').prop('checked', false);
			fetchProducts(1);
		});

		$paginationWrap.on('click', 'a.page-numbers', function (e) {
			e.preventDefault();
			const $this = $(this);
			let page = $this.data('page') || parseInt($this.text());
			if (page) {
				fetchProducts(page);
				$('html, body').animate({ scrollTop: $('.archive-toolbar').offset().top - 100 }, 400);
			}
		});

		$activeFiltersBar.on('click', '.active-filter-chip', function () {
			const name = $(this).data('name');
			const val = $(this).data('val');
			$form.find(`input[name="${name}"][value="${val}"]`).prop('checked', false);
			fetchProducts(1);
		});

		$('#btn-clear-filters, #btn-empty-clear').on('click', function () {
			$form.find('input[type="checkbox"]').prop('checked', false);
			$searchInput.val('');
			fetchProducts(1);
		});

		$('.popular-search-chip').on('click', function () {
			const $chip = $(this);
			const isActive = $chip.hasClass('active');

			// Always deactivate all chips first
			$('.popular-search-chip').removeClass('active');

			if (isActive) {
				// Chip was already active — toggle it OFF, clear the search
				$searchInput.val('');
				fetchProducts(1);
			} else {
				// Chip was inactive — activate it and search
				$chip.addClass('active');
				const term = $chip.data('term');
				$searchInput.val(term);
				addRecentSearch(term);
				fetchProducts(1);
			}
		});

		$('.filter-group-toggle').on('click', function () {
			$(this).parent().toggleClass('open');
		});

		$('#btn-mobile-filters').on('click', function () {
			$('#archive-sidebar').addClass('is-open');
		});

		$('#btn-close-sidebar').on('click', function () {
			$('#archive-sidebar').removeClass('is-open');
		});

		$('.view-btn').on('click', function () {
			$('.view-btn').removeClass('active');
			$(this).addClass('active');
			if ($(this).hasClass('view-btn--list')) {
				$grid.addClass('product-grid--list');
			} else {
				$grid.removeClass('product-grid--list');
			}
		});

		initFromURL();
	});

})(jQuery);
