<?php
/*
Plugin Name: Migration Notice
Plugin URI: http://www.github.com/billerickson/migration-notice
Description: Lets users know this site has been migrated elsewhere
Version: 1.0
Author: Bill Erickson
Author URI: http://www.billerickson.net
License: GPLv2
*/



class BE_Migration_Notice {

	var $instance;
	
	public function __construct() {
		$this->instance =& $this;
		add_action( 'init', array( $this, 'init' ) );	
	}
	
	public function init() {
	
		// Translations
		load_plugin_textdomain( 'migration-notice', false, basename( dirname( __FILE__ ) ) . '/lib/languages' );
		
		// Backend Notice
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		
		// Frontend Notice
		add_action( 'wp_head', array( $this, 'frontend_notice' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_notice_style' ) );
		
		// Settings Page for defining notice
		add_action( 'admin_init', array( $this, 'settings_page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
	
	}
	
	/**
	 * Default Notices
	 *
	 */
	public function default_notices() {
		return array(
			'frontend' => __( 'This site has been migrated.', 'migration-notice' ),
			'backend'  => __( 'This site has been migrated.', 'migration-notice' ),
		);
	}
	
	/**
	 * Notice displayed at top of all admin pages
	 * @link http://wptheming.com/2011/08/admin-notices-in-wordpress/
	 */
	public function admin_notice() {
		$notices = get_option( 'migration_notice', $this->default_notices() );
		if( isset( $notices['backend'] ) && !empty( $notices['backend'] ) )
			echo '<div class="error">' . wpautop( $notices['backend'] ) . '</div>';
	}
	
	/**
	 * Notice displayed at top of all frontend pages
	 *
	 * Use the 'migration_notice_hide_frontend' filter to disable, 
	 * in case you want to use a different hook.
	 */
	public function frontend_notice() {
		if( apply_filters( 'migration_notice_hide_frontend', false ) )
			return;
		
		$notices = get_option( 'migration_notice', $this->default_notices() );
		if( isset( $notices['frontend'] ) && !empty( $notices['frontend'] ) )	
			echo '<div class="migration-notice">' . wpautop( $notices['frontend'] ) . '</div>';
	}
	
	/**
	 * Enqueue CSS for frontend notice
	 *
	 * Use the 'migration_notice_disable_css' filter to disable,
	 * in case you styled it in your theme.
	 */
	public function frontend_notice_style() {
		if( apply_filters( 'migration_notice_disable_css', false ) )
			return;
			
		wp_enqueue_style( 'migration-notice', plugins_url( 'lib/css/migration-notice.css', __FILE__ ) );
	}
	
	/**
	 * Initialize plugin options
	 * @link http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	 *
	 */
	public function settings_page_init() {
		register_setting( 'migration_notice_options', 'migration_notice', array( $this, 'migration_notice_validate' ) );
	}
	
	/**
	 * Add Settings Page
	 *
	 */
	public function add_settings_page() {
		add_options_page( __( 'Migration Notice', 'migration-notice' ), __( 'Migration Notice', 'migration-notice' ), 'manage_options', 'migration_notice', array( $this, 'settings_page' ) );
	}
	
	/**
	 * Build Settings Page 
	 *
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Migration Notice', 'migration-notice' );?></h2>
			<form method="post" action="options.php">
				<?php 
				settings_fields( 'migration_notice_options' );
				$notices = get_option( 'migration_notice', $this->default_notices() ); 
				?>
				<table class="form-table">
					<tr valign="top"><th scope="row"><?php _e( 'Frontend Notice', 'migration-notice' );?></th>
						<td><?php wp_editor( $notices['frontend'], 'migration_notice[frontend]' );?></td>
					</tr>
					<tr valign="top"><th scope="row"><?php _e( 'Backend Notice', 'migration-notice' );?></th>
						<td><?php wp_editor( $notices['backend'], 'migration_notice[backend]' );?></td>
					</tr>
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'migration-notice' ); ?>" />
				</p>
			</form>
		</div>
		<?php	
	
	}
	
	/** 
	 * Validate settings
	 *
	 */
	function migration_notice_validate( $input ) {
		$input['frontend'] = wp_kses_post( $input['frontend'] );
		$input['backend'] = wp_kses_post( $input['backend'] );
		return $input;
	}
}

global $be_migration_notice;
$be_migration_notice = new BE_Migration_Notice;