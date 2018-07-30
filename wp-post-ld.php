<?php
/*
Plugin Name: WP Post LD JSON
Plugin URI: https://webseo.co.za
Description: Adds relevant LD JSON to post pages
Author: Web SEO Online (PTY) LTD
Author URI: https://webseo.co.za
Version: 0.0.1

	Copyright: © 2016 Web SEO Online (PTY) LTD (email : support@webseo.co.za)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


/**
* Make sure class doesn't already exist
*/

if ( ! class_exists( 'WP_Post_LD' ) ) {
	
	/**
	* Localisation
	**/
	load_plugin_textdomain( 'WP_Post_LD', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

	class WP_Post_LD {

		/**
		* constructor
		*/
		public function __construct() {
			add_filter( 'wp_head', array( $this, 'add_ld_script') );	            			
		}

		/**
		* add_ld_script
		* Checks post type and injects JSON LD data.
		**/
		public function add_ld_script() {
			global $post;

			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
			$tags = array();
			foreach ( get_the_tags( $post->ID ) as $key => $value ) {
				$tags[$key] = $value->name;
			}
			$categories = get_the_category();

			if ( is_single() && $post->post_type === "post" ) { ?>
				<script type="application/ld+json">
				{
					"@context": "http:\/\/schema.org",
					"@type": "BlogPosting",
					"mainEntityOfPage": {
						"@type": "WebPage",
						"@id": "<?php echo $post->ID ?>"
					},
					"headline": "<?php echo $post->post_title ?>",
					"image": "<?php echo $image[0] ?>",
					"award": "Best article ever written",
					"editor": "<?php echo the_author_meta( 'user_nicename' , $post->post_author ) ?>",
					"genre": "<?php echo esc_html( $categories[0]->name ) ?>",
					"keywords": "<?php echo implode( ",", $tags ) ?>",
					"publisher": {
						"@type": "Organization",
						"name": "<?php echo the_author_meta( 'user_nicename' , $post->post_author ) ?>",
						"logo": {
							"@type": "ImageObject",
							"url": ""
						}
					},
					"url": "<?php echo get_permalink( $post->ID ) ?>",
					"datePublished": "<?php echo $post->post_date ?>",
					"dateCreated": "<?php echo $post->post_date ?>",
					"dateModified": "<?php echo $post->post_modified ?>",
					"description": "<?php echo get_the_excerpt( $post->ID ) ?>",
					"articleBody": "<?php echo esc_html( $post->post_content ) ?>",
					"author": {
						"@type": "Person",
						"name": "<?php echo the_author_meta( 'user_nicename' , $post->post_author ) ?>"
					}
				}
				</script>
			<?php } 
		}

	}
	
	// finally instantiate our plugin class and add it to the set of globals
	$GLOBALS['WP_Post_LD'] = new WP_Post_LD();
}
