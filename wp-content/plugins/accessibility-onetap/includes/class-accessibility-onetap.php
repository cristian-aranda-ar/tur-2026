<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wponetap.com
 * @since      1.0.0
 *
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include the plugin.php file to access the is_plugin_active() function.
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/includes
 * @author     OneTap <support@wponetap.com>
 */
class Accessibility_Onetap {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Accessibility_Onetap_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ACCESSIBILITY_ONETAP_VERSION' ) ) {
			$this->version = ACCESSIBILITY_ONETAP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'accessibility-onetap';

		// Ensure install timestamp exists for review banner logic.
		// For existing users (first load after update), set timestamp to now
		// so they see the banner after 14 days, same as new users after install.
		if ( ! get_option( 'onetap_install_timestamp', false ) ) {
			update_option( 'onetap_install_timestamp', time() );
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_alt_text_admin_hooks();
		$this->define_settings_options();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Accessibility_Onetap_Loader. Orchestrates the hooks of the plugin.
	 * - Accessibility_Onetap_I18n. Defines internationalization functionality.
	 * - Accessibility_Onetap_Admin. Defines all hooks for the admin area.
	 * - Accessibility_Onetap_Settings_Options.
	 * - Accessibility_Onetap_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-accessibility-onetap-loader.php';

		/**
		 * Load the helper functions for template handling (e.g., accessibility_onetap_load_template()).
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/helpers-template.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-accessibility-onetap-i18n.php';

		/**
		 * This allows access to configuration settings.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'inc/class-accessibility-onetap-config.php';

		/**
		 * The class responsible for call Settings Manager.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-accessibility-onetap-settings-manager.php';

		/**
		 * The class responsible for call Settings Option.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-accessibility-onetap-settings-options.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-accessibility-onetap-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area alt text.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-onetap-free-alt-text.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-accessibility-onetap-public.php';

		$this->loader = new Accessibility_Onetap_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Note: As of WordPress 4.6, translations are automatically loaded
	 * when the Text Domain header is set in the main plugin file.
	 * No manual hook registration is needed.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		// Translations are automatically loaded by WordPress 4.6+
		// when Text Domain is set in the plugin header.
		// No action needed.
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Accessibility_Onetap_Admin( $this->get_plugin_name(), $this->get_version() );

		if ( ! is_plugin_active( 'accessibility-plugin-onetap-pro/accessibility-plugin-onetap-pro.php' ) ) {

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_action( 'allowed_redirect_hosts', $plugin_admin, 'onetap_allow_external_redirect_host' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'onetap_redirect_admin_page_to_pricing', 999999 );
			$this->loader->add_action( 'wp_ajax_onetap_action_dismiss_notice', $plugin_admin, 'dismiss_notice_ajax_callback' );
			$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'add_row_meta', 10, 2 );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings_for_accessibility_status' );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_review_banner' );
			$this->loader->add_action( 'wp_ajax_onetap_review_leave_review', $plugin_admin, 'ajax_handle_leave_review' );
			$this->loader->add_action( 'wp_ajax_onetap_review_maybe_later', $plugin_admin, 'ajax_handle_maybe_later' );
			$this->loader->add_action( 'wp_ajax_onetap_review_dont_show_again', $plugin_admin, 'ajax_handle_dont_show_again' );

		}
	}

	/**
	 * Register all hooks related to alt text management functionality in the admin area.
	 *
	 * Sets up AJAX handlers for managing image alt text, including saving and updating
	 * alt text for images to improve accessibility compliance.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_alt_text_admin_hooks() {

		// Initialize the alt text management class with plugin details.
		$plugin_admin = new Onetap_Free_Alt_Text( $this->get_plugin_name(), $this->get_version() );

		// Register AJAX action for saving alt text.
		$this->loader->add_action( 'wp_ajax_onetap_save_alt_text', $plugin_admin, 'handle_ajax_save_alt_text' );
	}

	/**
	 * Register all of setting options.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_settings_options() {

		$plugin_admin = new Accessibility_Onetap_Settings_Options( $this->get_plugin_name(), $this->get_version(), new Accessibility_Onetap_Settings_Manager() );

		if ( ! is_plugin_active( 'accessibility-plugin-onetap-pro/accessibility-plugin-onetap-pro.php' ) ) {

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_admin_menu_page' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'hide_notifications_for_onetap_page', 99999 );

		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Accessibility_Onetap_Public( $this->get_plugin_name(), $this->get_version() );

		if ( ! is_plugin_active( 'accessibility-plugin-onetap-pro/accessibility-plugin-onetap-pro.php' ) ) {

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'wp_footer', $plugin_public, 'render_accessibility_template' );
			$this->loader->add_filter( 'body_class', $plugin_public, 'add_custom_body_class' );
			$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

			// Exclude CSS and JS from WP Rocket optimization.
			$this->loader->add_filter( 'rocket_exclude_css', $plugin_public, 'exclude_css_from_wp_rocket' );
			$this->loader->add_filter( 'rocket_exclude_js', $plugin_public, 'exclude_js_from_wp_rocket' );
			$this->loader->add_filter( 'rocket_exclude_defer_js', $plugin_public, 'exclude_js_from_wp_rocket_defer' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Accessibility_Onetap_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
