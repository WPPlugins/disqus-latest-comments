<?php
/*
Plugin Name: Disqus latest comments addon
Description: Displays the latest Disqus comments for a website.
Version: 1.7.0
Author: Adrian Gordon
Author URI: http://www.itsupportguides.com
License: GPLv2
*/

/** Allow shortcodes to work in widgets **/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );

if ( ! class_exists('ITSG_Disqus_Latest_Comments_Addon' ) ) {
    class ITSG_Disqus_Latest_Comments_Addon
    {
        /**
        * Construct the plugin object
        */

        public function __construct()
        {
            // register actions

            /** Back end - register menu */
            add_action( 'admin_menu', array( $this,'itsg_disqus_lastest_comments_addon_admin_menu' ) );

            /** register shortcode 'disqus-latest' **/
            add_shortcode( 'disqus-latest', array( $this,'itsg_disqus_lastest_comments_addon_shortcode' ) );

            add_action( 'wp_footer', array( $this,'itsg_disqus_lastest_comments_addon_change_text_js_script' ) );

			add_action( 'wp_footer', array( $this,'itsg_disqus_lastest_comments_addon_css_styles' ) );

			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );
        }

		/*
         * Add 'Settings' link to plugin in WordPress installed plugins page
         */
		function plugin_action_links( $links ) {
			$action_links = array(
				'settings' => '<a href="' . admin_url( 'edit-comments.php?page=disqus-latest-comments' ) . '">' . __( 'Settings', 'disqus-latest-comments' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		} // END plugin_action_links

        /*
        *   Front end - what is rendered when shortcode is used
        */
        public function itsg_disqus_lastest_comments_addon_shortcode()
        {
            $html = '';
            /** Set default values **/
            if ( get_option( 'num_items' ) ) {
                $num_items = ( float ) esc_attr( get_option( 'num_items' ) );
            } else {
                $num_items = '5';
            }

            if ( get_option( 'hide_avatars' ) ) {
                $hide_avatars = '1';
            } else {
                $hide_avatars = '0';
            }

            if ( get_option( 'avatar_size' ) ) {
                $avatar_size = ( float ) esc_attr( get_option( 'avatar_size' ) );
            } else {
                $avatar_size = '35';
            }

            if ( get_option( 'excerpt_length' ) ) {
                $excerpt_length = ( float ) esc_attr( get_option( 'excerpt_length' ) );
            } else {
                $excerpt_length = '200';
            }

            if ( get_option( 'bypass_cache' ) ) {
                $bypass_cache = true;
            } else {
                $bypass_cache = false;
            }

			if ( is_ssl() ) {
                $protocol = 'https://';
            } else {
                $protocol = 'http://';
            }
			
			$disqus_shortname = esc_attr( get_option( 'disqus_shortname' ) );

            /** If Disqus shortname has been configured **/

            if ( $disqus_shortname ) {
                $html .= '<script type="text/javascript" src="'. $protocol . $disqus_shortname . '.disqus.com/recent_comments_widget.js?num_items=' . $num_items . '&hide_avatars=' . $hide_avatars . '&avatar_size=' . $avatar_size . '&excerpt_length=' . $excerpt_length . '&rand=' . mt_rand() . '"></script>';
            } else {

                /** If Disqus shortname has NOT been configured **/
                $html .= "
			<p><strong>" . __( 'Disqus Latest Comments - Configuration required', 'disqus-latest-comments' ) . "</strong></p>
			<p>" . sprintf( __( 'Disqus shortname required - see <a href="%s" target="_blank" >Disqus Latest Comments configuration page</a>.', 'disqus-latest-comments' ),  admin_url( 'edit-comments.php?page=disqus-latest-comments' ) ) . "</p> ";
            }

            return $html;
        } // END itsg_disqus_lastest_comments_addon_shortcode

        /** Back end - menu */
        public function itsg_disqus_lastest_comments_addon_admin_menu()
        {
            add_comments_page('Disqus Latest Comments', 'Disqus Latest Comments', 'manage_options', 'disqus-latest-comments', array($this,'itsg_disqus_lastest_comments_addon_options'));
        } // END itsg_disqus_lastest_comments_addon_admin_menu

        /** Back end - form */
        public function itsg_disqus_lastest_comments_addon_options()
        {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( __('You do not have sufficient permissions to access this page.', 'disqus-latest-comments' ) );
            }

            if ( isset( $_POST['mt_submit_hidden'] ) && sanitize_text_field( $_POST['mt_submit_hidden'] ) == 'Y' ) {
                $disqus_shortname = sanitize_text_field( $_POST['disqus_shortname'] );
                update_option( 'disqus_shortname', $disqus_shortname );

                $num_items = sanitize_text_field( $_POST['num_items'] );
                update_option( 'num_items', $num_items );

                $hide_avatars = sanitize_text_field( $_POST['hide_avatars'] );
                update_option( 'hide_avatars', $hide_avatars );

                $avatar_size = sanitize_text_field( $_POST['avatar_size'] );
                update_option( 'avatar_size', $avatar_size );

                $excerpt_length = sanitize_text_field( $_POST['excerpt_length'] );
                update_option( 'excerpt_length', $excerpt_length );

                $style = sanitize_text_field( $_POST['style'] );
                update_option( 'style', $style );

                $bypass_cache = sanitize_text_field($_POST['bypass_cache'] );
                update_option( 'bypass_cache', $bypass_cache );

                $disqus_minute_ago = sanitize_text_field( $_POST['disqus_minute_ago'] );
                update_option( 'disqus_minute_ago', $disqus_minute_ago );

                $disqus_minutes_ago = sanitize_text_field( $_POST['disqus_minutes_ago'] );
                update_option( 'disqus_minutes_ago', $disqus_minutes_ago );

                $disqus_hour_ago = sanitize_text_field( $_POST['disqus_hour_ago'] );
                update_option( 'disqus_hour_ago', $disqus_hour_ago );

                $disqus_hours_ago = sanitize_text_field( $_POST['disqus_hours_ago'] );
                update_option( 'disqus_hours_ago', $disqus_hours_ago );

                $disqus_day_ago = sanitize_text_field( $_POST['disqus_day_ago'] );
                update_option( 'disqus_day_ago', $disqus_day_ago );

                $disqus_days_ago = sanitize_text_field( $_POST['disqus_days_ago'] );
                update_option( 'disqus_days_ago', $disqus_days_ago );

                $disqus_week_ago = sanitize_text_field( $_POST['disqus_week_ago'] );
                update_option( 'disqus_week_ago', $disqus_week_ago );

                $disqus_weeks_ago = sanitize_text_field( $_POST['disqus_weeks_ago'] );
                update_option( 'disqus_weeks_ago', $disqus_weeks_ago );

                $disqus_month_ago = sanitize_text_field( $_POST['disqus_month_ago'] );
                update_option( 'disqus_month_ago', $disqus_month_ago );

                $disqus_months_ago = sanitize_text_field( $_POST['disqus_months_ago'] );
                update_option( 'disqus_months_ago', $disqus_months_ago );

                $disqus_year_ago = sanitize_text_field( $_POST['disqus_year_ago'] );
                update_option( 'disqus_year_ago', $disqus_year_ago );

                $disqus_years_ago = sanitize_text_field( $_POST['disqus_years_ago'] );
                update_option( 'disqus_years_ago', $disqus_years_ago );

                $disqus_target_blank = sanitize_text_field( $_POST['disqus_target_blank'] );
                update_option( 'disqus_target_blank', $disqus_target_blank );

				$disqus_custom_css = sanitize_text_field( $_POST['disqus_custom_css'] );
                update_option( 'disqus_custom_css', $disqus_custom_css );

            } else {
                $disqus_shortname = esc_html( get_option( 'disqus_shortname' ) );
                $num_items = esc_html( get_option( 'num_items' ) );
                $hide_avatars = esc_html( get_option( 'hide_avatars' ) );
                $avatar_size = esc_html( get_option( 'avatar_size' ) );
                $excerpt_length = esc_html( get_option( 'excerpt_length' ) );
                $style = esc_html( get_option( 'style' ) );
                $bypass_cache = esc_html( get_option( 'bypass_cache' ) );
                $disqus_minute_ago = esc_html( get_option( 'disqus_minute_ago' ) );
                $disqus_minutes_ago = esc_html( get_option( 'disqus_minutes_ago' ) );
                $disqus_hour_ago = esc_html( get_option( 'disqus_hour_ago' ) );
                $disqus_hours_ago =  esc_html( get_option( 'disqus_hours_ago' ) );
				$disqus_day_ago = esc_html( get_option( 'disqus_day_ago' ) );
                $disqus_days_ago = esc_html( get_option( 'disqus_days_ago' ) );
                $disqus_week_ago = esc_html( get_option( 'disqus_week_ago' ) );
                $disqus_weeks_ago = esc_html( get_option( 'disqus_weeks_ago' ) );
                $disqus_month_ago = esc_html( get_option( 'disqus_month_ago' ) );
                $disqus_months_ago = esc_html( get_option( 'disqus_months_ago' ) );
                $disqus_year_ago = esc_html( get_option( 'disqus_year_ago' ) );
                $disqus_years_ago = esc_html( get_option( 'disqus_years_ago' ) );
                $disqus_target_blank = esc_html( get_option( 'disqus_target_blank' ) );
				$disqus_custom_css = esc_html( get_option( 'disqus_custom_css' ) );
            }

            $hidden_field_name = 'mt_submit_hidden';
?>
			<div class="wrap">
				<h2><?php _e( 'Disqus Latest Comments - Options', 'disqus-latest-comments' ); ?></h2>
				<p><?php _e( 'This plugin will allow you to list your websites latest comments in a page or post.', 'disqus-latest-comments' ); ?></p>
				<p><?php _e( 'Instructions:
					<ol>
					<li>Enter your Disqus shortname below and save the changes.</li>
					<li>Open or create the post or page you want the comments to be included in<li>enter the shortcode <strong>[disqus-latest]</strong> into the content area and save the changes.</li>
					<li>The comments will now be displayed on the post or page where the shortcode has been used.</li>
					</ol>', 'disqus-latest-comments' ); ?></p>

				<form method="post">
					<table class="form-table">
						<tbody>
						<tr>
							<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
							<th scope="row"><?php _e( 'Disqus shortname:' );?></th>
							<td><input type="text" name="disqus_shortname" value="<?php echo $disqus_shortname; ?>" size="20">
							<p class="description"><strong><?php _e( 'Where do I find my Disqus shortname?', 'disqus-latest-comments') ?></strong></p><?php
							printf( '<ol>
								<li>%s</li>
								<li>%s</li>
								<li>%s</li>
								<li>%s</li>
							</ol>', 
							sprintf( __( 'Go to <a href="%s">the Disqus website</a> and log in', 'disqus-latest-comments' ), 'https://www.disqus.com' ),
							__( "Click on your avatar icon at the top right of the screen and select 'Settings'", 'disqus-latest-comments' ), 
							__( "Click on the settings icon (a cog) at the top right of the screen and select 'Admin''", 'disqus-latest-comments' ), 
							__( "In the 'General' page you'll see 'Shortname' - this is your Disqus shortname for enterering above.", 'disqus-latest-comments' ) );?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Number of comments:', 'disqus-latest-comments' ); ?></th>
							<td><input type="number" name="num_items" min="1" max="25" value="<?php echo $num_items; ?>" size="20"></td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Hide avatars:', 'disqus-latest-comments' ); ?></th>
							<td>
							<select name='hide_avatars'>
							<option value='0' <?php if ( $hide_avatars == '0' ) { echo 'selected'; } ?>><?php _e( 'No', 'disqus-latest-comments' ); ?></option>
							<option value='1' <?php if ( $hide_avatars == '1' ) { echo 'selected'; } ?>><?php _e( 'Yes', 'disqus-latest-comments' ); ?></option>
							</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Avatar size:', 'disqus-latest-comments' ); ?></th>
							<td>
							<select name='avatar_size'>
							<option value='35' <?php if ( $avatar_size == '35' ) { echo 'selected'; } ?>>35px</option>
							<option value='48' <?php if ( $avatar_size == '48' ) { echo 'selected'; } ?>>48px</option>
							<option value='92' <?php if ( $avatar_size == '92' ) { echo 'selected'; } ?>>92px</option>
							</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Excerpt length:', 'disqus-latest-comments' ); ?></th>
							<td><input type="number" name="excerpt_length" min="1" max="500" value="<?php echo $excerpt_length; ?>" size="20"></td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Style:' ); ?></th>
							<td>
							<select id="disqus_style_select" onclick="ToggleDisqusStyle();" onblur="ToggleDisqusStyle();"  onchange="ResetDisqusStyle();"  name='style'>
							<option value='0' <?php if ( $style == 'None' ) { echo 'selected'; } ?>><?php _e( 'None', 'disqus-latest-comments' ); ?></option>
							<option value='Custom' <?php if ( $style == 'Custom' ) { echo 'selected'; } ?>><?php _e( 'Custom', 'disqus-latest-comments' ); ?></option>
							<option value='Grey' <?php if ( $style == 'Grey' ) { echo 'selected'; } ?>><?php _e( 'Grey', 'disqus-latest-comments' ); ?></option>
							<option value='Blue' <?php if ( $style == 'Blue' ) { echo 'selected'; } ?>><?php _e( 'Blue', 'disqus-latest-comments' ); ?></option>
							<option value='Green' <?php if ( $style == 'Green' ) { echo 'selected'; } ?>><?php _e( 'Green', 'disqus-latest-comments' ); ?></option>
							</select>
							</td>
						</tr>
						<tr style='display:none' id="disqus_custom_css">
							<th scope="row"><?php _e( 'Custom CSS:', 'disqus-latest-comments' ); ?></th>
							<td>
							<p><?php _e( 'Use the box below to enter your custom CSS styles - <strong>do not include the &#60;style&#62; tags</strong>.', 'disqus-latest-comments' ); ?></p>
							<p><?php _e( 'Classes available include:', 'disqus-latest-comments' ); ?></p><?php
							printf( '<ol>
								<li>%s</li>
								<li>%s</li>
								<li>%s</li>
								<li>%s</li>
								<li>%s</li>
								<li>%s</li>
							</ol>', 
							__( ".dsq-widget-list - the entire list", 'disqus-latest-comments' ), 
							__( ".dsq-widget-item - each comment item", 'disqus-latest-comments' ), 
							__( ".dsq-widget-avatar - the avatar image in each comment item", 'disqus-latest-comments' ), 
							__( ".dsq-widget-user - the Disqus user name", 'disqus-latest-comments' ), 
							__( ".dsq-widget-comment - the comment", 'disqus-latest-comments' ), 
							__( ".dsq-widget-meta - paragraph that contains the link to the post and day", 'disqus-latest-comments' ) ) ?>
							<p><?php printf( __( 'See <a href="%s" target="_blank" >plugin frequently asked questions</a> for examples.', 'disqus-latest-comments' ), 'https://wordpress.org/plugins/disqus-latest-comments/faq/' )?></p>
							<textarea name='disqus_custom_css' style='width:100%;' rows="10" cols="50"><?php echo $disqus_custom_css; ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e("Bypass Cache:"); ?></th>
							<td>
							<select name='bypass_cache'>
							<option value='0' <?php if ( $bypass_cache == '0') { echo 'selected'; } ?>>No</option>
							<option value='1' <?php if ( $bypass_cache == '1') { echo 'selected'; } ?>>Yes</option>
							</select>
							</td>
						</tr>
						<tr>
						<th>
						<label for="disqus_target_blank"><?php _e( "Open Disqus usernames in new window (target='_blank')", 'disqus-latest-comments' ) ?></label>
						</th>
						<td>
						<input type="checkbox" id="disqus_target_blank" name="disqus_target_blank" <?php if ( $disqus_target_blank) echo "checked='checked'"; ?>  >
						</td>
						</tr>
						</tbody>
					</table>
					<hr />
					<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
					</p>
					<h3><?php _e( "Translate options", 'disqus-latest-comments' ) ?></h3>
					<p><?php _e( 'These options allow you to translate the time terms used by Disqus.' ); ?></p>
					<table class="form-table">
						<tbody>
					<tr>
						<th scope="row"><?php _e( "Minute ago:" ); ?></th>
						<td><input type="text" name="disqus_minute_ago" value="<?php echo $disqus_minute_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Minutes ago:" ); ?></th>
						<td><input type="text" name="disqus_minutes_ago" value="<?php echo $disqus_minutes_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Hour ago:" ); ?></th>
						<td><input type="text" name="disqus_hour_ago" value="<?php echo $disqus_hour_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Hours ago:" ); ?></th>
						<td><input type="text" name="disqus_hours_ago" value="<?php echo $disqus_hours_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Day ago:" ); ?></th>
						<td><input type="text" name="disqus_day_ago" value="<?php echo $disqus_day_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Days ago:" ); ?></th>
						<td><input type="text" name="disqus_days_ago" value="<?php echo $disqus_days_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Week ago:" ); ?></th>
						<td><input type="text" name="disqus_week_ago" value="<?php echo $disqus_week_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Weeks ago:" ); ?></th>
						<td><input type="text" name="disqus_weeks_ago" value="<?php echo $disqus_weeks_ago; ?>" size="20">
						</td>

					</tr>

					<tr>
						<th scope="row"><?php _e( "Month ago:" ); ?></th>
						<td><input type="text" name="disqus_month_ago" value="<?php echo $disqus_month_ago;?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Months ago:" ); ?></th>
						<td><input type="text" name="disqus_months_ago" value="<?php echo $disqus_months_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Year ago:" ); ?></th>
						<td><input type="text" name="disqus_year_ago" value="<?php echo $disqus_year_ago; ?>" size="20">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( "Years ago:" ); ?></th>
						<td><input type="text" name="disqus_years_ago" value="<?php echo $disqus_years_ago; ?>" size="20">
						</td>
					</tr>
					</tbody>
					</table>
					<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
					</p>
					<hr />
				</form>
			</div>
		<script type="text/javascript">

		function ToggleDisqusStyle() {
					if (jQuery("#disqus_style_select > option:selected").text() == "Custom") {
						jQuery("#disqus_custom_css").show();
					} else {
						jQuery("#disqus_custom_css").hide();
					}
				}
		function ResetDisqusStyle() {
				jQuery("#disqus_style_select > option.default").prop("selected", true);
				}

		jQuery(document).ready(function() {
			ToggleDisqusStyle();
		});
		</script>
			<?php
        } // END itsg_disqus_lastest_comments_addon_options

        /*
        * jQuery that changes Disqus time terms
        */
        public function itsg_disqus_lastest_comments_addon_change_text_js_script()
        {
            $disqus_minute_ago = esc_js( get_option( 'disqus_minute_ago' ) );
            $disqus_minutes_ago = esc_js( get_option( 'disqus_minutes_ago' ) );
            $disqus_hour_ago = esc_js( get_option( 'disqus_hour_ago' ) );
            $disqus_hours_ago = esc_js( get_option( 'disqus_hours_ago' ) );
            $disqus_day_ago = esc_js( get_option( 'disqus_day_ago' ) );
            $disqus_days_ago = esc_js( get_option( 'disqus_days_ago' ) );
            $disqus_week_ago = esc_js( get_option( 'disqus_week_ago' ) );
            $disqus_weeks_ago = esc_js( get_option( 'disqus_weeks_ago' ) );
            $disqus_month_ago = esc_js( get_option( 'disqus_month_ago' ) );
            $disqus_months_ago = esc_js( get_option( 'disqus_months_ago' ) );
            $disqus_year_ago = esc_js( get_option( 'disqus_year_ago' ) );
            $disqus_years_ago = esc_js( get_option( 'disqus_years_ago' ) );
            $disqus_target_blank = esc_js( get_option( 'disqus_target_blank' ) );

            if ( $disqus_target_blank || $disqus_minute_ago || $disqus_minutes_ago || $disqus_hour_ago || $disqus_hours_ago || $disqus_day_ago || $disqus_days_ago || $disqus_week_ago || $disqus_weeks_ago || $disqus_month_ago || $disqus_months_ago || $disqus_year_ago || $disqus_years_ago ) {
				
				wp_register_script( 'disqus-latest-comments-js', plugins_url( "/js/disqus-latest-comments-js.js", __FILE__ ), array( 'jquery' ) );
				
				$settings_array = array(
					'disqus_minute_ago' => $disqus_minute_ago,
					'disqus_minutes_ago' => $disqus_minutes_ago,
					'disqus_hour_ago' => $disqus_hour_ago,
					'disqus_hours_ago' => $disqus_hours_ago,
					'disqus_day_ago' => $disqus_day_ago,
					'disqus_days_ago' => $disqus_days_ago,
					'disqus_week_ago' => $disqus_week_ago,
					'disqus_weeks_ago' => $disqus_weeks_ago,
					'disqus_month_ago' => $disqus_month_ago,
					'disqus_months_ago' => $disqus_months_ago,
					'disqus_year_ago' => $disqus_year_ago,
					'disqus_years_ago' => $disqus_years_ago,
					'disqus_target_blank' => $disqus_target_blank,

				);

				wp_localize_script( 'disqus-latest-comments-js', 'disqus_latest_comments_js_settings', $settings_array );

				// Enqueued script with localized data.
				wp_enqueue_script( 'disqus-latest-comments-js' );

            }

        } // END itsg_disqus_lastest_comments_addon_change_text_js_script

		/*
        * CSS Styles placed in footer
        */
        public function itsg_disqus_lastest_comments_addon_css_styles()
        {
			if ( get_option( 'style' ) == "Custom" ) {
			?>
			<style>
			<?php echo esc_html( get_option( 'disqus_custom_css' ) ) ?>
			</style>
			<?php
			} else if ( get_option( 'style' ) == "Grey" ) {
				wp_enqueue_style( 'disqus-latest-comments-grey-css', plugins_url( "/css/disqus-latest-comments-grey-css.css", __FILE__ ) );
            } else if ( get_option( 'style' ) == "Blue" ) {
				wp_enqueue_style( 'disqus-latest-comments-blue-css', plugins_url( "/css/disqus-latest-comments-blue-css.css", __FILE__ ) );
            } else if ( get_option( 'style' ) == "Green" ) {
				wp_enqueue_style( 'disqus-latest-comments-green-css', plugins_url( "/css/disqus-latest-comments-green-css.css", __FILE__ ) );
            }
		} // END itsg_disqus_lastest_comments_addon_css_styles
    }
    $ITSG_Disqus_Latest_Comments_Addon = new ITSG_Disqus_Latest_Comments_Addon();
}