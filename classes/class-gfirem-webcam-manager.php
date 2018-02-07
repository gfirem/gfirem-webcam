<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GFiremWebcamManager {

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'fs_is_submenu_visible_gfirem-webcam', array( $this, 'handle_sub_menu' ), 10, 2 );

        require_once 'class-gfirem-webcam-logs.php';
        new GFiremWebcamLogs();

        try {
            //Check formidable pro
            if ( self::is_formidable_active() ) {
                if ( GFireM_Webcam::getFreemius()->is_paying() ) {

                    require_once 'class-gfirem-fieldbase.php';
                    require_once 'class-webcam_field.php';
                    new GFireMWebcamField();
                }
            }
        } catch ( Exception $ex ) {
            GFiremWebcamLogs::log( array(
                'action'         => get_class( $this ),
                'object_type'    => GFireM_Webcam_Fs::getSlug(),
                'object_subtype' => 'loading_dependency',
                'object_name'    => $ex->getMessage(),
            ) );
        }
    }

    public static function load_plugins_dependency() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    public static function is_formidable_active() {
        self::load_plugins_dependency();

        return is_plugin_active( 'formidable/formidable.php' );
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
        add_menu_page( __( "WebcamField", "gfirem_webcam-locale" ), __( "WebcamField", "gfirem_webcam-locale" ), 'manage_options', GFireM_Webcam::getSlug(), array( $this, 'screen' ), 'dashicons-camera' );
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