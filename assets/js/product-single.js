document.addEventListener('DOMContentLoaded', function () {

	// ── Sticky Enquiry Bar ──────────────────────────────────────────
	const stickyCard  = document.querySelector('.sticky-enquiry-card');
	const siteFooter  = document.querySelector('footer, .site-footer, #colophon');

	let footerVisible = false;
	let isScrolled    = false;

	function updateStickyVisibility() {
		if (stickyCard) {
			if (isScrolled && !footerVisible) {
				stickyCard.classList.add('is-visible');
				stickyCard.removeAttribute('aria-hidden');
				document.body.classList.add('sticky-is-visible');
			} else {
				stickyCard.classList.remove('is-visible');
				stickyCard.setAttribute('aria-hidden', 'true');
				document.body.classList.remove('sticky-is-visible');
			}
		}
	}

	// Trigger sticky bar after scrolling down 150px
	window.addEventListener('scroll', function() {
		isScrolled = window.scrollY > 150;
		updateStickyVisibility();
	}, { passive: true });

	// Check footer visibility
	if (stickyCard && siteFooter) {
		const footerObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				footerVisible = entry.isIntersecting;
				updateStickyVisibility();
			});
		}, { threshold: 0 });
		footerObserver.observe(siteFooter);
	}

	// ── Gallery Image Swapping ──────────────────────────────────────
	const mainImg     = document.querySelector('#main-product-img');
	const galleryBtns = document.querySelectorAll('.gallery-thumb-btn');

	if (mainImg && galleryBtns.length > 0) {
		galleryBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				const newSrc = this.getAttribute('data-full');
				if (newSrc) {
					mainImg.src = newSrc;
					galleryBtns.forEach(function (b) { b.classList.remove('active'); });
					this.classList.add('active');
				}
			});
		});
	}

	// ── OEM Part Number Copy Button ─────────────────────────────────
	const copyBtns = document.querySelectorAll('.oem-copy-btn');

	copyBtns.forEach(function (btn) {
		btn.addEventListener('click', function () {
			const pn = this.getAttribute('data-pn');
			if (!pn) return;

			navigator.clipboard.writeText(pn).then(function () {
				const label = btn.querySelector('.copy-label');
				btn.classList.add('copied');
				if (label) label.textContent = 'Copied!';

				setTimeout(function () {
					btn.classList.remove('copied');
					if (label) label.textContent = 'Copy';
				}, 2000);
			}).catch(function () {
				// Fallback for older browsers
				const el = document.createElement('textarea');
				el.value = pn;
				document.body.appendChild(el);
				el.select();
				document.execCommand('copy');
				document.body.removeChild(el);

				const label = btn.querySelector('.copy-label');
				btn.classList.add('copied');
				if (label) label.textContent = 'Copied!';
				setTimeout(function () {
					btn.classList.remove('copied');
					if (label) label.textContent = 'Copy';
				}, 2000);
			});
		});
	});

	// ── Section Navigation Active Highlight ────────────────────────
	const sectionLinks = document.querySelectorAll('.section-nav-link');
	const sections = [];

	sectionLinks.forEach(function (link) {
		const targetId = link.getAttribute('href').replace('#', '');
		const section  = document.getElementById(targetId);
		if (section) sections.push({ link: link, section: section });
	});

	if (sections.length > 0) {
		const navObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					sectionLinks.forEach(function (l) { l.classList.remove('active'); });
					const activeLink = document.querySelector('.section-nav-link[href="#' + entry.target.id + '"]');
					if (activeLink) activeLink.classList.add('active');
				}
			});
		}, {
			rootMargin: '-20% 0px -70% 0px',
			threshold: 0
		});

		sections.forEach(function (s) { navObserver.observe(s.section); });
	}

	// ── Smooth scroll for section nav links ────────────────────────
	sectionLinks.forEach(function (link) {
		link.addEventListener('click', function (e) {
			const targetId = this.getAttribute('href').replace('#', '');
			const target   = document.getElementById(targetId);
			if (target) {
				e.preventDefault();
				const navHeight = document.getElementById('section-nav') ? document.getElementById('section-nav').offsetHeight : 0;
				const top = target.getBoundingClientRect().top + window.scrollY - navHeight - 16;
				window.scrollTo({ top: top, behavior: 'smooth' });
			}
		});
	});

});
