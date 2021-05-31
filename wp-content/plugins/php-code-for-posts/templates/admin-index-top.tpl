<div class='wrap'>

	<h2>
		<img src='<?php echo PhpCodeForPosts::web_path_it( PhpCodeForPosts_Menu::MENU_ICON_URL )?>' alt='' class='left-align' />
		<?php _e( PhpCodeForPosts_Menu::MENU_PAGE_TITLE )?>
	</h2>

	<?php
		if (PhpCodeForPosts_Messages::has_error_messages()) {
			PhpCodeForPosts_Messages::display_error_messages();
		} elseif (PhpCodeForPosts_Messages::has_success_messages()) {
			PhpCodeForPosts_Messages::display_success_messages();
		}
	?>
	<div id='phpcfp-notificationbox'></div>

	<ul class='phpcfp-menu'>
		<li class='phpcfp-menuitem'>
			<a href='#!' data-tab='snippets' class='tabchange activetab-head'><?php _e( 'Snippets', "phpcodeforposts" )?></a>
		</li>
		<li class='phpcfp-menuitem'>
			<a href='#!' data-tab='options' class='tabchange'><?php _e( 'Options', "phpcodeforposts" )?></a>
		</li>
		<li class='phpcfp-menuitem new-snippet'>
			<a href='<?php echo PhpCodeForPosts_Snippet::get_new_snippet_link()?>'><?php _e( 'New Snippet', "phpcodeforposts" )?></a>
		</li>
		<li class='phpcfp-menuitem other'>
			<a href='#!' data-tab='other' class='tabchange'><?php _e( 'Other', "phpcodeforposts" )?></a>
		</li>
		<li class='phpcfp-menuitem donate'>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<h3><?php _e( 'Support The Plugin', "phpcodeforposts" )?></h3>
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="SFQZ3KDJ4LQBA">
				<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
			</form>
		</li>
	</ul>




