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
		
		// Backend Notice
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		
		// Frontend Notice
		add_action( 'wp_head', array( $this, 'frontend_notice' ) );
		
		// Settings Page for defining notice
		add_action( 'admin_init', array( $this, 'settings_page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
	
	}
	
	/**
	 * Notice displayed at top of all admin pages
	 *
	 */
	public function admin_notice() {
		echo '<div class="error"><p>This site has been migrated.</p></div>';
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
			
		echo '<div class="error"><p>This site has been migrated.</p></div>';
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
		add_options_page( 'Migration Notice', 'Migration Notice', 'manage_options', 'migration_notice', array( $this, 'settings_page' ) );
	}
	
	/**
	 * Build Settings Page 
	 *
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<h2>Migration Notice</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'migration_notice_options' ); ?>
				<?php $options = get_option( 'migration_notice' ); ?>
				<table class="form-table">
					<tr valign="top"><th scope="row">Frontend Notice</th>
						<td><?php wp_editor( $options['frontend'], 'migration_notice[frontend]' );?></td>
					</tr>
					<tr valign="top"><th scope="row">Backend Notice</th>
						<td><?php wp_editor( $options['backend'], 'migration_notice[backend]' );?></td>
					</tr>
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
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