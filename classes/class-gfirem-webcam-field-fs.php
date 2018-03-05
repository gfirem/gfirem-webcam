<?php
/**
 * @package    WordPress
 * @subpackage Formidable
 * @author     GFireM
 * @copyright  2018
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

class GFireM_Webcam_Fs {
	
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	protected static $slug = 'gfirem-webcam';
	
	public function __construct() {
		$this->load_freemius();
	}
	
	/**
	 * @return Freemius
	 */
	public static function getFreemius() {
		global $gfirem;
		
		return $gfirem[ self::$slug ]['freemius'];
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
	
	private function load_freemius() {
		global $gfirem;
		
		if ( ! isset( $gfirem[ self::$slug ]['freemius'] ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/include/freemius/start.php';
			try {
				$gfirem[ self::$slug ]['freemius'] = fs_dynamic_init( array(
					'id'                  => '1824',
					'slug'                => 'gfirem-webcam-field',
					'type'                => 'plugin',
					'public_key'          => 'pk_6faec3ea95bdd2d0db34edf17c479',
					'is_premium'          => true,
					'is_premium_only'     => true,
					'has_premium_version' => true,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'is_org_compliant'    => false,
					'trial'               => array(
						'days'               => 14,
						'is_require_payment' => true,
					),
					'menu'                => array(
						'slug'           => 'gfirem-webcam',
						'first-path'     => 'admin.php?page=gfirem-webcam',
						'support'        => false,
					),
					// Set the SDK to work in a sandbox mode (for development & testing).
					// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
					'secret_key'          => 'sk_#.Uv=~p_vQGaIfcI{YAD5?G&#<k*Z',
				) );
			} catch ( Exception $ex ) {
				$gfirem[ self::$slug ]['freemius'] = false;
			}
		}
		
		return $gfirem[ self::$slug ]['freemius'];
	}
	
}
