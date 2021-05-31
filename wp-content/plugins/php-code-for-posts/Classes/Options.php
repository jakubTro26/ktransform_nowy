<?php
class PhpCodeForPosts_Options {

	const OPTION_NAME = 'phppc_menu';

	protected $options;

	public function __construct()
	{
		$this->load_options();
	}

	private function get_defaults()
	{
		return array(
			'complete_deactivation' 	=> 0,
			'content_filter' 			=> 1,
			'enable_richeditor' 		=> 1,
			'shortcode'					=> PhpCodeForPosts_Shortcode::DEFAULT_SHORTCODE,
			'sidebar_filter' 			=> 1,
			'table_version' 			=> 0,
			'ajaxible'					=> 1,
			'parameter_extraction'		=> 0,
		);
	}

	private function get_default( $option_name )
	{
		$options = $this->get_defaults();
		return isset( $options[$option_name] ) ? $options[ $option_name ] : false;
	}

	private function load_options()
	{
		$this->options = get_option( self::OPTION_NAME, $this->get_defaults() );
	}

	public function save_options()
	{
		return update_option( self::OPTION_NAME, $this->options );
	}

	public function get_option( $option_name )
	{
		if (! $this->has_option( $option_name ) ) {
			return $this->get_default( $option_name ) ;
		}

		return $this->options[$option_name];
	}

	public function set_option( $option_name, $option_value )
	{
		$this->options[$option_name] = $option_value;
	}

	public function has_option( $option_name )
	{
		return isset( $this->options[$option_name] );
	}

	public function save_posted_options( $post_fields )
	{
		foreach( $this->get_defaults() as $key => $value ) {
			if( isset( $post_fields[$key] ) ) {
				$this->options[$key] = $post_fields[$key];
			}
		}
		return $this->save_options();
	}
}
