<?php 

if (!defined('_PS_VERSION_')) {
    exit;
}

class Helper_Global{

	private static $instance = null;

	public static $filter_values;
	public static $hook_values;
	public static $current_filter;
	public static $current_action;

	private function __construct() {
	}

    public function convertica_update_option($key, $value) {
        $table_name = _DB_PREFIX_ . 'convertica_options';
		$result     = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$key'" );
		if ( is_array( $value ) || is_object( $value ) ) {
			$value = json_encode( $value );
		}

		$key  = pSQL( $key );
		$value = pSQL( $value );
		if ( $result === false ) {
			Db::getInstance()->insert(
				'convertica_options',
				array(
					'option_name'  => $key,
					'option_value' => $value,
				)
			);
		} else {
			Db::getInstance()->update( 'convertica_options', array( 'option_value' => $value ), "`option_name` = '$key'" );
		}
    }

    public function convertica_get_option($key, $default = false) {
        $table_name  = _DB_PREFIX_ . 'convertica_options';
		$key = pSQL( $key );
		$result      = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$key'" );
		if ( empty( $result ) || $result == false ) {
			return $default;
		} else {
			return $result;
		}
    }

    public function include_core_files() {
        require_once dirname( __FILE__ ) . '/classes/class-convert-plug.php';
    }

	public function apply_filters( $tag, $value, $arg1 = '', $arg2 = '', $arg3 = '', $arg4 = '', $arg5 = '' ) {
		if ( isset( self::$filter_values[ $tag ] ) ) {
			self::$current_filter = $tag;
			$filtered_value       = null;
			$params               = array( $value, $arg1, $arg2, $arg3, $arg4, $arg5 );
			$filter_tag_values    = self::$filter_values[ $tag ];
			foreach ( $filter_tag_values as $filter ) {
				if ( $filter['type'] == 'class' ) {
					$return_data = call_user_func_array( array( $filter['class'], $filter['function_name'] ), $params );
				} else {
					$return_data = call_user_func_array( $filter['function_name'], $params );
				}
				// get the filtered value weather string or array. sometimes returns only string
				$filtered_value = $return_data;
				// if array then reassign the value
				if ( is_array( $return_data ) ) {
					if ( count( $return_data ) == 1 || empty( $return_data ) ) {
						if ( ! empty( $return_data ) ) {
							$array_value[ key( $return_data ) ] = $return_data[ key( $return_data ) ];
						} else {
							$array_value = array();
						}
					} else {
						$array_value = $return_data;
					}
					$filtered_value = $array_value;
				}
			}
			self::$current_filter = null;
			return $filtered_value;
		} else {
			return $value;
		}
	}

	public function add_filter( $tag, $function, $priority = 10, $accepted_args = 1 ) {
		if ( is_array( $function ) ) {
			$function_info['class']         = $function[0];
			$function_info['type']          = 'class';
			$function_info['function_name'] = $function[1];
		} else {
			$function_info['type']          = 'noclass';
			$function_info['function_name'] = $function;
		}
		self::$filter_values[ $tag ][] = $function_info;
		return true;
	}

	public function add_action( $tag, $function, $priority = 10, $accepted_args = 1 ) {

		if ( $tag == 'plugins_loaded' ) {
			$params = array();
			call_user_func_array( $function, $params );
		} else {
			if ( is_array( $function ) ) {
				$function_info['class']         = $function[0];
				$function_info['type']          = 'class';
				$function_info['function_name'] = $function[1];
			} else {
				$function_info['type']          = 'noclass';
				$function_info['function_name'] = $function;
			}
			self::$hook_values[ $tag ][] = $function_info;
		}

		return true;
	}

	public function do_action( $tag, $arg1 = '', $arg2 = '', $arg3 = '', $arg4 = '', $arg5 = '' ) {
		if ( isset( self::$hook_values[ $tag ] ) ) {
			self::$current_action = $tag;
			$params               = array( $arg1, $arg2, $arg3, $arg4, $arg5 );
			foreach ( self::$hook_values[ $tag ] as $hook ) {
				if ( $hook['type'] == 'class' ) {
					call_user_func_array( array( $hook['class'], $hook['function_name'] ), $params );
				} else {
					call_user_func_array( $hook['function_name'], $params );
				}
			}
			self::$current_action = null;
		} else {
			return true;
		}
	}

	public function wp_upload_dir(){

		return [
			'path' => CP_PLUS_UPLOADS_DIR,
			'url' => CP_PLUS_UPLOADS_URL,
			'subdir' => '',
			'basedir' => CP_PLUS_UPLOADS_DIR,
			'baseurl' => CP_PLUS_UPLOADS_URL,
			'error' => '',
		];
	}

	public function wp_rand( $min = null, $max = null ) {
		global $rnd_value;
	
		/*
		 * Some misconfigured 32-bit environments (Entropy PHP, for example)
		 * truncate integers larger than PHP_INT_MAX to PHP_INT_MAX rather than overflowing them to floats.
		 */
		$max_random_number = 3000000000 === 2147483647 ? (float) '4294967295' : 4294967295; // 4294967295 = 0xffffffff
	
		if ( null === $min ) {
			$min = 0;
		}
	
		if ( null === $max ) {
			$max = $max_random_number;
		}
	
		// We only handle ints, floats are truncated to their integer value.
		$min = (int) $min;
		$max = (int) $max;
	
		// Use PHP's CSPRNG, or a compatible method.
		static $use_random_int_functionality = true;
		if ( $use_random_int_functionality ) {
			try {
				// wp_rand() can accept arguments in either order, PHP cannot.
				$_max = max( $min, $max );
				$_min = min( $min, $max );
				$val  = random_int( $_min, $_max );
				if ( false !== $val ) {
					return absint( $val );
				} else {
					$use_random_int_functionality = false;
				}
			} catch ( Error $e ) {
				$use_random_int_functionality = false;
			} catch ( Exception $e ) {
				$use_random_int_functionality = false;
			}
		}
	
		/*
		 * Reset $rnd_value after 14 uses.
		 * 32 (md5) + 40 (sha1) + 40 (sha1) / 8 = 14 random numbers from $rnd_value.
		 */
		if ( strlen( $rnd_value ) < 8 ) {
			static $seed = '';
			$rnd_value  = md5( uniqid( microtime() . mt_rand(), true ) . $seed );
			$rnd_value .= sha1( $rnd_value );
			$rnd_value .= sha1( $rnd_value . $seed );
			$seed       = md5( $seed . $rnd_value );
		}
	
		// Take the first 8 digits for our value.
		$value = substr( $rnd_value, 0, 8 );
	
		// Strip the first eight, leaving the remainder for the next call to wp_rand().
		$rnd_value = substr( $rnd_value, 8 );
	
		$value = abs( hexdec( $value ) );
	
		// Reduce the value to be within the min - max range.
		$value = $min + ( $max - $min + 1 ) * $value / ( $max_random_number + 1 );
	
		return abs( (int) $value );
	}

	public function is_admin(){
		// Check if we are in the PrestaShop admin context
		if (defined('_PS_ADMIN_DIR_')) {
			// You can also use the Context class to get more information
			if (class_exists('Context')) {
				$context = Context::getContext();

				// Check if it's the admin context
				if ($context->controller instanceof AdminController) {
					// We are in the admin context
					return true;
				} else {
					// We are in the front office context
					return false;
				}
			}
		} else {
			// We are in the front office context
			return false;
		}
	}

	public function __($text, $slug){
		return Context::getContext()->getTranslator()->trans( $text, [], 'Modules.Convertica.Admin' );
	}

	public function esc_html__($text, $slug){
		return Context::getContext()->getTranslator()->trans( $text, [], 'Modules.Convertica.Admin' );
	}

	public function esc_html($text){
		return $text;
	}

	public function esc_url($url){
		return $url;
	}


	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new Helper_Global();
		}
		return self::$instance;
	}
}