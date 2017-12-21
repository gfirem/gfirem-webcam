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


 if (!class_exists('GFireM_Webcam')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class-gfirem-webcam-field-fs.php';
    $is_fs_loaded = GFireM_Webcam_Fs::get_instance();
    if ($is_fs_loaded !== false) {
        class GFireM_Webcam
        {
            /**
             * Instance of this class.
             *
             * @var object
             */
            protected static $instance = null;

            /**
             * Initialize the plugin.
             */
            private function __construct()
            {

                require_once gfirem::$fields . DIRECTORY_SEPARATOR . 'gfirem_field_base.php';
                require_once gfirem::$classes . DIRECTORY_SEPARATOR . 'gfirem_fs.php';
                $this->constants();
                $this->load_plugin_textdomain();
                require_once GFIREM_WEBCAM_CLASSES_PATH . 'class-webcam_field.php';
                new GFireMWebcamFieldController();
            }


            private function constants()
            {
                define('GFIREM_WEBCAM_CSS_PATH', plugin_dir_url(__FILE__) . 'assets/css/');
                define('GFIREM_WEBCAM_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
                define('GFIREM_WEBCAM_VIEW_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR);
                define('GFIREM_WEBCAM_CLASSES_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR);
            }

            /**
             * Return an instance of this class.
             *
             * @return object A single instance of this class.
             */
            public static function get_instance()
            {
                // If the single instance hasn't been set, set it now.
                if (null == self::$instance) {
                    self::$instance = new self;
                }

                return self::$instance;
            }

            /**
             * Load the plugin text domain for translation.
             */
            public function load_plugin_textdomain()
            {
                load_plugin_textdomain('gfirem-webcam-field-locale', false, basename(dirname(__FILE__)) . '/languages');
            }
        }

        add_action('plugins_loaded', array('GFireM_Webcam', 'get_instance'), 9999);
    }
} else {
    //TODO necesita notificar aqui que no esta insalado el core
}

