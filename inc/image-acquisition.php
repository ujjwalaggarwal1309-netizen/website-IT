<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ITHS_Image_Acquisition {

	private $api_key_option = 'iths_serper_api_key';
	private $test_mode_option = 'iths_acquisition_test_mode';
	
	private $trusted_domains = [
		'hpe.com', 'dell.com', 'cisco.com', 'ibm.com', 'fujitsu.com', 
		'seagate.com', 'westerndigital.com', 'broadcom.com', 'cdw.com', 
		'insight.com', 'bhphotovideo.com', 'pcconnection.com', 
		'servermonkey.com', 'storagereview.com'
	];

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		
		// AJAX Handlers
		add_action( 'wp_ajax_iths_img_search', [ $this, 'ajax_search' ] );
		add_action( 'wp_ajax_iths_img_approve', [ $this, 'ajax_approve' ] );
		add_action( 'wp_ajax_iths_img_reject', [ $this, 'ajax_reject' ] );
	}

	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=products',
			'OEM Image Acquisition',
			'Image Acquisition',
			'manage_options',
			'oem-image-acquisition',
			[ $this, 'render_admin_page' ]
		);
	}

	public function register_settings() {
		register_setting( 'iths_acquisition_settings', $this->api_key_option );
		register_setting( 'iths_acquisition_settings', $this->test_mode_option );
		
		// Set test mode to ON by default if not set
		if ( get_option( $this->test_mode_option, false ) === false ) {
			update_option( $this->test_mode_option, '1' );
		}
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$api_key = get_option( $this->api_key_option );
		$is_test_mode = get_option( $this->test_mode_option ) === '1';
		
		// Fetch counts
		$total_products = wp_count_posts( 'products' )->publish;
		
		// Calculate missing/assigned (simplified query for UI)
		global $wpdb;
		$missing_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} p WHERE p.post_type = 'products' AND p.post_status = 'publish' AND NOT EXISTS (SELECT 1 FROM {$wpdb->postmeta} pm WHERE pm.post_id = p.ID AND pm.meta_key = 'iths_hero_image' AND pm.meta_value != '')");
		$assigned_count = $total_products - $missing_count;
		
		?>
		<div class="wrap iths-acquisition-wrap">
			<div class="iths-acquisition-header">
				<h1 class="wp-heading-inline">OEM Image Acquisition</h1>
			</div>

			<h2 class="nav-tab-wrapper">
				<a href="#tab-acquisition" class="nav-tab nav-tab-active">Acquisition</a>
				<a href="#tab-settings" class="nav-tab">Settings</a>
			</h2>

			<div id="tab-acquisition" class="iths-tab-content active">
				
				<?php if ( ! $api_key ) : ?>
					<div class="notice notice-error"><p>Please enter your Serper.dev API key in the Settings tab before searching.</p></div>
				<?php endif; ?>

				<?php if ( $is_test_mode ) : ?>
					<div class="iths-test-mode-notice">
						<p><strong>SAFE TEST MODE ACTIVE:</strong> Only 5 representative products are shown. Please verify the workflow on these before disabling test mode in Settings to process the full catalogue.</p>
					</div>
				<?php endif; ?>

				<div class="iths-summary-cards">
					<div class="iths-summary-card">
						<h4>Total Products</h4>
						<p class="count"><?php echo esc_html( $total_products ); ?></p>
					</div>
					<div class="iths-summary-card">
						<h4>With Image</h4>
						<p class="count" id="count-assigned"><?php echo esc_html( $assigned_count ); ?></p>
					</div>
					<div class="iths-summary-card">
						<h4>Missing</h4>
						<p class="count" id="count-missing"><?php echo esc_html( $missing_count ); ?></p>
					</div>
					<div class="iths-summary-card">
						<h4>Needs Review</h4>
						<p class="count">0</p>
					</div>
					<div class="iths-summary-card">
						<h4>Failed</h4>
						<p class="count">0</p>
					</div>
				</div>
				<div class="iths-bulk-actions" style="margin-bottom: 15px; display: flex; gap: 10px; align-items: center;">
					<button type="button" class="button" id="btn-bulk-search">Search All Missing</button>
					<button type="button" class="button button-primary" id="btn-bulk-approve">Approve All HIGH Confidence</button>
					<span id="bulk-status" style="font-weight: 600; color: #007cba;"></span>
				</div>

				<table class="iths-acquisition-table">
					<thead>
						<tr>
							<th>Product</th>
							<th>OEM PN</th>
							<th>Brand</th>
							<th>Type</th>
							<th>Status</th>
							<th>Image Candidate</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$products = $this->get_products_for_table( $is_test_mode );
						if ( empty( $products ) ) {
							echo '<tr><td colspan="7">No products found or all test products have images.</td></tr>';
						}
						foreach ( $products as $p ) {
							$oem_pn = get_post_meta( $p->ID, 'iths_oem_part_number', true );
							$hero_image = get_post_meta( $p->ID, 'iths_hero_image', true );
							$type = get_post_meta( $p->ID, 'iths_product_type', true );
							$brand_terms = wp_get_post_terms( $p->ID, 'product-brand', ['fields' => 'names'] );
							$brand = !empty($brand_terms) ? $brand_terms[0] : '';
							
							$status = $hero_image ? 'assigned' : 'missing';
							$status_label = $hero_image ? 'Assigned' : 'Missing';
							?>
							<tr data-product-id="<?php echo esc_attr( $p->ID ); ?>" data-oem-pn="<?php echo esc_attr( $oem_pn ); ?>" data-brand="<?php echo esc_attr( $brand ); ?>" data-type="<?php echo esc_attr( $type ); ?>">
								<td>
									<strong><?php echo esc_html( $p->post_title ); ?></strong><br>
									<small>ID: <?php echo esc_html( $p->ID ); ?></small>
								</td>
								<td><code><?php echo esc_html( $oem_pn ?: 'N/A' ); ?></code></td>
								<td><?php echo esc_html( $brand ); ?></td>
								<td><?php echo esc_html( $type ); ?></td>
								<td><span class="status-badge <?php echo esc_attr( $status ); ?>"><?php echo esc_html( $status_label ); ?></span></td>
								<td class="candidate-cell">
									<?php if ( $hero_image ) : ?>
										<img src="<?php echo esc_url( wp_upload_dir()['baseurl'] . '/products/' . $hero_image ); ?>" class="existing-hero-thumb" alt="Existing Image">
										<br><small><?php echo esc_html( $hero_image ); ?></small>
									<?php else : ?>
										<span style="color: #646970;">No candidate yet</span>
									<?php endif; ?>
								</td>
								<td class="action-cell">
									<?php if ( ! $hero_image && $oem_pn && $api_key ) : ?>
										<button type="button" class="button button-primary btn-search-image">Search</button>
									<?php elseif ( ! $hero_image && ! $oem_pn ) : ?>
										<span style="color:#d63638">No OEM PN</span>
									<?php elseif ( $hero_image ) : ?>
										✅ Done
									<?php endif; ?>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>

			<div id="tab-settings" class="iths-tab-content">
				<form method="post" action="options.php">
					<?php settings_fields( 'iths_acquisition_settings' ); ?>
					<table class="form-table">
						<tr>
							<th scope="row">Serper.dev API Key</th>
							<td>
								<input type="password" name="<?php echo esc_attr( $this->api_key_option ); ?>" value="<?php echo esc_attr( get_option( $this->api_key_option ) ); ?>" class="regular-text">
								<p class="description">Get a free key from <a href="https://serper.dev" target="_blank">serper.dev</a>. Gives 2,500 free searches.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Safe Test Mode</th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $this->test_mode_option ); ?>" value="1" <?php checked( get_option( $this->test_mode_option ), '1' ); ?>>
									Enable Test Mode (Limits acquisition to 5 specific representative products)
								</label>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
		</div>
		<?php
	}

	private function get_products_for_table( $is_test_mode ) {
		$args = [
			'post_type' => 'products',
			'post_status' => 'publish',
			'posts_per_page' => 104,
			'orderby' => 'ID',
			'order' => 'ASC'
		];

		if ( $is_test_mode ) {
			// Find 5 specific products: Dell HDD, HPE HDD, Memory, RAID, PSU
			global $wpdb;
			
			// This is a custom raw query to pick 5 representative products that lack a hero image
			$query = "
				SELECT p.ID
				FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'iths_product_type'
				LEFT JOIN {$wpdb->postmeta} pm_hero ON p.ID = pm_hero.post_id AND pm_hero.meta_key = 'iths_hero_image'
				WHERE p.post_type = 'products' 
				AND p.post_status = 'publish'
				AND (pm_hero.meta_value IS NULL OR pm_hero.meta_value = '')
				AND pm1.meta_value IN ('Hard Drive', 'SAS HDD', 'SSD', 'Memory', 'RAM', 'RAID Controller', 'Power Supply')
				LIMIT 20
			";
			
			$candidate_ids = $wpdb->get_col($query);
			if (empty($candidate_ids)) {
				return []; // All have images or none found
			}
			
			// We'll just grab the first 5 unique candidates to fulfill the test mode requirement simply
			$test_ids = array_slice($candidate_ids, 0, 5);
			
			if (empty($test_ids)) return [];
			
			$args['post__in'] = $test_ids;
			$args['posts_per_page'] = 5;
		}

		return get_posts( $args );
	}

	// AJAX: Search
	public function ajax_search() {
		check_ajax_referer( 'iths_image_acquisition_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}

		$post_id = intval( $_POST['product_id'] );
		$oem_pn = sanitize_text_field( $_POST['oem_pn'] );
		$brand = sanitize_text_field( $_POST['brand'] );
		$type = sanitize_text_field( $_POST['type'] );

		if ( ! $oem_pn ) {
			wp_send_json_error( 'No OEM Part Number provided.' );
		}

		$api_key = get_option( $this->api_key_option );
		if ( ! $api_key ) {
			wp_send_json_error( 'API Key missing.' );
		}

		// Cascading Search Logic - STRICTLY search only approved clean sites and official brand domains
		$clean_sites = 'site:tekeurope.co.uk OR site:techbuyer.com OR site:bytestock.com OR site:itinstock.com OR site:servershop24.de OR site:bargainhardware.co.uk';
		
		$brand_lower = strtolower( $brand );
		$brand_site = '';
		if ( $brand_lower === 'hpe' || $brand_lower === 'hp' ) $brand_site = 'site:hpe.com OR site:hp.com OR ';
		elseif ( $brand_lower === 'dell' ) $brand_site = 'site:dell.com OR ';
		elseif ( $brand_lower === 'cisco' ) $brand_site = 'site:cisco.com OR ';
		elseif ( $brand_lower === 'lenovo' ) $brand_site = 'site:lenovo.com OR ';
		elseif ( $brand_lower === 'ibm' ) $brand_site = 'site:ibm.com OR ';
		
		$sites_to_search = '(' . $brand_site . $clean_sites . ')';
		
		$negative_keywords = '-watermark -stock -alamy -getty -shutterstock -123rf -istock -diagram -schematic -drawing -exploded -label -sticker -box -manual';
		
		$product_title = get_the_title( $post_id );
		$clean_title = sanitize_text_field( str_replace( '"', '', $product_title ) );
		
		// 1. First attempt: Strict exact match for OEM PN on the specific safe sites
		$queries[] = "\"{$oem_pn}\" {$sites_to_search} {$negative_keywords}";
		
		// 2. Second attempt: Broad web search using OEM PN, Brand, Type, and Full Part Name to guarantee identical specs
		$banned_sites = '-site:amazon.com -site:ebay.com -site:aliexpress.com -site:ebay.co.uk -site:newegg.com -site:walmart.com -site:tonitrus.com';
		$queries[] = "\"{$oem_pn}\" {$brand} \"{$type}\" {$clean_title} {$negative_keywords} {$banned_sites}";
		
		// 3. Third attempt: Broad web search using OEM PN and Full Part Name
		$queries[] = "\"{$oem_pn}\" {$clean_title} {$negative_keywords} {$banned_sites}";

		$best_candidate = null;

		foreach ( $queries as $q ) {
			$results = $this->serper_search( $q, $api_key );
			if ( ! empty( $results['images'] ) ) {
				foreach ( $results['images'] as $img ) {
					$confidence = $this->calculate_confidence( $img, $oem_pn, $brand, $type, $product_title );
					if ( $confidence === 'REJECTED' ) {
						continue; // Skip watermarked/stock
					}
					if ( $confidence === 'HIGH' ) {
						$best_candidate = $img;
						$best_candidate['confidence'] = $confidence;
						break 2; // Found the perfect candidate, stop cascading
					}
					
					// Keep track of best candidate if we don't have one, or if we find a better one
					if ( ! $best_candidate ) {
						$best_candidate = $img;
						$best_candidate['confidence'] = $confidence;
					} elseif ( $confidence === 'MEDIUM' && $best_candidate['confidence'] === 'LOW' ) {
						$best_candidate = $img;
						$best_candidate['confidence'] = $confidence;
					}
				}
			}
		}

		if ( ! $best_candidate ) {
			wp_send_json_error( 'No valid image candidates found.' );
		}

		// Build HTML response
		ob_start();
		$conf = $best_candidate['confidence'];
		$domain = parse_url( $best_candidate['link'], PHP_URL_HOST );
		?>
		<div class="candidate-wrapper">
			<img src="<?php echo esc_url( $best_candidate['imageUrl'] ); ?>" class="candidate-img" alt="Candidate">
			<div class="candidate-info">
				<p><strong>Source:</strong> <?php echo esc_html( $best_candidate['title'] ); ?></p>
				<p><strong>Domain:</strong> <?php echo esc_html( $domain ); ?></p>
				<span class="candidate-confidence <?php echo strtolower( $conf ); ?>"><?php echo esc_html( $conf ); ?> CONFIDENCE</span>
			</div>
		</div>
		<?php
		$candidate_html = ob_get_clean();

		ob_start();
		$disabled = ( $conf === 'LOW' ) ? 'disabled' : '';
		?>
		<div class="action-buttons">
			<a href="<?php echo esc_url( $best_candidate['link'] ); ?>" target="_blank" class="button button-secondary">Open Source ↗</a>
			<button type="button" class="button button-primary btn-approve-image" <?php echo $disabled; ?> 
				data-img-url="<?php echo esc_attr( $best_candidate['imageUrl'] ); ?>"
				data-source-url="<?php echo esc_attr( $best_candidate['link'] ); ?>"
				data-source-domain="<?php echo esc_attr( $domain ); ?>"
				data-confidence="<?php echo esc_attr( $conf ); ?>">Approve</button>
			<button type="button" class="button button-link-delete btn-reject-image">Reject</button>
		</div>
		<?php
		$action_html = ob_get_clean();

		wp_send_json_success( [
			'candidate_html' => $candidate_html,
			'action_html'    => $action_html
		] );
	}

	private function serper_search( $query, $api_key ) {
		$response = wp_remote_post( 'https://google.serper.dev/images', [
			'headers' => [
				'X-API-KEY'    => $api_key,
				'Content-Type' => 'application/json'
			],
			'body' => wp_json_encode( [
				'q' => $query
			] ),
			'timeout' => 15
		] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		return json_decode( $body, true );
	}

	public function calculate_confidence( $img, $oem_pn, $brand, $type, $product_title = '' ) {
		$title = $img['title'];
		$domain = strtolower( parse_url( $img['link'], PHP_URL_HOST ) ?? '' );
		$img_url_lower = strtolower( $img['imageUrl'] );
		
		// Strip non-alphanumeric chars for robust matching
		$oem_clean = preg_replace('/[^a-z0-9]/', '', strtolower($oem_pn));
		$title_clean = preg_replace('/[^a-z0-9]/', '', $title);
		$url_clean = preg_replace('/[^a-z0-9]/', '', $img_url_lower . strtolower($img['link']));

		// Explicitly reject known watermarked/stock sources (text and domains)
		$watermark_terms = ['watermark', 'stock', 'alamy', 'getty', 'shutterstock', '123rf', 'istock', 'dreamstime', 'harddrivesdirect', 'serverdiskdrives', 'itcreations', 'serversupply', 'vibrant', 'stikc', 'express', 'tonitrus'];
		foreach ( $watermark_terms as $term ) {
			if ( strpos( $title_clean, $term ) !== false || strpos( $domain, $term ) !== false || strpos( $img_url_lower, $term ) !== false ) {
				return 'REJECTED';
			}
		}

		// Reject logos, avatars, community forum pages, and low-res OEM portals
		$logo_terms = ['logo', 'icon', 'avatar', 'profile', 'community', 'forum', 'badge', 'partsurfer'];
		foreach ( $logo_terms as $term ) {
			if ( strpos( $img_url_lower, $term ) !== false || strpos( $url_clean, $term ) !== false ) {
				return 'REJECTED';
			}
		}

		// Reject diagrams, schematics, and stickers/labels/placeholders
		$bad_image_terms = ['diagram', 'schematic', 'drawing', 'exploded', 'label', 'sticker', 'manual', 'quickspecs', 'box', 'placeholder', 'no-image', 'kein-bild', 'coming-soon', 'not-available', 'no_image'];
		foreach ( $bad_image_terms as $term ) {
			if ( strpos( $title_clean, $term ) !== false || strpos( $url_clean, $term ) !== false ) {
				return 'REJECTED';
			}
		}

		// Prevent Category Mix-and-Match (e.g. Motherboard for a Power Supply)
		$type_lower = strtolower($type);
		$required_keywords = [];
		if (strpos($type_lower, 'power') !== false || strpos($type_lower, 'psu') !== false) {
			$required_keywords = ['power', 'supply', 'psu', 'watt'];
		} elseif (strpos($type_lower, 'hdd') !== false || strpos($type_lower, 'hard drive') !== false || strpos($type_lower, 'disk') !== false) {
			$required_keywords = ['hdd', 'hard drive', 'drive', 'disk', 'sas', 'sata', 'ssd'];
		} elseif (strpos($type_lower, 'ssd') !== false || strpos($type_lower, 'solid state') !== false) {
			$required_keywords = ['ssd', 'solid state', 'drive', 'sas', 'sata', 'nvme', 'flash'];
		} elseif (strpos($type_lower, 'motherboard') !== false || strpos($type_lower, 'board') !== false) {
			$required_keywords = ['motherboard', 'board', 'mainboard', 'system'];
		} elseif (strpos($type_lower, 'ram') !== false || strpos($type_lower, 'memory') !== false) {
			$required_keywords = ['ram', 'memory', 'dimm', 'ddr'];
		} elseif (strpos($type_lower, 'processor') !== false || strpos($type_lower, 'cpu') !== false) {
			$required_keywords = ['processor', 'cpu', 'intel', 'amd', 'xeon', 'epyc'];
		} elseif (strpos($type_lower, 'switch') !== false || strpos($type_lower, 'module') !== false || strpos($type_lower, 'card') !== false) {
			$required_keywords = ['switch', 'module', 'card', 'port', 'transceiver'];
		}

		if (!empty($required_keywords)) {
			$found_keyword = false;
			foreach ($required_keywords as $kw) {
				if (strpos($title_clean, preg_replace('/[^a-z0-9]/', '', $kw)) !== false) {
					$found_keyword = true;
					break;
				}
			}
			if (!$found_keyword) {
				return 'REJECTED';
			}
		}

		// Prevent Part Spec Mix-and-Match by extracting specs (e.g. 146GB, 10K, 2.5) from Product Title
		if (!empty($product_title)) {
			preg_match_all('/[0-9]+[a-zA-Z]+|[0-9]+\.[0-9]+/', $product_title, $spec_matches);
			if (!empty($spec_matches[0])) {
				$specs_found = 0;
				$specs_to_check = array_unique(array_map('strtolower', $spec_matches[0]));
				foreach ($specs_to_check as $spec) {
					if (strpos($title_clean, preg_replace('/[^a-z0-9]/', '', $spec)) !== false || strpos($url_clean, preg_replace('/[^a-z0-9]/', '', $spec)) !== false) {
						$specs_found++;
					}
				}
				// If the product title has key specs, the image source MUST contain at least one of them
				if (count($specs_to_check) > 0 && $specs_found === 0) {
					return 'REJECTED';
				}
			}
		}

		// Reject 3rd party compatible/alternative brands
		$third_party_terms = ['axiom', 'compatible', 'replacement', 'equivalent', 'generic', 'refurbished', 'refurb'];
		foreach ( $third_party_terms as $term ) {
			if ( strpos( $title, $term ) !== false ) {
				return 'REJECTED';
			}
		}

		// Check if OEM PN exists anywhere
		$has_oem = (strpos( $title_clean, $oem_clean ) !== false) || (strpos( $url_clean, $oem_clean ) !== false);
		$has_brand = strpos( $title, strtolower( $brand ) ) !== false || strpos( $domain, strtolower( $brand ) ) !== false;
		
		// Ultra-safe domains that NEVER watermark
		$ultra_safe_domains = ['hpe.com', 'hp.com', 'dell.com', 'cisco.com', 'lenovo.com', 'ibm.com', 'cdw.com', 'insight.com', 'pcconnection.com', 'shi.com', 'zones.com', 'bhphotovideo.com', 'tekeurope.co.uk', 'techbuyer.com', 'bytestock.com', 'itinstock.com', 'servershop24.de', 'bargainhardware.co.uk'];
		$is_ultra_safe = false;
		foreach ( $ultra_safe_domains as $td ) {
			if ( strpos( $domain, $td ) !== false ) {
				$is_ultra_safe = true;
				break;
			}
		}
		
		// Risky domains (marketplaces) that often have watermarks but aren't strictly rejected by name
		$risky_domains = ['amazon.com', 'ebay.com', 'aliexpress.com', 'newegg.com'];
		$is_risky = false;
		foreach ( $risky_domains as $td ) {
			if ( strpos( $domain, $td ) !== false ) {
				$is_risky = true;
				break;
			}
		}

		if ( $has_oem && $has_brand && $is_ultra_safe ) {
			return 'HIGH';
		} elseif ( $has_oem && !$is_risky ) {
			// If we have the OEM PN and it's not a risky domain, give it a MEDIUM so user can check it
			return 'MEDIUM';
		} elseif ( $has_oem || $has_brand ) {
			// If it's risky (Amazon/eBay) or only has partial info
			return 'LOW';
		}

		return 'REJECTED';
	}

	// AJAX: Approve & Download
	public function ajax_approve() {
		check_ajax_referer( 'iths_image_acquisition_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}

		$post_id = intval( $_POST['product_id'] );
		$oem_pn = sanitize_text_field( $_POST['oem_pn'] );
		$img_url = esc_url_raw( $_POST['img_url'] );
		
		// Check existing
		$existing = get_post_meta( $post_id, 'iths_hero_image', true );
		if ( $existing ) {
			wp_send_json_error( 'Hero image already exists.' );
		}

		// Download and process
		$result = $this->download_and_process_image( $img_url, $oem_pn, $post_id );
		
		if ( is_wp_error( $result ) ) {
			update_post_meta( $post_id, 'iths_image_status', 'failed' );
			wp_send_json_error( $result->get_error_message() );
		}

		// Save metadata
		update_post_meta( $post_id, 'iths_hero_image', $result['filename'] );
		update_post_meta( $post_id, 'iths_image_status', 'assigned' );
		update_post_meta( $post_id, 'iths_image_source_url', sanitize_text_field( $_POST['source_url'] ) );
		update_post_meta( $post_id, 'iths_image_source_domain', sanitize_text_field( $_POST['source_domain'] ) );
		update_post_meta( $post_id, 'iths_image_confidence', sanitize_text_field( $_POST['confidence'] ) );
		update_post_meta( $post_id, 'iths_image_verified_at', current_time('mysql') );
		update_post_meta( $post_id, 'iths_image_verified_by', get_current_user_id() );

		wp_send_json_success( [
			'filename' => $result['filename'],
			'url'      => wp_upload_dir()['baseurl'] . '/products/' . $result['filename']
		] );
	}

	private function download_and_process_image( $url, $oem_pn, $post_id ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		$tmp_file = download_url( $url );
		if ( is_wp_error( $tmp_file ) ) {
			return $tmp_file;
		}

		$mime = mime_content_type( $tmp_file );
		if ( ! in_array( $mime, [ 'image/jpeg', 'image/png', 'image/webp' ] ) ) {
			@unlink( $tmp_file );
			return new WP_Error( 'invalid_mime', 'Invalid image MIME type: ' . $mime );
		}

		$editor = wp_get_image_editor( $tmp_file );
		if ( is_wp_error( $editor ) ) {
			@unlink( $tmp_file );
			return $editor;
		}

		// Resize to max 1600px wide
		$size = $editor->get_size();
		if ( $size['width'] > 1600 ) {
			$editor->resize( 1600, null, false );
		}

		// Set up upload dir
		$upload_dir = wp_upload_dir();
		$products_dir = $upload_dir['basedir'] . '/products';
		if ( ! file_exists( $products_dir ) ) {
			wp_mkdir_p( $products_dir );
		}

		// Sanitize filename
		$clean_oem = sanitize_file_name( $oem_pn );
		$filename = $clean_oem . '.webp';
		$dest_path = $products_dir . '/' . $filename;

		// Handle duplicate filenames by appending counter if needed
		$counter = 1;
		$orig_filename = $clean_oem;
		while ( file_exists( $dest_path ) ) {
			$filename = $orig_filename . '-' . $counter . '.webp';
			$dest_path = $products_dir . '/' . $filename;
			$counter++;
		}

		// Save as WebP
		$saved = $editor->save( $dest_path, 'image/webp' );
		@unlink( $tmp_file );

		if ( is_wp_error( $saved ) ) {
			return $saved;
		}

		// Create attachment
		$brand_terms = wp_get_post_terms( $post_id, 'product-brand', ['fields' => 'names'] );
		$brand = !empty($brand_terms) ? $brand_terms[0] : '';
		$type = get_post_meta( $post_id, 'iths_product_type', true );
		
		$alt_text = trim("{$brand} {$type} {$oem_pn}");
		
		$attachment = [
			'guid'           => $upload_dir['baseurl'] . '/products/' . $filename,
			'post_mime_type' => 'image/webp',
			'post_title'     => sanitize_text_field( $alt_text ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		];
		
		$attach_id = wp_insert_attachment( $attachment, $dest_path, $post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $dest_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		update_post_meta( $attach_id, '_wp_attachment_image_alt', sanitize_text_field( $alt_text ) );
		set_post_thumbnail( $post_id, $attach_id );

		return [ 'filename' => $filename ];
	}

	// AJAX: Reject / Skip
	public function ajax_reject() {
		check_ajax_referer( 'iths_image_acquisition_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		
		$post_id = intval( $_POST['product_id'] );
		update_post_meta( $post_id, 'iths_image_status', 'needs_review' );
		
		wp_send_json_success();
	}
}

new ITHS_Image_Acquisition();
