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
class GFireMWebcamFieldController extends GFireMFieldBase {
	
	public $version = '1.0.0';
	
	public function __construct() {
		parent::__construct( 'webcam_field', __( 'Webcam Field', 'gfirem-webcam-field-locale' ),
			array(
				'button_title'  => __( 'Take Snapshot', 'gfirem-webcam-field-locale' ),
				'button_css'    => '',
				'activate_zoom' => 'true',
				'scroll_zoom'   => 'false',
			),
			__( 'Take a Snapshot whit the webcam.', 'gfirem-webcam-field-locale' ), array('icon' => 'dashicons dashicons-camera')
		);
		
		add_filter( 'frm_pre_create_entry', array( $this, 'process_pre_create_entry' ), 10, 1 );
		add_filter( 'frm_pre_update_entry', array( $this, 'process_pre_update_entry' ), 10, 2 );
		add_action( 'frm_before_destroy_entry', array( $this, 'process_destroy_entry' ), 10, 2 );
	}
	
	/**
	 * Destroy the attached image to the entry
	 *
	 * @param $id
	 * @param $entry
	 */
	public function process_destroy_entry( $id, $entry ) {
		$entry_with_meta = FrmEntry::getOne( $id, true );
		foreach ( $entry_with_meta->metas as $key => $value ) {
			$field_type = FrmField::get_type( $key );
			if ( $field_type == 'webcam_field' && ! empty( $value ) ) {
				wp_delete_attachment( $value, true );
			}
		}
	}
	
	
	public function process_pre_update_entry( $values, $entry_id ) {
		$values['item_meta'] = $this->save_snapshot( $values['item_meta'], $values['form_id'], true );
		
		return $values;
	}
	
	public function save_snapshot( $fields_collections, $form_id, $delete_before = false ) {
		$params = array();
		foreach ( $fields_collections as $key => $value ) {
			$field_type = FrmField::get_type( $key );
			if ( $field_type == 'webcam_field' && ! empty( $value ) ) {
				$exploded_data = explode( ",", $value );
				if ( ! isset( $exploded_data[1] ) ) {
					//En caso de que no se edite el campo signature @Victor
					continue;
				}
				if ( $delete_before && ! empty( $decoded_value['id'] ) ) {
					wp_delete_attachment( $decoded_value['id'], true );
				}
				$decoded_image = base64_decode( $exploded_data[1] );
				$upload_dir    = wp_upload_dir();
				$file_id       = $this->slug . '_' . $form_id . '_' . $key . '_' . time();
				$file_name     = $file_id . ".png";
				$full_path     = wp_normalize_path( $upload_dir['path'] . DIRECTORY_SEPARATOR . $file_name );
				$upload_file   = wp_upload_bits( $file_name, null, $decoded_image );
				if ( ! $upload_file['error'] ) {
					$wp_filetype   = wp_check_filetype( $file_name, null );
					$attachment    = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
					if ( ! is_wp_error( $attachment_id ) ) {
						require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
						$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
						wp_update_attachment_metadata( $attachment_id, $attachment_data );
						$value                         = $attachment_id;
						$fields_collections[ $key ]    = $value;
						$_POST['item_meta'][ $key ]    = $value;//Used to update the current request
						$_REQUEST['item_meta'][ $key ] = $value;//Used to update the current request
					}
				}
			}
		}
		
		return $fields_collections;
	}
	
	public function process_pre_create_entry( $values ) {
		$values['item_meta'] = $this->save_snapshot( $values['item_meta'], $values['form_id'] );
		
		return $values;
	}
	
	/**
	 * Add script needed to load the field
	 *
	 * @param string $hook
	 * @param string $image_url
	 * @param $field_name
	 */
	public function add_script( $hook = '', $image_url = '', $field_name ) {
		wp_enqueue_script( 'webcam', GFireM_Webcam::$assets . 'js/webcam.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'gfirem_webcam', GFireM_Webcam::$assets . 'js/camera.js', array( "jquery" ), $this->version, true );
		$params          = array();
		$signatureFields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
		foreach ( $signatureFields as $key => $field ) {
			foreach ( $this->defaults as $def_key => $def_val ) {
				$opt                                                          = FrmField::get_option( $field, $def_key );
				$params['config'][ 'field_' . $field->field_key ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
			}
			if ( ! empty( $image_url ) ) {
				$params['config'][ 'item_meta[' . $field->id . ']' ]['image_url'] = $image_url;
			}
		}
		if ( ! empty( $_GET['frm_action'] ) ) {
			$params['action'] = FrmAppHelper::get_param( 'frm_action' );
		}
		wp_localize_script( 'gfirem_webcam', 'gfirem_webcam', $params );
	}
	
	/**
	 * Options inside the form
	 *
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	protected function inside_field_options( $field, $display, $values ) {
		include GFireM_Webcam::$view . 'field_option.php';
	}
	
	protected function field_front_view( $field, $field_name, $html_id ) {
		$field['value'] = stripslashes_deep( $field['value'] );
		$html_id        = $field['id'];
		$print_value    = $field['default_value'];
		if ( ! empty( $field['value'] ) ) {
			$print_value = $field['value'];
		}
		$showContainer = '';
		if ( empty( $field['value'] ) ) {
			$showContainer = 'style = "display:none;"';
		}
		$imageUrl         = wp_get_attachment_image_url( $field['value'] );
		$full_image_url   = wp_get_attachment_image_src( $field['value'], 'full' );
		$imageFullUrl     = wp_get_attachment_url( $field['value'] );
		$attachment_title = basename( get_attached_file( $field['value'] ) );
		$button_name      = FrmField::get_option( $field, 'button_title' );
		$this->add_script( '', $imageUrl, $field_name );
		include GFireM_Webcam::$view . 'field_webcam.php';
	}
	
	
	protected function field_admin_view( $value, $field, $attr ) {
		$value = $this->getMicroImage( $value );
		
		return $value;
	}
	
	private function getMicroImage( $id ) {
		$result = '';
		$src    = wp_get_attachment_url( $id );
		if ( ! empty( $id ) && ! empty( $src ) ) {
			$result = wp_get_attachment_image( $id, array( 50, 50 ), true ) . " <a style='vertical-align: top;' target='_blank' href='" . $src . "'>" . __( "Full Image", 'gfirem-webcam-field-locale' ) . "</a>";
		}
		
		return $result;
	}
	
	protected function process_short_code( $id, $tag, $attr, $field ) {
		$internal_attr = shortcode_atts( array(
			'output' => 'img',
			'size'   => 'thumbnail',
			'html'   => '0',
		), $attr );
		$result        = wp_get_attachment_url( $id );
		if ( $internal_attr['output'] == 'img' ) {
			$result = wp_get_attachment_image( $id, $internal_attr['size'] );
		}
		if ( $internal_attr['html'] == '1' ) {
			$result = "<a style='vertical-align: top;' target='_blank'  href='" . wp_get_attachment_url( $id ) . "' >" . $result . "</a>";
		}
		$replace_with = $result;
		
		return $replace_with;
	}
	
}
