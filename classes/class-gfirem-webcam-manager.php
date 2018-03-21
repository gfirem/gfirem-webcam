<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GFireMWebcamManager {
	
	public function __construct() {
		
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'fs_is_submenu_visible_gfirem-webcam-field', array( $this, 'handle_sub_menu' ), 10, 2 );
		
		require_once 'class-gfirem-webcam-logs.php';
		new GFireMWebcamLogs();
		
		try {
			//Check formidable pro
			if ( class_exists( 'FrmAppHelper' ) && method_exists( 'FrmAppHelper', 'pro_is_installed' ) && FrmAppHelper::pro_is_installed() ) {
				if ( GFireM_Webcam::getFreemius()->is_paying() ) {
                    require_once 'class-gfirem-webcam-admin.php';
                    new GFireMWebcamAdmin();
					require_once 'class-gfirem-fieldbase.php';
					require_once 'class-gfirem-webcam-field.php';
					new GFireMWebcamField();
				}
			} else {
				add_action( 'admin_notices', array( $this, 'required_formidable_pro' ) );
			}
		} catch ( Exception $ex ) {
			GFireMWebcamLogs::log( array(
				'action'         => 'loading_dependency',
				'object_type'    => GFireM_Webcam::getSlug(),
				'object_subtype' => get_class( $this ),
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public function required_formidable_pro() {
		require GFireM_Webcam::$view . 'formidable_notice.php';
	}
	
	/**
	 * Handle freemius menus visibility
	 *
	 * @param $is_visible
	 * @param $menu_id
	 *
	 * @return bool
	 */
	public function handle_sub_menu( $is_visible, $menu_id ) {
		if ( $menu_id == 'account' ) {
			$is_visible = false;
		}
		
		return $is_visible;
	}
	
	/**
	 * Adding the Admin Page
	 */
	public function admin_menu() {
		add_menu_page( __( "Webcam", "gfirem-webcam-field-locale" ), __( "Webcam", "gfirem-webcam-field-locale" ), 'manage_options', GFireM_Webcam::getSlug(), array( $this, 'screen' ), 'dashicons-camera' );
	}
	
	/**
	 * Screen to admin page
	 */
	public function screen() {
		GFireM_Webcam::getFreemius()->get_logger()->entrance();
		GFireM_Webcam::getFreemius()->_account_page_load();
		GFireM_Webcam::getFreemius()->_account_page_render();
	}
}
