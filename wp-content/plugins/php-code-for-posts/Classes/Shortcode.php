<?php
class PhpCodeForPosts_Shortcode {

	const DEFAULT_SHORTCODE = 'php';

	public static function get_shortcode()
	{
		$option_value = PhpCodeForPosts::$options->get_option( 'shortcode' );
		return $option_value == '' ? self::DEFAULT_SHORTCODE : $option_value;
	}

	public static function handle_shortcode( $attributes )
	{
		$arguments = array( 'snippet' => 0, 'param' => '' );
		shortcode_atts( $arguments, $attributes );

		$snippet_id = intval( $attributes['snippet'] );
		if( $snippet_id == 0 ) {
			return '';
		}

		$snippet = PhpCodeForPosts_Database::load_single_snippet( $snippet_id );
		if (! $snippet->is_loaded() ) {
			return '';
		}

		$snippet_prefix = '?>';

		if (isset( $attributes['param'] ) && $attributes['param'] != '' ) {
			$snippet_pre_prefix = '$_parameters = array(); parse_str(htmlspecialchars_decode("' . $attributes['param'] . '"), $_parameters);';

			if (PhpCodeForPosts::$options->get_option( 'parameter_extraction' )) {
				$snippet_pre_prefix .= 'extract($_parameters);';
			}

			$snippet_prefix = $snippet_pre_prefix . $snippet_prefix;

		}

		ob_start();
		eval( $snippet_prefix . $snippet->get_code() );
		return ob_get_clean();
	}

	public static function handle_extra_shortcode( $content )
	{
		$shortcode = self::get_shortcode();
		$content = str_ireplace( "[/{$shortcode}]", " ?>", $content );
		$content = str_ireplace( "[{$shortcode}]", "<?php ", $content );
		ob_start();
		eval( "?>" . $content );
		return ob_get_clean();
	}

}
