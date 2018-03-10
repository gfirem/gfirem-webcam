<?php
/**
 *
 * @since             1.0.0
 * @package           GFireM_Webcam
 *
 * @wordpress-plugin
 * Plugin Name:       GFireM Webcam Fields
 * Description:       Add a Webcam field to your formidable forms!
 * Version:           1.0.0
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 *
 *
 * Copyright 2017 Guillermo Figueroa Mesa (email: gfirem@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if (!defined('WPINC')) {
    die;
}


if ( ! class_exists( 'GFireM_Webcam' ) ) {
	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class-gfirem-webcam-field-fs.php';
	$is_fs_loaded = GFireM_Webcam_Fs::get_instance();
	
	class GFireM_Webcam {
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;
		public static $assets;
		public static $view;
		public static $classes;
		public static $slug = 'gfirem-webcam';
		public static $version = '1.0.0';
		
		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			self::$assets  = plugin_dir_url( __FILE__ ) . 'assets/';
			self::$view    = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
			self::$classes = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
			$this->load_plugin_textdomain();
			require_once self::$classes . 'class-gfirem-webcam-manager.php';
			new GFireMWebcamManager();
		}
		
		static function getFreemius(){
			return GFireM_Webcam_Fs::getFreemius();
		}
		
		/**
		 * Get plugin version
		 *
		 * @return string
		 */
		static function getVersion() {
			return self::$version;
		}
		
		/**
		 * Get plugins slug
		 *
		 * @return string
		 */
		static function getSlug() {
			return self::$slug;
		}
		
		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'gfirem-webcam-field-locale', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	add_action( 'plugins_loaded', 'gfirem_webcam_field_init' );
	function gfirem_webcam_field_init() {
		global $gfirem;
		$gfirem[ GFireM_Webcam::$slug ]['instance'] = GFireM_Webcam::get_instance();
	}
}

