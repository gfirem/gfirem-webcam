<?php
/**
 * @package    WordPress
 * @subpackage Formidable, gfirem
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GFireMWebcamAdmin {

    public static $type = "webcam_field";

    function  __construct(){
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
    }

    public function enqueue_js( $hook ) {
	    $current_screen = get_current_screen();
	    if ( 'toplevel_page_formidable' === $current_screen->id ) {
		    wp_enqueue_style( 'gfirem_webcam_admin_css', GFireM_Webcam::$assets . 'css/gfirem-webcam.css' );
		    wp_enqueue_script( 'gfirem_webcam_admin', GFireM_Webcam::$assets . 'js/camera_admin.js', array( "jquery" ), GFireM_Webcam::getVersion(), true );
	    }
     
    }
}
