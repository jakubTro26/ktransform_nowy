<?php
class PhpCodeForPosts_Database {

	const TABLENAME = 'phppc_functions';

	public static function get_db()
	{
		global $wpdb;
		return $wpdb;
	}

	public static function get_full_table_name()
	{
		$db = self::get_db();
		return $db->prefix . self::TABLENAME;
	}

	public static function load_all_snippets()
	{
		$db = self::get_db();
		$query = sprintf(
			'SELECT id, name, description, code FROM %s ORDER BY id',
			self::get_full_table_name()
		);

		$snippets = $db->get_results( $query );

		foreach( $snippets as $index => $snippet ) {
			$snippets[$index] = PhpCodeForPosts_Snippet::create_from_database_object( $snippet );
		}

		return $snippets;
	}

	public static function load_single_snippet( $snippet_id )
	{
		if(! filter_var( $snippet_id, FILTER_VALIDATE_INT ) ) {
			throw new InvalidArgumentException;
		}

		$db = self::get_db();
		$query = sprintf(
			'SELECT id, name, description, code FROM %s WHERE id = %%d',
			self::get_full_table_name()
		);

		$query = $db->prepare( $query, $snippet_id );
		$snippet_row = $db->get_row( $query );
		if(! $snippet_row ) {
			$snippet_row = new StdClass;
		}
		return PhpCodeForPosts_Snippet::create_from_database_object( $snippet_row );
	}
	public static function delete_snippet_by_id($snippet_id)
	{
		if(! filter_var( $snippet_id, FILTER_VALIDATE_INT ) ) {
			throw new InvalidArgumentException( 'Invalid Snippet Id: ' . $snippet_id );
		}
		$db = self::get_db();
		return $db->delete( self::get_full_table_name(), array( 'id' => $snippet_id ), array( "%d" ) ) !== false;
	}

	public static function save_snippet( PhpCodeForPosts_Snippet &$snippet )
	{
		if( $snippet->get_id() > 0 ) {
			return self::_update_snippet($snippet);
		}

		return self::_insert_snippet($snippet);
	}

	private static function _insert_snippet( PhpCodeForPosts_Snippet &$snippet )
	{
		$db = self::get_db();
		$inserted = $db->insert(
			self::get_full_table_name(),
			$snippet->get_array_for_db(),
			$snippet->get_array_format_for_db()
		) !== false;

		if ($inserted) {
			$snippet->set_id( $db->insert_id);
		}
		return $inserted;
	}

	private static function _update_snippet( PhpCodeForPosts_Snippet $snippet )
	{
		$db = self::get_db();
		return $db->update(
			self::get_full_table_name(),
			$snippet->get_array_for_db(),
			$snippet->get_where_for_update(),
			$snippet->get_array_format_for_db(),
			$snippet->get_where_format_for_update()
		) !== false;
	}
}
