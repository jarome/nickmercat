<?php
if( !defined('ABSPATH') ) exit;
if (!class_exists("Neat_Theme_Options")) {
    class Neat_Theme_Options {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {
			$this->initSettings();
        }

        public function initSettings() {

            if ( !class_exists("ReduxFramework" ) ) {
                return;
            }       
            
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();
            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections() {
			//---- Theme Option Here ----//
			$schedules	=	array();
        	$wp_get_schedules	=	function_exists( 'wp_get_schedules' ) ? wp_get_schedules() : null;
        	if( is_array( $wp_get_schedules ) && !empty( $wp_get_schedules ) ){
        		foreach ($wp_get_schedules as $key=>$value) {
        			$schedules[ $key ]	=	$value['display'];
        		}
        	}

			$this->sections[] 	=	array(
				'title'	=>	__('General','neat'),
				'icon'	=>	'el-icon-cogs',
				'desc'	=>	null,
				'fields'	=>	array(
					array(
						'id'        => 'homepage',
						'type'      => 'callback',
						'title'     => __('Homepage', 'neat'),
						'subtitle'      => __('Setup the Homepage.', 'neat'),
						'callback'  => 'neat_homepage_callback'
					),									
					array(
						'id'        => 'logo',
						'type'      => 'callback',
						'title'     => __('Logo', 'neat'),
						'subtitle'      => __('Upload your logo.', 'neat'),
						'callback'  => 'neat_logo_callback'
					),						
					array(
						'id'	=>	'favicon',
						'type'	=>	'media',
						'url' => true,
                        'subtitle' => __('Upload any media using the WordPress native uploader', 'neat'),				
						'title'	=>	__('Favicon','neat')
					),
                   array(
                        'id' => 'custom_css',
                        'type' => 'ace_editor',
                        'title' => __('Custom CSS', 'neat'),
                        'subtitle' => __('Paste your CSS code here, no style tag.', 'neat'),
                        'mode' => 'css',
                        'theme' => 'monokai'
                    ),	
                    array(
                        'id' => 'custom_js',
                        'type' => 'ace_editor',
                        'title' => __('Custom JS', 'neat'),
                        'subtitle' => __('Paste your JS code here, no script tag, eg: alert(\'hello world\');', 'neat'),
                        'mode' => 'javascript',
                        'theme' => 'chrome'
                    )
				)
			);
			
			$this->sections[] 	=	array(
				'title'	=>	__('Blog','neat'),
				'icon'	=>	'el-icon-cogs',
				'desc'	=>	null,
				'fields'	=>	array(
						/**
						array(
							'id'        => 'blogpage',
							'type'      => 'callback',
							'title'     => __('Blog page', 'neat'),
							'subtitle'      => __('Setup the blog page.', 'neat'),
							'callback'  => 'neat_blogpage_callback'
						),
						**/
						array(
							'id'        => 'bloglayout',
							'type'      => 'image_select',
							'compiler'  => true,
							'title'     => __('Blog Layout', 'neat'),
							'subtitle'	=>	__('Choose the Blog page layout.','neat'),
							'options'   => array(
									'r_sidebar' => array('alt' => __('Right Sidebar','neat'), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
									'l_sidebar' => array('alt' => __('Left Sidebar','neat'),  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
							),
							'default'   => 'r_sidebar'
						),
						array(
							'id'        => 'exclude_page_search',
							'type'      => 'checkbox',
							'title'     => __('Excluding Page Search', 'neat'),
							'subtitle'      => __('Do not display the Page in search result page.', 'neat'),
							'default'   => '1'
						),
						array(
							'id'        => 'include_video_homepage',
							'type'      => 'checkbox',
							'title'     => __('Including Video Format in Homepage', 'neat'),
							'subtitle'	=>	__('I only want to retrieve the Videos Post Format in Homepage.','neat'),
							'default'   => '0'
						),
						array(
							'id'        => 'exclude_video_blogpage',
							'type'      => 'checkbox',
							'title'     => __('Excluding Video Format in Blog Page', 'neat'),
							'subtitle'	=>	__('Do not retrieve the the Videos Post Format in Blog page.','neat'),
							'default'   => '0'
						),
						array(
							'id'        => 'appid',
							'type'      => 'text',
							'title'     => __('AppID', 'neat'),
							'subtitle'  => 'Facebook AppID',
							'description'	=> sprintf( __('Get a key %s.', 'neat'), '<a target="_blank" href="https://developers.facebook.com">HERE</a>')
						),
						array(
							'id'        => 'comment',
							'type'      => 'button_set',
							'title'	=>	__('Comment','neat'),
							'subtitle'	=>	__('Choose the Comment System.','neat'),
							'description'	=>	__('If you use the Comment plugin (Disqus, facebook ... etc), You have to choose "Default".','neat'),
							'options'   => array(
								'default'		=>		__('Default','neat'),
								'facebook'		=>		__('Facebook','neat'),
								'both'			=>		__('Both','neat')
							),
							'default'   => 'default'
						),
						array(
							'id'    => 'info-warning-both',
							'type'  => 'info',
							'style' => 'warning',
							//'title' => __('Warning.', 'neat'),
							'desc'  => __('The total comment number = default + facebook numner.','neat'),
							'indent'    => false,
							'required'  => array('comment', "=", 'both'),
						),						
						array(
							'id'    => 'info-warning',
							'type'  => 'info',
							'style' => 'warning',
							//'title' => __('Warning.', 'neat'),
							'desc'  => sprintf( __('If you use the Facebook Comment system, You have to setup the %s in one time, If you change the Permalink, All the exists comments will be lost.','neat') , '<a href="'.admin_url( 'options-permalink.php' ).'">Permalink</a>' ),
							'indent'    => false,
							'required'  => array('comment', "!=", 'default'),			
						),										
						array(
							'id'        => 'data-colorscheme',
							'type'      => 'button_set',
							'title'     => __('Comment Style', 'neat'),
							'description'	=>	__('The color scheme used by the plugin. Can be "light" or "dark".','neat'),
							'options'	=>	array(
								'light'	=>	__('Light','neat'),
								'dark'	=>	__('Dark','neat')
							),
							'default'   => 'light',
							'indent'    => false,
							'required'  => array('comment', "!=", 'default'),							
						),						
						array(
							'id'        => 'data-numposts',
							'type'      => 'text',
							'title'     => __('Number of Posts', 'neat'),
							'description'	=>	__('The number of comments to show by default. The minimum value is 1.','neat'),
							'default'	=>	10,
							'validate'  => 'comma_numeric',
							'indent'    => false,
							'required'  => array('comment', "!=", 'default'),
						),
						array(
							'id'        => 'data-orderby',
							'type'      => 'select',
							'title'     => __('Order by', 'neat'),
							'description'	=>	sprintf( __('The order to use when displaying comments. Can be "social", "reverse_time", or "time". The different order types are explained %s','neat') , '<a target="_blank" href="https://developers.facebook.com/docs/plugins/comments#faqorder">in the FAQ</a>' ),
							'options'	=>	array(
								'social'		=>	__('Social','neat'),
								'reverse_time'	=>	__('Reverse Time','neat'),
								'time'			=>	__('Time','neat')
							),
							'default'	=>	'social',
							'indent'    => false,
							'required'  => array('comment', "!=", 'default'),
						),						
						array(
							'id'        => 'facebooklang',
							'type'      => 'select',
							'title'     => __('Language', 'neat'),
							'options'	=>	array(
								'af_ZA'			=>		'Afrikaans',
								'ar_AR'			=>		'Arabic',
								'az_AZ'			=>		'Azerbaijani',
								'be_BY'			=>		'Belarusian',
								'bg_BG'			=>		'Bulgarian',
								'bn_IN'			=>		'Bengali',
								'bs_BA'			=>		'Bosnian',
								'ca_ES'			=>		'Catalan',
								'cs_CZ'			=>		'Czech',
								'cy_GB'			=>		'Welsh',
								'da_DK'			=>		'Danish',
								'de_DE'			=>		'German',
								'el_GR'			=>		'Greek',
								'en_GB'			=>		'English (UK)',	
								'en_PI'			=>		'English (Pirate)',
								'en_UD'			=>		'English (Upside Down)',
								'en_US'			=>		'English (US)',
								'eo_EO'			=>		'Esperanto',
								'es_ES'			=>		'Spanish (Spain)',
								'es_LA'			=>		'Spanish',
								'et_EE'			=>		'Estonian',
								'eu_ES'			=>		'Basque',
								'fa_IR'			=>		'Persian',
								'fb_LT'			=>		'Leet Speak',
								'fi_FI'			=>		'Finnish',
								'fo_FO'			=>		'Faroese',
								'fr_CA'			=>		'French (Canada)',
								'fr_FR'			=>		'French (France)',
								'fy_NL'			=>		'Frisian',
								'ga_IE'			=>		'Irish',
								'gl_ES'			=>		'Galician',
								'he_IL'			=>		'Hebrew',
								'hi_IN'			=>		'Hindi',
								'hr_HR'			=>		'Croatian',
								'hu_HU'			=>		'Hungarian',
								'hy_AM'			=>		'Armenian',
								'id_ID'			=>		'Indonesian',
								'is_IS'			=>		'Icelandic',
								'it_IT'			=>		'Italian',
								'ja_JP'			=>		'Japanese',
								'ka_GE'			=>		'Georgian',
								'km_KH'			=>		'Khmer',
								'ko_KR'			=>		'Korean',
								'ku_TR'			=>		'Kurdish',
								'la_VA'			=>		'Latin',
								'lt_LT'			=>		'Lithuanian',
								'lv_LV'			=>		'Latvian',
								'mk_MK'			=>		'Macedonian',
								'ml_IN'			=>		'Malayalam',
								'ms_MY'			=>		'Malay',
								'nb_NO'			=>		'Norwegian (bokmal)',
								'ne_NP'			=>		'Nepali',
								'nl_NL'			=>		'Dutch',
								'nn_NO'			=>		'Norwegian (nynorsk)',
								'pa_IN'			=>		'Punjabi',
								'pl_PL'			=>		'Polish',
								'ps_AF'			=>		'Pashto',
								'pt_BR'			=>		'Portuguese (Brazil)',
								'pt_PT'			=>		'Portuguese (Portugal)',
								'ro_RO'			=>		'Romanian',
								'ru_RU'			=>		'Russian',
								'sk_SK'			=>		'Slovak',
								'sl_SI'			=>		'Slovenian',
								'sq_AL'			=>		'Albanian',
								'sr_RS'			=>		'Serbian',
								'sv_SE'			=>		'Swedish',
								'sw_KE'			=>		'Swahili',
								'ta_IN'			=>		'Tamil',
								'te_IN'			=>		'Telugu',
								'th_TH'			=>		'Thai',
								'tl_PH'			=>		'Filipino',
								'tr_TR'			=>		'Turkish',
								'uk_UA'			=>		'Ukrainian',
								'vi_VN'			=>		'Vietnamese',
								'zh_CN'			=>		'Simplified Chinese (China)',
								'zh_HK'			=>		'Traditional Chinese (Hong Kong)',
								'zh_TW'			=>		'Traditional Chinese (Taiwan)'
							),
							'default'	=>	'en_US',
							'indent'    => false,
							'required'  => array('comment', "!=", 'default'),
						),
						array(
							'id'        => 'counter_up_interval',
							'type'      => 'select',
							'title'     => __('Scheduling Intervals', 'neat'),
							'subtitle'	=>	__('Scheduling the updating Counter intervals.','neat'),
							'description'	=>	__('Leave blank for real time.','neat'),
							'options'		=>	$schedules,
							'default'	=>	'15m'
						),
						
					)
				);
			
			$this->sections[]	=	array(
				'title'	=>	__('Submit','neat'),
				'icon'      => 'el-icon-upload',
				'desc'      => __('<p class="description">Neat support the post submission at Fronend through Contact Form 7 Form.</p>', 'neat'),
				'fields'	=>	array(					
					array(
						'id'        => 'cf7id',
						'type'      => 'text',
						'title'     => __('Contact Form 7 ID', 'neat'),
						'description'	=>	sprintf( __('Enter the form ID for Post Submission, %s','neat'), '<a href="'.admin_url( 'admin.php?page=wpcf7' ).'">Where is it?</a>' ),
						'validate'  => 'comma_numeric'
					),
					array(
						'id'        => 'post_status',
						'type'      => 'select',
						'title'     => __('Post Status', 'neat'),
						'description'	=>	__('Set the post status for the post, which is submitted through the Contact Form 7 at Frontend.','neat'),
						'options'	=>	array(
							'pending'	=>	__('Pending','neat'),
							'publish'	=>	__('publish','neat')
						),
						'default'	=>	'pending'
					),
					/**
					array(
						'id'        => 'user_roles',
						'type'      => 'select',
						'title'     => __('Who can submit the post?', 'neat'),
						'data'      => 'roles',
						'description'	=>	__('Choose the User roles, all users of this roles can submit the post through Frontend, Leave blank for Guest (all posts is submitted by guest will be assigned to Admin).','neat'),
						'multi'     => true,
					),
					array(
						'id'        => 'limit_posts_amount',
						'type'      => 'text',
						'title'     => __('Posts amount/day', 'neat'),
						'description'	=>	__( 'Limit the posts per day for the User (This function won\'t work if Guest post is activated), 0 is un-limit.','neat' ),
						'validate'  => 'comma_numeric',
						'default'	=>	5
					),
					array(
						'id'        => 'role_not_limit',
						'type'      => 'select',
						'title'     => __('Roles is not limited', 'neat'),
						'data'      => 'roles',
						'description'	=>	__('Don\'t apply the limit post for this roles.','neat'),
						'multi'     => true,
					),
					**/
					array(
						'id'        => 'response',
						'type'      => 'checkbox',
						'title'     => __('Auto Response', 'neat'),
						'subtitle'      => __('Send the message through email to the User after submitted.', 'neat'),
						'default'   => 0
					),
					array(
						'id'        => 'response_name',
						'type'      => 'text',
						'title'     => __('Sender\'s name', 'neat'),
						'default'	=>	get_bloginfo( 'name' ),
						'indent'    => false,
						'required'  => array('response', "=", 1),
					),						
					array(
						'id'        => 'response_email',
						'type'      => 'text',
						'title'     => __('Sender\'s email', 'neat'),
						'default'	=>	get_bloginfo( 'admin_email' ),
						'indent'    => false,
						'required'  => array('response', "=", 1),
					),						
					array(
						'id'        => 'response_subject',
						'type'      => 'text',
						'title'     => __('Response Subject', 'neat'),
						'subtitle'  => __('The subject of the email', 'neat'),
						'description'	=>	sprintf( __('<strong>Tags support:</strong><br/><code>[user]</code>: Username/Display Name of User.<br/><code>[sitename]</code>: Your site name (%s)<br/><code>[site_desc]</code>: Your Site description (%s)','neat'), get_bloginfo('name'), get_bloginfo('description') ),
						'indent'    => false,
						'required'  => array('response', "=", 1),							
					),
					array(
						'id'        => 'response_content',
						'type'      => 'textarea',
						'title'     => __('Response Content', 'neat'),
						'subtitle'  => __('The Content of the email', 'neat'),
						'description'	=>	sprintf( __('<strong>Tags support:</strong><br/><code>[user]</code>: Username/Display Name of User.<br/><code>[sitename]</code>: Your site name (%s)<br/><code>[site_desc]</code>: Your Site description (%s)<br/><code>[post_link]</code>: The post link (working for the Publish post).','neat'), get_bloginfo('name'), get_bloginfo('description') ),
						'indent'    => false,
						'required'  => array('response', "=", 1)				
					),											
				)
			);			
			
			$this->sections[]	=	array(
				'title'	=>	__('Styling','neat'),
				'icon'      => 'el-icon-website',
				'fields'	=>	array(
					array(
						'id'        => 'main-style',
						'type'      => 'image_select',
						'compiler'  => true,
						'title'     => __('Main Style', 'neat'),
						'options'   => array(
							'main' => array('alt' =>  __('Default','neat') , 'img' => NEAT_THEME_URI . '/assets/img/default.png'  ),
							'main-blue' => array('alt' =>  __('Blue style','neat') , 'img' => NEAT_THEME_URI . '/assets/img/blue.png'  ),
							'main-red' => array('alt' =>  __('Red style','neat') , 'img' => NEAT_THEME_URI . '/assets/img/red.png'  ),
							'main-dark' => array('alt' =>  __('Dark style','neat') , 'img' => NEAT_THEME_URI . '/assets/img/dark.png'  ),
						),
						'default'   => 'main'
					),
					array(
						'id'        => 'background',
						'type'      => 'background',
						'output'    => array('body'),
						'title'     => __('Body Background', 'neat'),
						'subtitle'  => __('Body background with image, color, etc.', 'neat'),
						'default'   => '#FFFFFF',
					),										
					array(
						'id'        => 'header-background',
						'type'      => 'color',
						'title'     => __('Header Background Color', 'neat'),
						'subtitle'  => __('Pick a background color for the Header (default: #ffffff).', 'neat'),
						'default'   => '#ffffff',
						'validate'  => 'color',
					),
					array(
						'id'        => 'menu-color',
						'type'      => 'color',
						'title'     => __('Menu Color', 'neat'),
						'subtitle'  => __('Pick a color for the Menu (default: #b3b3b3).', 'neat'),
						'default'   => '#b3b3b3',
						'validate'  => 'color',
					),
					array(
						'id'        => 'menu-hover-color',
						'type'      => 'color',
						'title'     => __('Menu Hover Color', 'neat'),
						'subtitle'  => __('Pick a color for the Menu Hover (default: #2ecc71).', 'neat'),
						'default'   => '#2ecc71',
						'validate'  => 'color',
					),
					array(
						'id'        => 'footer-background',
						'type'      => 'color',
						'title'     => __('Footer Background', 'neat'),
						'subtitle'  => __('Pick a background color for the Footer (default: #ffffff).', 'neat'),
						'default'   => '#ffffff',
						'validate'  => 'color',
					),
					array(
						'id'        => 'footer-color',
						'type'      => 'color',
						'title'     => __('Footer Color', 'neat'),
						'subtitle'  => __('Pick a color for the Footer Text (default: #bfbfbf).', 'neat'),
						'default'   => '#bfbfbf',
						'validate'  => 'color',
					),						
				)
			);			
			
			//footer
			$footer_field = array();
			$footer_field[] = array(
				'id'	=>	'footer_text',
				'title'	=>	__('Copyright Text','neat'),
				'type'	=>	'textarea',
				'default'	=>	sprintf( __('Copyright © 2014 <a href="%s">Neat</a> - All Rights Reserved.','neat'), home_url() ),
				'desc'	=>	__('HTML is allowed in here.','neat')
			);
			$socials = neat_option_socials();
			if( is_array( $socials ) ){
				foreach ( $socials  as $key=>$value) {
					$footer_field[] = array(
						'id'	=>	'footer_social_' . $key,
						'title'	=>	$value['name'],
						'type'	=>	'text',
						'placeholder'	=>	'http://',
						'desc'	=>	sprintf(  __('Put the %s profile/page link here.','neat') , $value['name'] )
					);
				}
			}

			$this->sections[]	=	array(
				'title'	=>	__('Footer','neat'),
				'icon'	=>	'el-icon-cogs',
				'fields'	=>	$footer_field
			);
							
        }
        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'neattheme', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'), // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'), // Version that appears at the top of your panel
                'menu_type' => 'submenu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __('Theme Options', 'neat'),
                'page' => __('Theme Options', 'neat'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                'async_typography'  => true,
                //'admin_bar' => false, // Show the panel pages on the admin bar
                'global_variable' => '', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'customizer' => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => '', // Specify a custom URL to an icon
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '', // __( '', $this->args['domain'] );            
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://twitter.com/marstheme',
                'title' => 'Follow us on Twitter',
                'icon' => 'el-icon-twitter'
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace("-", "_", $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'neat'), $v);
            } else {
                $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'neat');
            }

            // Add content after the form.
            //$this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'neat');
        }

    }

    new Neat_Theme_Options();
}

if( !function_exists( 'neat_homepage_callback' ) ){
	function neat_homepage_callback() {
		$blog_page = get_option( 'page_on_front' ) ? get_option( 'page_on_front' ) : null;
		if( empty( $blog_page ) ){
			printf(  __('Click %s to setup the Homepage %s','neat') , '<a href="'.admin_url( 'options-reading.php' ).'">HERE</a>', '<a target="_blank" href="http://puu.sh/b9Nxv/048ad7e50c.png">(Image)</a>' );
		}
		else{
			printf( __('View %s or change it %s','neat') , '<a href="'.get_permalink( $blog_page ).'">Homepage</a>', '<a href="'.admin_url( 'options-reading.php' ).'">HERE</a>' );
		}
	}
}

if( !function_exists( 'neat_blogpage_callback' ) ){
	function neat_blogpage_callback() {
		$blog_page = get_option( 'page_for_posts' ) ? get_option( 'page_for_posts' ) : null;
		if( empty( $blog_page ) ){
			printf(  __('Click %s to setup the blog page %s','neat') , '<a href="'.admin_url( 'options-reading.php' ).'">HERE</a>', '<a target="_blank" href="http://puu.sh/b9Nxv/048ad7e50c.png">(Image)</a>' );
		}
		else{
			printf( __('View %s page or change it %s','neat') , '<a href="'.get_permalink( $blog_page ).'">Blog</a>', '<a href="'.admin_url( 'options-reading.php' ).'">HERE</a>' );	
		}
	}
}

if( !function_exists( 'neat_logo_callback' ) ){
	function neat_logo_callback() {
		if( get_header_image() ){
			print '<img style="max-width: 200px;" src="'.get_header_image().'" alt="logo">';
			print '<br/>';
		}
		printf( __('Please go to %s to upload the logo.','neat'), '<a href="'.admin_url( 'themes.php?page=custom-header' ).'">'.__('HEADER','neat').'</a>' );
	}
}