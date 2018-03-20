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

class GFiremWebcamAdmin {

    public static $type = "webcam_field";

    function  __construct(){
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
       // add_action( 'admin_enqueue_scripts', array( $this, 'front_enqueue_style' ) );

    }

    public function enqueue_js( $hook ) {

        wp_enqueue_script( 'gfirem_webcam_admin', GFireM_Webcam::$assets . 'js/camera_admin.js', array( "jquery" ), $this->version, true );


    }
}