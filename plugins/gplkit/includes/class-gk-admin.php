<?php
/**
 * Admin
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GK_Admin Class
 */
class GK_Admin {

	public function __construct() {
		
		add_action( 'wp_loaded', array( $this, 'init' ) );
		
	}

	/**
	 * Hook in methods.
	 */
	public static function init() {

		add_action('admin_menu', array(__CLASS__, 'gplkit_create_menu'), 9999);
		
	}

	public static function gplkit_create_menu() {

		
		add_menu_page( 
	        __( 'GPL Kit Plugin Manager', 'gplkit' ),
	        'GPL Kit',
	        'manage_options',
	        'gplkit-plugin-manager',
	        array(__CLASS__, 'gplkit_settings_display'),
	        plugins_url( 'gplkit/assets/images/gplkit-icon.png' ),
	        56
	    );
	    add_submenu_page('gplkit-plugin-manager', 'GPL Kit Plugins', 'Plugins', 'manage_options', 'gplkit-plugin-manager' );



	}

	public static function gplkit_settings_display() { 
		
		if (!get_option('gplkit_plugins')) {
			GK_Updates::get_plugin_catalogue();
		}
	

	?>

	    <div class="wrap">

	        <?php if( isset($_GET['settings-updated']) ) { ?>
	            <div id="message" class="updated">
	                <p><strong><?php _e('Settings saved.') ?></strong></p>
	            </div>
	        <?php } ?>
	        <h1>
				GPL Kit Plugins/Extensions
			</h1>
			<?php
				if($gplkit_plugins = get_option('gplkit_plugins')) {

					$categories = array();
					foreach($gplkit_plugins as $key => $plugin) {
						$plugin_categories = $plugin['categories'];
						foreach($plugin_categories as $slug => $category) {
							if (!in_array($category, $categories)) {
								$categories[$slug] = $category;
							}
						}
					}
			?>
			<div style="clear:both;"><input type="text" id="search_plugins" name="search_plugins" placeholder="search plugins"></div>
			<ul class="subsubsub">
				<li><a class="gplfilter" data-filter="all">All</a></li>
				<?php foreach($categories as $slug => $category) {
					echo '<li> | <a class="gplfilter" data-filter=".'.$slug.'">'.$category.'</a></li>';
				}
				?>
				
			</ul>
			

			<script>

				jQuery.expr[":"].contains = jQuery.expr.createPseudo(function(arg) {
				    return function( elem ) {
				        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
				    };
				});

				jQuery(document).ready(function() {
					jQuery('#search_plugins').keyup(function() {
						var search_term = jQuery(this).val();
						jQuery('.gkititem').each(function() {
							jQuery(this).attr('style','');
							if (jQuery(this).find('.gk-plugin-title:contains("'+search_term+'")').length > 0) {
								jQuery(this).attr('style','display: inline-block;');
							}
						});
					});
				});
			</script>

	        <form method="post" action="">
                   

	                <ul id="Container" class="gkitcontainer">

	                	<?php

		                	foreach($gplkit_plugins as $key => $plugin) {
		                		$plugin_description = $plugin['description'];
		                		$plugin_category = $plugin['category'];
								$maxLength = 200;
								if (strlen($plugin_description) > $maxLength) {
								    $stringCut = substr($plugin_description, 0, $maxLength);
								    $plugin_description = substr($stringCut, 0, strrpos($stringCut, ' ')); 
								}

								if (!empty($plugin['gplkit_name'])) {
									$plugin_name = $plugin['gplkit_name'];
								} else {
									$plugin_name = $plugin['name'];
								}

		                	?>
		                		
		                        <li class="gkititem mix <?php echo implode(' ', $plugin_category); ?>">
		                            <div class="gk-plugin-wrapper">
		                                <span class="gk-plugin-title"><?php echo $plugin_name; ?></span>
		                                <div class="gk-plugin-inner"><p><?php echo strip_tags($plugin_description, '<cite>'); ?></p></div> 

	                            		<?php if ( in_array( $key, apply_filters('active_plugins', get_option('active_plugins')) ) ) { ?>
	                            			<button type="submit" data-plugin="<?php echo $key; ?>" class="button install-plugin pl-activated" value="Activated" disabled>Activated</button>

	                            		<?php } else if (file_exists(trailingslashit(WP_PLUGIN_DIR). $key) ) { ?>
											<button type="submit" data-plugin="<?php echo $key; ?>" class="button install-plugin pl-installed" value="Install" disabled>Installed</button>	
	                            		
	                            		<?php } else if ( $plugin['free'] == 1 ) { ?>
	                            			<button type="submit" data-plugin="<?php echo $key; ?>" class="button install-plugin" value="Install">Install for free</button>
	                            		
	                            		<?php } else if ( get_option( 'gplkit_plugin_manager_activated' ) != 'Activated' ) { ?>
	                            			<button type="submit" id="install" class="button install-plugin pl-licence-required" value="Install" disabled>A License key is required to install this plugin</button>

	                            		<?php } else { ?>
	                            			<button type="submit" data-plugin="<?php echo $key; ?>" class="button install-plugin" value="Install">Install</button>
	                            		<?php } ?>
		                            </div>
		                        </li>
	                    <?php 
	                		}
	                	} ?>
	                </ul>
	        </form>
	    </div>

	    <div id="gplkit-notice">
	    	<div class="gk-inner">
	    		<p><strong>Disclaimer: </strong>Woo, WooThemes and WooCommerce are all Trademarks of Automattic Inc. GPL Kit is not associated with these or endorsed by them in any way. These products are developed by WooThemes.com and it's affiliated developers and are released under the GPL license.</p>
	    	</div>
	    </div>
	    
	    <script type="text/javascript">
	        jQuery(document).ready(function($) {  
	        	$(".install-plugin").click(function(e) {
	        		var installButton = jQuery(this);
	        		e.preventDefault();

	        		var data = {
						'action': 'gplkit_install_plugin',
						'plugin': $(this).attr('data-plugin')
					};
					var spinner = $("<img src='<?php echo plugins_url( '../assets/images/ajax-loader.gif' , __FILE__ ); ?>' />").insertAfter(this);

					jQuery.post(ajaxurl, data, function(response) {
							installButton.prop('disabled', true);
				   			installButton.text(response);
				   			if (response == 'Installed') {
				   				installButton.addClass('pl-installed');
				   			}
						
					    spinner.remove();  
					});

	        	});

	            $(function(){  
	                $('#Container').mixItUp();
	            });
	    	});
	    </script>

	<?php }	
}

GK_Admin::init();