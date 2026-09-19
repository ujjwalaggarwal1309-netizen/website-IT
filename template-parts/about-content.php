<?php
/**
 * Template Part: About Content
 *
 * Renders three sections on the About Us page:
 *   - Section 1: "Who We Are" — image + 4-paragraph narrative
 *   - Section 2: Mission, Vision, Core Values cards
 *   - Section 3: Industries Served
 *
 * Content source: WEBSITE_CONTENT.md — "About Page"
 * Data source:    VERIFIED_COMPANY_FACTS.md
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php /* ── 1. About Hero (Storytelling) ────────────────────────────── */ ?>
<section class="about" aria-labelledby="about-page-title">
	<div class="about-hero-section">
		<!-- CSS-only Backgrounds -->
		<div class="about-hero-bg"></div>
		
		<div class="container">
			<!-- 55/45 Grid -->
			<div class="about-hero__grid">
			
			<!-- Left (55%) -->
			<div class="about-hero__content">
				<span class="about-hero__eyebrow"><?php esc_html_e( 'ABOUT INFINITY IT SOLUTIONS', 'it-hardware-supply' ); ?></span>
				<h1 id="about-page-title" class="about-hero__title"><?php esc_html_e( 'Building', 'it-hardware-supply' ); ?><br><?php esc_html_e( 'the backbone of', 'it-hardware-supply' ); ?><br><?php esc_html_e( 'modern enterprise.', 'it-hardware-supply' ); ?></h1>
				
				<div class="about-hero__text">
					<p><?php esc_html_e( 'Infinity IT Solutions was founded in 2018 with a simple mission to provide dependable enterprise hardware backed by exceptional service.', 'it-hardware-supply' ); ?></p>
					<p><?php esc_html_e( 'Over the years, we have become a trusted supplier of servers, storage solutions, workstations, desktops, and genuine spare parts for businesses across India.', 'it-hardware-supply' ); ?></p>
				</div>
				
				<div class="about-hero__actions">
					<a href="#solutions" class="btn btn-primary">
						<?php esc_html_e( 'Explore Solutions', 'it-hardware-supply' ); ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>

			<!-- Right (45%) -->
			<div class="about-hero__visual-col">
				<div class="about-hero__visual">
					<!-- Glow Layer Background -->
					<div class="about-hero__network-glow"></div>
					
					<!-- v4.0 Native Enterprise SVG Network -->
					<svg class="unified-network-svg" viewBox="0 0 666.66669 777.33331" preserveAspectRatio="xMidYMid meet">
						<defs>
							<filter id="glow-blue" x="-20%" y="-20%" width="140%" height="140%">
								<feGaussianBlur stdDeviation="3" result="blur" />
								<feMerge>
									<feMergeNode in="blur"/>
									<feMergeNode in="SourceGraphic"/>
								</feMerge>
							</filter>
							<filter id="node-glow" x="-50%" y="-50%" width="200%" height="200%">
								<feGaussianBlur stdDeviation="4" result="blur" />
								<feComponentTransfer in="blur" result="glow">
									<feFuncA type="linear" slope="0.5" />
								</feComponentTransfer>
								<feMerge>
									<feMergeNode in="glow"/>
									<feMergeNode in="SourceGraphic"/>
								</feMerge>
							</filter>
							<filter id="packet-glow" x="-50%" y="-50%" width="200%" height="200%">
								<feGaussianBlur stdDeviation="2" result="blur" />
								<feComponentTransfer in="blur" result="glow">
									<feFuncA type="linear" slope="0.8" />
								</feComponentTransfer>
								<feMerge>
									<feMergeNode in="glow"/>
									<feMergeNode in="SourceGraphic"/>
								</feMerge>
							</filter>
						</defs>
						
						<g id="india-outline" fill="none" stroke="#5486d1" stroke-width="4" filter="url(#glow-blue)" opacity="0.10">
							<path class="map-path" d="m 215.2163,738.62564 -4,-2.84704 -5.08396,-4.77739 -5.08397,-4.77738 -2.58571,-4.16224 -2.58571,-4.16225 -2.73664,-11.17108 -2.73662,-11.1711 -4.80426,-12.66666 -4.80425,-12.66667 -3.9362,-8 -3.9362,-8 -5.01572,-6.66667 -5.01572,-6.66666 -4.17085,-10.95699 -4.17086,-10.95699 v -3.31493 -3.31493 l -3.95422,-12.19948 -3.95423,-12.19949 -6.04578,-10.31244 -6.04577,-10.31246 v -0.87925 -0.87925 l -3.817,-8.3369 -3.81701,-8.33689 -1.48505,-4 -1.48505,-4 -1.42982,-12 -1.42982,-12 -4.14814,-15.92888 -4.14815,-15.92888 1.54669,-0.51557 1.54668,-0.51556 v -3.36487 -3.36488 l -1.81762,0.6975 -1.81763,0.69748 -0.85927,-10.30346 -0.85928,-10.30345 2.77715,-8.4596 2.77715,-8.45961 -2.95944,-9.20386 -2.95943,-9.20386 2.85918,-3.86726 2.85919,-3.86725 v -0.62793 -0.62792 l -2.82757,-0.42607 -2.82757,-0.42607 -0.53124,-5.97786 -0.53125,-5.97787 -1.83309,-1.83308 -1.83308,-1.83309 -1.47477,6.87658 -1.47476,6.87658 -0.005,2.93437 -0.005,2.93437 -2.693839,4.41843 -2.69383,4.41844 -8.35049,3.90767 -8.350489,3.90766 -7.55505,1.39663 -7.55505,1.39663 -5.28474,-3.33611 -5.28474,-3.33612 -5.1109,-6.26743 -5.1109,-6.26744 -6.29777,-7.09401 -6.29777,-7.09403 -3.58171,-5.41234 -3.58172,-5.41234 1.39566,-1.39566 1.39566,-1.39566 0.89585,1.44951 0.89584,1.44951 5.58798,-0.008 5.58798,-0.008 4.83022,-1.3417 4.83022,-1.34169 6.2277,-5.48184 6.2277,-5.48184 -0.50394,-0.50394 -0.50394,-0.50394 -8.78786,2.16862 -8.78785,2.16862 -4.48755,-0.84186 -4.48755,-0.84187 -7.54448,-4.71911 -7.54448,-4.7191 -2.06743,-4.14426 -2.06753,-4.14313 v -1.6418 -1.6418 l 3.44902,-2.25988 3.44902,-2.25989 -0.72543,-0.72542 -0.72542,-0.72542 -4.79981,2.83196 -4.79981,2.83196 h -0.67902 -0.67901 l 0.42189,-3.66667 0.4219,-3.66667 4.97993,-0.41262 4.97994,-0.41263 1.96682,-2.80804 1.96683,-2.80804 5.71991,0.17657 5.71991,0.17656 7.64598,0.83928 7.64599,0.83928 6.69897,-1.71689 6.69897,-1.71689 1.58837,1.58838 1.58838,1.58838 h 3.15432 3.15433 l 2.49487,-1.33522 2.49488,-1.33522 -0.75993,-4.99811 -0.75994,-4.99812 -3.15593,-9.83685 -3.15593,-9.83687 v -1.8406 -1.8406 l -5.34549,-2.13884 -5.34548,-2.13884 -0.80513,-3.20788 -0.80512,-3.20787 0.91322,-7.30916 0.91322,-7.30916 h -2.82883 -2.82882 l -3.91447,-2.02425 -3.91447,-2.02425 -0.80629,-3.2125 -0.80628,-3.2125 1.1203,-1.95513 1.12031,-1.95512 9.50149,-10.14146 9.5015,-10.14145 h 2.74199 2.74198 l 0.80815,2.54625 0.80816,2.54627 1.67494,1.39008 1.67494,1.39008 9.94009,-1.78487 9.940089,-1.78486 2,-0.90115 1.999999,-0.90113 5.33334,-6.7847 5.33333,-6.78468 3.72072,-3.46565 3.72071,-3.46564 5.93016,-5.83483 5.93016,-5.83481 3.55728,-7.27084 3.55729,-7.27083 3.45851,-2.1405 3.45851,-2.14051 3,-2.65292 3,-2.65293 v -1.91003 -1.91004 l 5.45108,-6.85755 5.45108,-6.85754 1.66944,-0.94715 1.66942,-0.94716 -0.31645,-9.05284 -0.31645,-9.05285 2.02382,-2.28313 2.02384,-2.28315 4.83878,-1.55729 4.83877,-1.5573 1.66667,-1.32282 1.66666,-1.32283 v -1.85407 -1.85406 l -5.76484,-2.80079 -5.76485,-2.80077 -1.45267,-3.84856 -1.45253,-3.84856 h -2.59459 -2.5946 l -7.85456,-3.95934 -7.85456,-3.95934 -1.57907,-17.70733 -1.57908,-17.707325 7.47046,-6.786157 7.47045,-6.786156 -0.89811,-2.340429 -0.8981,-2.34043 -2.58502,-0.675996 -2.58501,-0.675996 -0.40827,-2.858883 -0.40825,-2.858883 -4.66667,-2.194372 -4.66667,-2.194372 -2.53998,-2.810829 -2.53999,-2.810829 h -3.74643 -3.74644 l -1.45411,-2.717031 -1.45411,-2.717029 0.74048,-2.333039 0.74048,-2.333038 6.72316,-6.616599 6.72317,-6.616597 h 6.94355 6.94356 l -0.45187,-2.594964 -0.45185,-2.594964 1.85544,1.53988 1.85544,1.539881 3.92975,-2.097217 3.92976,-2.097217 9.73397,-0.217171 9.73397,-0.217172 9.06994,8.702805 9.06993,8.702805 4.40104,3.271139 4.40103,3.27114 2.6031,4.411929 2.60311,4.411929 5.85861,1.475219 5.85863,1.475217 v 2.50838 2.50838 h 5.14564 5.14564 l 7.58808,-4.049225 7.58808,-4.049227 6.13351,-0.62063 6.13351,-0.620631 1.70365,-1.413913 1.70367,-1.413914 4.02957,2.083771 4.02956,2.083769 h 1.96391 1.96392 l 4.10229,3.451859 4.10231,3.451857 v 2.339408 2.339408 l -2.56184,7.446105 -2.56183,7.446105 -5.03233,4.746637 -5.03234,4.746639 -1.40582,4.287074 -1.40584,4.287069 -4.66667,0.0623 -4.66667,0.0622 v 4.21767 4.21768 l -1,1.00455 -1,1.00454 v 3.13269 3.13268 l 4.66667,2.22538 4.66667,2.22537 0.0472,2.75305 0.0472,2.75305 1.9055,3.33334 1.90551,3.33333 0.0472,1.70851 0.0472,1.70852 -2.33333,0.822 -2.33334,0.82198 -4.34937,3.21626 -4.34939,3.21625 -2.31728,-4.07167 -2.31729,-4.07165 -1.50341,-0.008 -1.5034,-0.008 -1.7146,2.06597 -1.7146,2.06596 2.99361,7.22264 2.99361,7.22263 0.96058,7.07852 0.96057,7.07852 0.99231,1.60557 0.99229,1.60558 2.63773,-1.63936 2.63774,-1.63936 3.2599,4.15804 3.2599,4.15804 7.57764,3.58784 7.57762,3.58784 2.6316,3.34553 2.6316,3.34553 7.03387,3.11071 7.03388,3.11071 -6.91036,7.13454 -6.91036,7.13455 -3.14015,10.57763 -3.14014,10.57762 3.84796,3.07908 3.84794,3.07909 1.20044,0.007 1.20044,0.007 7.46623,3.5886 7.46623,3.58858 2.99262,2.88888 2.99262,2.88888 5.67405,2.7075 5.67404,2.70749 4.66667,1.63416 4.66667,1.63417 3.70365,0.67096 3.70365,0.67096 1.31255,2.45254 1.31256,2.45252 4.50292,0.84476 4.50292,0.84474 9.8142,-1.55618 9.81421,-1.5562 4.80499,2.03856 4.80499,2.03857 2.21452,3.37977 2.2145,3.37976 5.26072,2.68383 5.26071,2.68381 h 3.88987 3.88985 l 1.52101,1.83271 1.52102,1.83271 6.97557,0.99337 6.97559,0.99336 6.96587,0.25089 6.96585,0.25091 0.92303,0.92303 0.92302,0.92302 8.11112,-0.0204 8.11111,-0.0204 1.61844,-1.02846 1.61844,-1.02846 0.10853,-15.95112 0.10854,-15.95112 0.086,-2.91381 0.086,-2.91381 5.38509,-2.15468 5.38509,-2.15468 1.40227,1.40226 1.40228,1.40228 0.38401,9.5432 0.384,9.54319 2.03242,3.10187 2.03241,3.10185 3.6498,1.00557 3.6498,1.00559 8.66667,-0.20035 8.66666,-0.20033 6,-0.20389 6,-0.2039 15.04815,-2.13725 15.04815,-2.13727 -0.88471,-6.10958 -0.88469,-6.10959 -0.65955,-1.13604 -0.65954,-1.13605 -4.50391,-1.88184 -4.50389,-1.88186 v -2.73862 -2.73863 l 2.82554,0.8968 2.82555,0.89679 3.50779,-1.61398 3.50778,-1.61398 4.54252,-2.18224 4.54254,-2.18226 0.58048,-2.21973 0.58046,-2.21975 5.21034,-4.18713 5.21033,-4.18715 v -1.98454 -1.98455 l 5.89897,-2.5286 5.89896,-2.52858 7.78179,-8.17726 7.78177,-8.17725 3.98592,2.07835 3.98592,2.07834 4.25224,0.008 4.25224,0.008 6.0931,-5.27353 6.09309,-5.27353 3.07748,2.9402 3.0775,2.9402 -1.0895,1.26666 -1.08948,1.26667 v 2.56325 2.56327 l 2.14428,-1.7796 2.1443,-1.7796 1.8557,2.53785 1.85572,2.53784 -0.0472,1.41183 -0.0472,1.41183 -1.9055,3.33333 -1.90551,3.33333 -0.0472,1.05771 -0.0472,1.05771 4.33333,-0.2973 4.33334,-0.2973 4.96926,0.23958 4.96926,0.2396 2.23409,3.40966 2.23409,3.40965 -2.88893,3.59034 -2.88893,3.59035 -1.49258,2.92128 -1.49257,2.92127 2.79985,4.7454 2.79986,4.74538 h -1.03354 -1.03352 l -2.58817,-1.9576 -2.58817,-1.95758 -2.79936,-0.0424 -2.79936,-0.0424 -5.38843,3.66666 -5.38843,3.66667 -8.81221,8.28771 -8.81222,8.28769 v 11.11993 11.11992 l -3.43397,4.59238 -3.43397,4.59237 0.71976,5.33333 0.71976,5.33334 -4.21271,12 -4.21271,12 -1.25398,0.85716 -1.25398,0.85715 -7.77806,-0.70529 -7.77807,-0.70529 1.29229,4.506 1.29231,4.506 v 8.19948 8.19948 l -2,0.76748 -2,0.76746 v 13.12035 13.12033 l -1.66667,2.13243 -1.66666,2.13241 -2.92642,-1.14072 -2.92641,-1.14073 -0.87372,-5.07019 -0.87371,-5.07018 -3.11882,-12 -3.11883,-12 -1.75353,-5.33334 -1.75352,-5.33333 h -1.97623 -1.97621 l -1.8764,3.91499 -1.87642,3.91498 -0.65174,5.7823 -0.65175,5.78229 -1.35809,0.83935 -1.35811,0.83936 -2.58125,-3.54351 -2.58127,-3.54349 -1.04625,0.64662 -1.04626,0.64662 -2.29006,-5.99647 -2.29008,-5.99647 1.78588,-5.56113 1.78586,-5.56113 3.87506,-1.02258 3.87505,-1.02257 4.39667,-4.26142 4.39668,-4.26142 0.97984,-4.13149 0.97984,-4.13149 -0.31002,-1.66667 -0.31001,-1.66667 h 1.55504 1.55504 l -1.65985,-2 -1.65986,-2 -22.83673,-0.13293 -22.83675,-0.13293 -3.33333,-0.92696 -3.33334,-0.92698 -0.96818,-7.94012 -0.96818,-7.94013 -1.51006,-3.33333 -1.51006,-3.33334 -1.99806,2.9704 -1.99807,2.97039 -2.85703,-1.47487 -2.85702,-1.47486 -3.08122,-3.49554 -3.08121,-3.49552 -0.69656,1.66667 -0.69657,1.66667 -2.22222,-0.0424 -2.22222,-0.0424 -2.58818,-1.95759 -2.58817,-1.9576 h -1.15719 -1.15717 l 0.76739,1.24165 0.76738,1.24167 -3.35537,4.9444 -3.35536,4.9444 v 1.26105 1.26106 l 4.33333,3.81332 4.33334,3.8133 4.56388,3.07291 4.56386,3.07291 0.54724,1.66666 0.54724,1.66667 h -2.80757 -2.80757 l -5.9702,5.50315 -5.97022,5.50314 v 2.429 2.429 l 4.45919,3.40119 4.45919,3.40118 h 2.18208 2.18208 l 0.80509,3.20775 0.80509,3.20775 -2.24342,3.42389 -2.24342,3.42389 2.79706,4.52574 2.79706,4.52574 v 2.30078 2.30078 l 2.56204,0.66999 2.56206,0.66999 -0.61258,4.20518 -0.61256,4.20519 2.58803,10.56085 2.58804,10.56087 -0.68017,1.77247 -0.68016,1.77248 h -3.23759 -3.2376 l -3.35488,2.1982 -3.35488,2.19821 -1.13545,-1.19821 -1.13546,-1.1982 -0.46276,-2.56956 -0.46274,-2.56956 -3.48502,5.23622 -3.48501,5.23623 -6.83025,2.38175 -6.83026,2.38174 -3.61598,3.11035 -3.61599,3.11033 1.49424,6.50792 1.49424,6.50791 -1.89632,2.10499 -1.89632,2.10498 v 2.18391 2.18389 l -4.56725,4.56726 -4.56726,4.56725 -7.35017,3.4772 -7.35019,3.47718 h -1.97145 -1.97146 l -0.52441,-1.57321 -0.5244,-1.57321 -2.26491,0.86912 -2.2649,0.86913 -1.63688,3.70409 -1.63688,3.70408 -9.53804,12.66667 -9.53804,12.66667 -7.60648,6.51918 -7.60648,6.51918 -3.89774,5.38068 -3.89774,5.38068 -6.30932,3.63956 -6.30934,3.63956 -3,2.84585 -3,2.84585 v 5.219 5.219 l -6.33333,2.95159 -6.33334,2.95157 -5.25897,0.1108 -5.25899,0.1108 -4.07434,5.47402 -4.07436,5.47401 -1.70722,2.52599 -1.70721,2.52598 -0.73723,-1.66666 -0.73724,-1.66667 h -3.59221 -3.59221 l -2.98119,2.08811 -2.9812,2.0881 -2.69829,5.28912 -2.69831,5.28911 0.63792,13.66356 0.63793,13.66356 1.41156,3.71268 1.41156,3.71269 v 1.24653 1.24654 h -2 -2 v 1.28642 1.28642 l 2.83419,1.51681 2.8342,1.51681 -0.86745,7.3807 -0.86747,7.38068 -1.74589,3.48274 -1.7459,3.48275 -3.76314,7.23493 -3.76316,7.23495 0.23496,16.76505 0.23496,16.76507 0.71644,2.28892 0.71644,2.28892 -3.07576,0.6788 -3.07575,0.67881 -3.91979,0.3656 -3.91977,0.36561 -3.0138,5.33334 -3.01379,5.33333 -2.34532,4.40032 -2.34532,4.40032 1.50588,2.41131 1.5059,2.4113 -6.91764,1.5481 -6.91763,1.54809 -4.21623,2.56405 -4.21624,2.56404 -0.86989,5.80086 -0.86988,5.80085 -6.41975,3.60871 -6.41975,3.60872 -3.80349,-0.0423 -3.80349,-0.0423 -4,-2.84704 z" />
						</g>

						<g id="network-lines">
							<!-- Spokes (Central Hub to Outer Nodes - Bundled into Trunks) -->
							<path d="M 239,526 Q 239,350 211,253" class="data-flow-line" />
							<path d="M 239,526 Q 239,350 293,297" class="data-flow-line" />
							<path d="M 239,526 Q 239,350 530,315" class="data-flow-line" />
							<path d="M 239,526 Q 239,350 456,403" class="data-flow-line" />
							<path d="M 239,526 Q 170,550 219,628" class="data-flow-line" />
							<path d="M 239,526 Q 170,400 115,486" class="data-flow-line" />
							
							<!-- Ring (Outer Perimeter Connections) -->
							<path d="M 211,253 Q 250,260 293,297" class="data-flow-line" />
							<path d="M 293,297 Q 410,280 530,315" class="data-flow-line" />
							<path d="M 530,315 Q 460,350 456,403" class="data-flow-line" />
							<path d="M 456,403 Q 300,500 219,628" class="data-flow-line" />
							<path d="M 219,628 Q 180,560 115,486" class="data-flow-line" />
							<path d="M 115,486 Q 180,370 211,253" class="data-flow-line" />
						</g>
						
						<g id="secondary-hubs">
							<!-- Spoke Nodes (Trunks) - Staggered cleanly along curves -->
							<circle cx="237" cy="443" r="3" class="network-node secondary-node" />
							<circle cx="258" cy="359" r="4" class="network-node secondary-node" />
							<circle cx="387" cy="347" r="3" class="network-node secondary-node" />
							<circle cx="283" cy="414" r="4" class="network-node secondary-node" />
							<circle cx="205" cy="549" r="3" class="network-node secondary-node" />
							<circle cx="155" cy="452" r="4" class="network-node secondary-node" />
							
							<!-- Ring Nodes -->
							<circle cx="251" cy="268" r="3" class="network-node secondary-node" />
							<circle cx="411" cy="293" r="4" class="network-node secondary-node" />
							<circle cx="477" cy="355" r="3" class="network-node secondary-node" />
							<circle cx="319" cy="508" r="4" class="network-node secondary-node" />
							<circle cx="174" cy="559" r="3" class="network-node secondary-node" />
							<circle cx="172" cy="370" r="4" class="network-node secondary-node" />
						</g>

						<g id="primary-hubs">
							<!-- Radar Pings -->
							<circle cx="211" cy="253" r="7.5" class="radar-ping hub-1" />
							<circle cx="115" cy="486" r="7.5" class="radar-ping hub-2" />
							<circle cx="239" cy="526" r="7.5" class="radar-ping hub-3" />
							<circle cx="456" cy="403" r="7.5" class="radar-ping hub-4" />
							<circle cx="219" cy="628" r="7.5" class="radar-ping hub-5" />
							<circle cx="530" cy="315" r="7.5" class="radar-ping hub-6" />
							<circle cx="293" cy="297" r="7.5" class="radar-ping hub-8" />

							<!-- North (Delay 0s) -->
							<circle cx="211" cy="253" r="7.5" class="network-node primary-node hub-1" filter="url(#node-glow)" />
							<!-- West (Delay 1s) -->
							<circle cx="115" cy="486" r="7.5" class="network-node primary-node hub-2" filter="url(#node-glow)" />
							<!-- Central (Delay 2s) -->
							<circle cx="239" cy="526" r="7.5" class="network-node primary-node hub-3" filter="url(#node-glow)" />
							<!-- East (Delay 3s) -->
							<circle cx="456" cy="403" r="7.5" class="network-node primary-node hub-4" filter="url(#node-glow)" />
							<!-- South (Delay 4s) -->
							<circle cx="219" cy="628" r="7.5" class="network-node primary-node hub-5" filter="url(#node-glow)" />
							<!-- Northeast (Delay 5s) -->
							<circle cx="530" cy="315" r="7.5" class="network-node primary-node hub-6" filter="url(#node-glow)" />
							<!-- Lucknow (Delay 7s) -->
							<circle cx="293" cy="297" r="7.5" class="network-node primary-node hub-8" filter="url(#node-glow)" />
						</g>

						<g id="data-packets">
							<!-- Spoke Packets (Trunks) -->
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="7s" repeatCount="indefinite" path="M 239,526 Q 239,350 211,253 Q 239,350 239,526" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="8s" repeatCount="indefinite" path="M 239,526 Q 239,350 293,297 Q 239,350 239,526" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="9s" repeatCount="indefinite" path="M 239,526 Q 239,350 530,315 Q 239,350 239,526" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="10s" repeatCount="indefinite" path="M 239,526 Q 239,350 456,403 Q 239,350 239,526" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="7s" repeatCount="indefinite" path="M 239,526 Q 170,550 219,628 Q 170,550 239,526" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="8s" repeatCount="indefinite" path="M 239,526 Q 170,400 115,486 Q 170,400 239,526" />
							</circle>
							
							<!-- Ring Packets -->
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="10s" repeatCount="indefinite" path="M 211,253 Q 250,260 293,297 Q 250,260 211,253" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="11s" repeatCount="indefinite" path="M 293,297 Q 410,280 530,315 Q 410,280 293,297" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="12s" repeatCount="indefinite" path="M 530,315 Q 460,350 456,403 Q 460,350 530,315" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="13s" repeatCount="indefinite" path="M 456,403 Q 300,500 219,628 Q 300,500 456,403" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="10s" repeatCount="indefinite" path="M 219,628 Q 180,560 115,486 Q 180,560 219,628" />
							</circle>
							<circle r="2.5" class="data-packet" filter="url(#packet-glow)">
								<animateMotion dur="11s" repeatCount="indefinite" path="M 115,486 Q 180,370 211,253 Q 180,370 115,486" />
							</circle>
						</g>

						<g id="particles" opacity="0.6">
							<circle cx="150" cy="200" r="1.5" class="floating-particle float-1" />
							<circle cx="300" cy="250" r="2" class="floating-particle float-2" />
							<circle cx="450" cy="300" r="1.5" class="floating-particle float-3" />
							<circle cx="120" cy="450" r="2" class="floating-particle float-4" />
							<circle cx="280" cy="380" r="1.5" class="floating-particle float-5" />
							
							<circle cx="500" cy="450" r="1.5" class="floating-particle float-1" />
							<circle cx="220" cy="550" r="2" class="floating-particle float-2" />
							<circle cx="340" cy="600" r="1.5" class="floating-particle float-3" />
							<circle cx="480" cy="520" r="2" class="floating-particle float-4" />
							<circle cx="250" cy="720" r="1.5" class="floating-particle float-5" />

							<circle cx="180" cy="330" r="2" class="floating-particle float-1" />
							<circle cx="380" cy="270" r="1.5" class="floating-particle float-2" />
							<circle cx="520" cy="380" r="2" class="floating-particle float-3" />
							<circle cx="260" cy="480" r="1.5" class="floating-particle float-4" />
							<circle cx="170" cy="620" r="2" class="floating-particle float-5" />

							<circle cx="420" cy="580" r="1.5" class="floating-particle float-1" />
							<circle cx="310" cy="680" r="2" class="floating-particle float-2" />
							<circle cx="140" cy="280" r="1.5" class="floating-particle float-3" />
							<circle cx="390" cy="320" r="2" class="floating-particle float-4" />
							<circle cx="580" cy="340" r="1.5" class="floating-particle float-5" />
						</g>
					</svg>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Hero Section -->

<!-- Timeline Begins directly -->
	<section class="about-timeline-section">
		<div class="container">
			<div class="about-timeline-wrapper">
				<div class="about-timeline">
				<div class="timeline-line"></div>
				<!-- Timeline Progress Dots -->
				<div class="timeline-dot" style="left: 20%;"></div>
				<div class="timeline-dot" style="left: 40%;"></div>
				<div class="timeline-dot" style="left: 60%;"></div>
				<div class="timeline-dot" style="left: 80%;"></div>
				
				<!-- Milestones -->
				<div class="timeline-item">
					<div class="timeline-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
							<line x1="4" y1="22" x2="4" y2="15"></line>
							<polygon points="12,2 15,8 21,9 16,14 17,20 12,17 7,20 8,14 3,9 9,8" class="icon-accent-fill" stroke="none" transform="scale(0.3) translate(50, 10)" />
						</svg>
					</div>
					<div class="timeline-content">
						<strong>2018</strong>
						<span>Company<br>Founded</span>
					</div>
				</div>
				
				<div class="timeline-item">
					<div class="timeline-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="1" y="3" width="15" height="13"></rect>
							<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
							<circle cx="5.5" cy="18.5" r="2.5" class="icon-accent-stroke"></circle>
							<circle cx="18.5" cy="18.5" r="2.5" class="icon-accent-stroke"></circle>
						</svg>
					</div>
					<div class="timeline-content">
						<strong>2020</strong>
						<span>Expanded<br>PAN India</span>
					</div>
				</div>
				
				<div class="timeline-item">
					<div class="timeline-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
							<polyline points="3.27 6.96 12 12.01 20.73 6.96" class="icon-accent-stroke"></polyline>
							<line x1="12" y1="22.08" x2="12" y2="12"></line>
						</svg>
					</div>
					<div class="timeline-content">
						<strong>2022</strong>
						<span>Added Enterprise<br>Solutions</span>
					</div>
				</div>
				
				<div class="timeline-item">
					<div class="timeline-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="18" y1="20" x2="18" y2="10"></line>
							<line x1="12" y1="20" x2="12" y2="4"></line>
							<line x1="6" y1="20" x2="6" y2="14"></line>
							<polyline points="3 8 9 2 15 8 21 2"></polyline>
							<polygon points="21,2 18,2 21,5" class="icon-accent-fill" stroke="none"/>
						</svg>
					</div>
					<div class="timeline-content">
						<strong>2024</strong>
						<span>Scaled Product<br>Portfolio</span>
					</div>
				</div>
				
				<div class="timeline-item">
					<div class="timeline-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="9" cy="7" r="4" class="icon-accent-stroke"></circle>
							<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
							<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
						</svg>
					</div>
					<div class="timeline-content">
						<strong>2026+</strong>
						<span>Supporting Businesses<br>Nationwide</span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Trust Strip -->
	<?php get_template_part( 'template-parts/stats-about' ); ?>
</section>

<?php /* ── SECTION 2: Mission / Vision / Core Values ───────────────────── */ ?>
<section class="mission-section fade-in" aria-labelledby="mission-section-heading">
	<div class="container">
		<div class="section-heading text-center">
			<span class="section-label"><?php esc_html_e( 'Our Foundation', 'it-hardware-supply' ); ?></span>
			<h2 id="mission-section-heading"><?php esc_html_e( 'Mission, Vision &amp; Values', 'it-hardware-supply' ); ?></h2>
		</div>

		<div class="mission-grid premium-grid">
			<?php get_template_part( 'template-parts/mission-card' ); ?>
		</div>
	</div>
</section>

<?php /* ── SECTION 3: Industries Served ────────────────────────────────── */ ?>
<section class="industries-section fade-in" aria-labelledby="industries-heading">
	<div class="container">
		<div class="section-heading text-center">
			<span class="section-label"><?php esc_html_e( 'Industries', 'it-hardware-supply' ); ?></span>
			<h2 id="industries-heading"><?php esc_html_e( 'Industries We Serve', 'it-hardware-supply' ); ?></h2>
			<p><?php esc_html_e( 'Delivering tailored enterprise IT solutions for diverse business sectors across India.', 'it-hardware-supply' ); ?></p>
		</div>

		<?php
		/**
		 * Verified industries from VERIFIED_COMPANY_FACTS.md — "Industries Served".
		 * Option 4 Global Card Standard applied.
		 */
		$industries = array(
			array(
				'label'    => __( 'IT Companies', 'it-hardware-supply' ),
				'subtitle' => __( 'Technology procurement support', 'it-hardware-supply' ),
				'badge'    => __( 'Technology Solutions', 'it-hardware-supply' ),
				'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
				'bg_svg'   => '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/illustrations/industries/it-companies.svg' ) . '" class="card-illustration" alt="" aria-hidden="true" />',
			),
			array(
				'label'    => __( 'System Integrators', 'it-hardware-supply' ),
				'subtitle' => __( 'Infrastructure deployment partners', 'it-hardware-supply' ),
				'badge'    => __( 'Deployment Partners', 'it-hardware-supply' ),
				'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>',
				'bg_svg'   => '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/illustrations/industries/system-integrators.svg' ) . '" class="card-illustration" alt="" aria-hidden="true" />',
			),
			array(
				'label'    => __( 'Data Centers', 'it-hardware-supply' ),
				'subtitle' => __( 'Enterprise hardware solutions', 'it-hardware-supply' ),
				'badge'    => __( 'Mission Critical', 'it-hardware-supply' ),
				'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>',
				'bg_svg'   => '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/illustrations/industries/data-centers.svg' ) . '" class="card-illustration" alt="" aria-hidden="true" />',
			),
			array(
				'label'    => __( 'Corporate Enterprises', 'it-hardware-supply' ),
				'subtitle' => __( 'Scalable IT infrastructure', 'it-hardware-supply' ),
				'badge'    => __( 'Enterprise Scale', 'it-hardware-supply' ),
				'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
				'bg_svg'   => '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/illustrations/industries/corporate-enterprises.svg' ) . '" class="card-illustration" alt="" aria-hidden="true" />',
			),
			array(
				'label'    => __( 'SMEs', 'it-hardware-supply' ),
				'subtitle' => __( 'Cost-effective business solutions', 'it-hardware-supply' ),
				'badge'    => __( 'Business Growth', 'it-hardware-supply' ),
				'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
				'bg_svg'   => '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/illustrations/industries/smes.svg' ) . '" class="card-illustration" alt="" aria-hidden="true" />',
			),
			array(
				'label'    => __( 'IT-Based Industries', 'it-hardware-supply' ),
				'subtitle' => __( 'Reliable technology support', 'it-hardware-supply' ),
				'badge'    => __( 'Industry Specific', 'it-hardware-supply' ),
				'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2"/><path d="M7 14H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2"/><polyline points="7 14 12 9 17 14"/></svg>',
				'bg_svg'   => '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/illustrations/industries/it-based-industries.svg' ) . '" class="card-illustration" alt="" aria-hidden="true" />',
			),
		);
		?>
		<ul class="premium-grid" role="list" aria-label="<?php esc_attr_e( 'Industries served by Infinity IT Solutions', 'it-hardware-supply' ); ?>">
			<?php foreach ( $industries as $industry ) : ?>
			<li class="card-premium category-card">
				<div class="category-card__content">
					<div class="card-premium__icon" aria-hidden="true">
						<?php echo $industry['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3 class="card-premium__title"><?php echo esc_html( $industry['label'] ); ?></h3>
					<div class="card-premium__divider" aria-hidden="true"></div>
					<p class="card-premium__description"><?php echo esc_html( $industry['subtitle'] ); ?></p>
					
					<div class="category-card__badge-wrap" style="margin-bottom: 24px;">
						<span class="category-badge" style="display:inline-block; padding: 8px 14px; background: rgba(22,63,168,.08); color: #163FA8; border-radius: 999px; font-size: 12px; font-family: 'Inter', sans-serif; font-weight: 700; letter-spacing: 0.5px;"><?php echo esc_html( $industry['badge'] ); ?></span>
					</div>

					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="card-premium__link" aria-label="<?php echo esc_attr( sprintf( __( 'Request a quote for %s', 'it-hardware-supply' ), $industry['label'] ) ); ?>">
						<span class="card-premium__link-text"><?php esc_html_e( 'Request a Quote', 'it-hardware-supply' ); ?></span>
						<svg class="card-premium__link-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
				<?php echo $industry['bg_svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
