<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_add_admin_menu_page' ) ) {
	/**
	 * Add admin menu page
	 */
	function extensive_vc_add_admin_menu_page() {
		
		add_menu_page(
			esc_html__( 'Extensive VC Addons', 'extensive-vc' ),
			esc_html__( 'Extensive VC', 'extensive-vc' ),
			'edit_posts',
			'evc-admin-menu-page',
			'extensive_vc_admin_about_page_content',
			'dashicons-schedule',
			'85'
		);
	}
	
	add_action( 'admin_menu', 'extensive_vc_add_admin_menu_page' );
}

if ( ! function_exists( 'extensive_vc_admin_about_page_redirect' ) ) {
	/**
	 * Redirect to admin about page on plugin activation
	 */
	function extensive_vc_admin_about_page_redirect() {
		
		// If no activation redirect, bail
		if ( ! get_transient( '_extensive_vc_admin_about_page_redirect' ) ) {
			return;
		}
		
		// Delete the redirect transient
		delete_transient( '_extensive_vc_admin_about_page_redirect' );
		
		// If activating from network, or bulk, bail
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		
		// Redirect to welcome page
		wp_safe_redirect( add_query_arg( array( 'page' => 'evc-admin-menu-page' ), esc_url( admin_url( 'index.php' ) ) ) );
		exit;
	}
	
	add_action( 'admin_init', 'extensive_vc_admin_about_page_redirect' );
}

if ( ! function_exists( 'extensive_vc_add_admin_menu_settings_page' ) ) {
	/**
	 * Add admin options page
	 *
	 * @see add_submenu_page function
	 */
	function extensive_vc_add_admin_menu_settings_page() {
		
		add_submenu_page(
			'evc-admin-menu-page',
			esc_html__( 'Settings', 'extensive-vc' ),
			esc_html__( 'Settings', 'extensive-vc' ),
			'manage_options',
			'evc-admin-options-page',
			'extensive_vc_render_admin_menu_settings_page'
		);
	}
	
	add_action( 'admin_menu', 'extensive_vc_add_admin_menu_settings_page', 11 );
}

if ( ! function_exists( 'extensive_vc_render_admin_menu_settings_page' ) ) {
	/**
	 * Renders admin options
	 */
	function extensive_vc_render_admin_menu_settings_page() { ?>
		<div class="wrap evc-admin-settings">
			<h2><?php esc_attr_e( 'Extensive VC Settings', 'extensive-vc' ); ?></h2>
			<?php settings_errors(); ?>
			<h2 class="nav-tab-wrapper">
				<?php
					$tabs = array(
						'general' => array(
							'title' => esc_html__( 'General', 'extensive-vc' ),
							'url'   => 'evc-admin-options-page'
						)
					);
					foreach ( $tabs as $tab ) {
						$tab_url = 'admin.php?page=' . esc_attr( $tab['url'] );
						$url     = esc_attr( is_network_admin() ? network_admin_url( $tab_url ) : admin_url( $tab_url ) )
						?>
						<a class="nav-tab nav-tab-active" href="<?php echo esc_url( $url ); ?>">
							<?php echo esc_html( $tab['title'] ); ?>
						</a>
						<?php
					}
				?>
			</h2>
			<form action="options.php" method="post">
				<p class="evc-donate-link">
					<?php esc_html_e( 'If you enjoy using Extensive VC plugin and find it useful, please consider making a donation. Your donation will help encourage and support the plugin\'s continued development and better user support. Thank you, WP Realize team.', 'extensive-vc' ); ?>
					<a href="https://www.paypal.me/NenadObradovic" target="_blank"><?php esc_html_e( 'Donate', 'extensive-vc' ); ?></a>
				</p>
				<?php
					settings_fields( 'evc_options_group' );
					do_settings_sections( 'evc_options_page' );
					
					submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

if ( ! function_exists( 'extensive_vc_add_admin_menu_about_page' ) ) {
	/**
	 * Add admin menu about page
	 *
	 *  @see add_submenu_page function
	 */
	function extensive_vc_add_admin_menu_about_page() {
		
		add_submenu_page(
			'evc-admin-menu-page',
			esc_html__( 'About', 'extensive-vc' ),
			esc_html__( 'About', 'extensive-vc' ),
			'edit_posts',
			'evc-admin-about-page',
			'extensive_vc_admin_about_page_content'
		);
		
		// Remove duplicated admin menu page from submenu we want to have only about page
		remove_submenu_page( 'evc-admin-menu-page', 'evc-admin-menu-page' );
	}
	
	add_action( 'admin_menu', 'extensive_vc_add_admin_menu_about_page', 12 );
}

if ( ! function_exists( 'extensive_vc_admin_about_page_content' ) ) {
	/**
	 * Print admin about page content
	 */
	function extensive_vc_admin_about_page_content() {
		?>
		<div class="wrap about-wrap evc-admin-about">
			<div class="evc-admin-about-content">
				<h1><?php echo sprintf( esc_html__( 'Welcome to Extensive VC Addons %s', 'extensive-vc' ), EXTENSIVE_VC_VERSION ); ?></h1>
				<img class="evc-admin-logo" src="<?php echo EXTENSIVE_VC_ASSETS_URL_PATH . '/img/extensive_vc_admin_logo.jpg'; ?>" alt="<?php esc_html_e( 'Extensive VC Admin Logo', 'extensive-vc' ); ?>" />
				<div class="about-text">
					<?php esc_html_e( 'Thank you for installing Extensive VC Addons! Extensive VC is most powerful plugin addons for WPBakery page builder formerly Visual Composer.', 'extensive-vc' ); ?>
				</div>
				<img class="evc-admin-image" src="<?php echo EXTENSIVE_VC_ASSETS_URL_PATH . '/img/extensive_vc_admin_image.jpg'; ?>" alt="<?php esc_html_e( 'Extensive VC Admin Image', 'extensive-vc' ); ?>" />
				<p class="evc-donate-link">
					<?php esc_html_e( 'If you enjoy using Extensive VC plugin and find it useful, please consider making a donation. Your donation will help encourage and support the plugin\'s continued development and better user support. Thank you, WP Realize team.', 'extensive-vc' ); ?>
					<a href="https://www.paypal.me/NenadObradovic" target="_blank"><?php esc_html_e( 'Donate', 'extensive-vc' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'extensive_vc_add_admin_bar_menu_options' ) ) {
	/**
	 * Add admin menu link into WordPress toolbar
	 *
	 * @param $wp_admin_bar object
	 */
	function extensive_vc_add_admin_bar_menu_options( $wp_admin_bar ) {
		if ( ! is_object( $wp_admin_bar ) ) {
			global $wp_admin_bar;
		}
		
		$args = array(
			'id'    => 'evc-admin-bar-options-page',
			'title' => sprintf( '<span class="ab-icon dashicons-before dashicons-schedule"></span> %s', esc_html__( 'Extensive VC', 'extensive-vc' ) ),
			'href'  => esc_url( admin_url('admin.php?page=evc-admin-options-page') )
		);
		
		$wp_admin_bar->add_node( $args );
	}
	
	if ( ! is_admin() ) {
		add_action( 'admin_bar_menu', 'extensive_vc_add_admin_bar_menu_options', 100 );
	}
}

if ( ! function_exists( 'extensive_vc_is_forwarded_admin_page_active' ) ) {
	/**
	 * Checks if forwarded page in active
	 *
	 * @param $page string
	 *
	 * @return boolean
	 */
	function extensive_vc_is_forwarded_admin_page_active( $page ) {
		$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : '';
		
		return ! empty( $current_screen ) ? $current_screen->post_type === $page : false;
	}
}
