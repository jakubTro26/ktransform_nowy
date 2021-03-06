<?php

/**
 * Color field class.
 */
class RWMB_Color_Field extends RWMB_Text_Field {

	/**
	 * Enqueue scripts and styles
	 */
	static function admin_enqueue_scripts() {
		wp_enqueue_style( 'rwmb-color', ievent_RWMB_CSS_URL . 'color.css', array( 'wp-color-picker' ), ievent_RWMB_VER );
		wp_enqueue_script( 'rwmb-color', ievent_RWMB_JS_URL . 'color.js', array( 'wp-color-picker' ), ievent_RWMB_VER, true );
	}

	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field
	 * @return array
	 */
	static function normalize( $field ) {
		$field = wp_parse_args( $field, array(
			'size'       => 7,
			'maxlength'  => 7,
			'pattern'    => '^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$',
			'js_options' => array(),
		) );

		$field['js_options'] = wp_parse_args( $field['js_options'], array(
			'defaultColor' => false,
			'hide'         => true,
			'palettes'     => true,
		) );

		$field = parent::normalize( $field );

		return $field;
	}

	/**
	 * Get the attributes for a field
	 *
	 * @param array $field
	 * @param mixed $value
	 * @return array
	 */
	static function get_attributes( $field, $value = null ) {
		$attributes = parent::get_attributes( $field, $value );
		$attributes = wp_parse_args( $attributes, array(
			'data-options' => wp_json_encode( $field['js_options'] ),
		) );
		$attributes['type'] = 'text';

		return $attributes;
	}

	/**
	 * Format a single value for the helper functions.
	 *
	 * @param array  $field Field parameter
	 * @param string $value The value
	 * @return string
	 */
	static function format_single_value( $field, $value ) {
		return sprintf( "<span style='display:inline-block;width:20px;height:20px;border-radius:50%%;background:%s;'></span>", $value );
	}
}
