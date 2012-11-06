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
	
	}
	
	public function admin_notice() {
		echo '<div class="error"><p>This site has been migrated.</p></div>';
	}
	
}

global $be_migration_notice;
$be_migration_notice = new BE_Image_Override;