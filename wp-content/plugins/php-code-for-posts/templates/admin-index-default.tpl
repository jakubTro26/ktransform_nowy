<div class='tabs phpcfp-tabs'>
	<div class='tab tab-snippets activetab'>
		<h3><?php _e( 'Saved Code Snippets', "phpcodeforposts" ) ?></h3>
		<form action='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG ?>' method='post'>
			<?php echo PhpCodeForPosts::get_hidden_field( 'action', 'bulkdelete' ) ?>
			<?php echo PhpCodeForPosts::get_hidden_field( 'item', '' ) ?>
			<?php wp_nonce_field( 'bulkdelete', PhpCodeForPosts::POSTFIELD_PREFIX . '[actioncode]') ?>
			<table cellpadding='5' cellspacing='0' class='wp-list-table widefat fixed posts striped'>
				<thead>
					<tr>
						<td class='manage-column column-cb check-column' scope='col'><input type='checkbox'></td>
						<th class='manage-column' scope='col'><?php _e( 'ID', "phpcodeforposts" ) ?></th>
						<th class='manage-column' scope='col'><?php _e( 'Name', "phpcodeforposts" ); ?></th>
						<th class='manage-column' scope='col'><?php _e( 'Description', "phpcodeforposts" ); ?></th>
						<th class='manage-column' scope='col'><?php _e( 'Shortcode', "phpcodeforposts" ); ?></th>
					</tr>
				</thead>
				<tbody id='TPSL' data-noitems='<tr class="no-items"><td colspan="4" class="colspanchange">No Code Found.</td></tr>'>
				<?php
					$snippets = PhpCodeForPosts_Database::load_all_snippets();

					if( count( $snippets ) > 0 ) {
						foreach( $snippets as $index => $snippet ) {
							include PhpCodeForPosts::directory_path_it( 'templates/admin-snippet-row.tpl' );
						}
					}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td class='manage-column column-cb check-column' scope='col'><input type='checkbox'></td>
						<th class='manage-column' scope='col'><?php _e( 'ID', "phpcodeforposts" ) ?></th>
						<th class='manage-column' scope='col'><?php _e( 'Name', "phpcodeforposts" ) ?></th>
						<th class='manage-column' scope='col'><?php _e( 'Description', "phpcodeforposts" ) ?></th>
						<th class='manage-column' scope='col'><?php _e( 'Shortcode', "phpcodeforposts" ) ?></th>
					</tr>
				</tfoot>
			</table>
			<p><input type="submit" class="button-secondary" value="Deleted Selected Snippets" /></p>
		</form>
	</div>

	<div class='tab tab-options'>
		<h3><?php _e('Plugin Options') ?></h3>
		<form action='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG ?>' method='post'>
			<?php echo PhpCodeForPosts::get_hidden_field( 'action', 'updateoptions' ) ?>
			<?php echo PhpCodeForPosts::get_hidden_field( 'item', '' ) ?>
			<?php wp_nonce_field( 'updateoptions', PhpCodeForPosts::POSTFIELD_PREFIX . '[actioncode]' ) ?>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'complete_deactivation', __('Remove all options and tables on uninstall', "phpcodeforposts" ) ); ?></p>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'content_filter', __('Parse inline plugin shortcode tags inside post content (HTML Editor Only)', "phpcodeforposts" ) ); ?></p>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'sidebar_filter', __('Parse inline plugin shortcode tags inside sidebar text widgets', "phpcodeforposts" ) ); ?></p>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'enable_richeditor', __('Enable Codemirror\'s rich editor for code snippets', "phpcodeforposts" ) ); ?></p>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'parameter_extraction', __('Extract shortcode params to their own variables', "phpcodeforposts" ) ); ?>*</p>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'ajaxible', __('Use Ajax to save snippets', "phpcodeforposts" ) ); ?></p>
			<p class='formlabel'><?php echo PhpCodeForPosts::get_input_field( 'shortcode', PhpCodeForPosts::$options->get_option('shortcode'), __('Change the shortcode for the plugin (not recommended if you already have snippets!)', "phpcodeforposts" ) ); ?></p>
			<div class='clearall'></div>
			<p><input type='submit' class='button-primary' value='<?php _e( 'Save Options', "phpcodeforposts" ) ?>' /></p>
		</form>
		<p>*<em><?php _e('See the readme for more information on this');?></em></p>
	</div>

	<div class='tab tab-other'>
		<iframe src='http://www.jamiefraser.co.uk/php-code-for-posts/' width="100%" frameborder="0" height="415px"></iframe>
	</div>

</div>



