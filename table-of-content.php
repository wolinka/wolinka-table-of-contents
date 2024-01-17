<?php 
/*
Plugin Name: Wolinka Table Of Contents
Description: Kullanıcı dostu sayfa içeriğinden oluşan bir içindekiler tablosu oluşturmak ve göstermek için bir yol ekler. Kullanımı: [toc]
Version: 1.0
Author: Wolinka
Author URI: https://wolinka.com.tr
*/

function wlnk_table_of_contents() {
	ob_start();
    ?>
    	<div id="table-of-contents"></div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'toc', 'wlnk_table_of_contents' );

function wlnk_table_of_contents_css() {
    global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'toc' ) ) {
		?>
			<style type="text/css">
				#table-of-contents {
					padding: 45px 30px 30px 30px;
					margin-bottom: 20px;
					background-color: #f5f5f5;
				}

				#table-of-contents ul {
					list-style: none;
					margin-bottom: 0;
				}

				#table-of-contents ul .level-1 {
					margin-left: 0;
				}

				#table-of-contents ul li a {
					color: #1b4582;
				}

				#table-of-contents ul li a:hover {
					color: #cf2e2e;
				}				

			</style>
		<?php
	}
}
add_action( 'wp_head', 'wlnk_table_of_contents_css' );

function wlnk_table_of_content_javascript() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'toc' ) ) {
		?>
		    <script>
		        jQuery(function($) {
		            $(document).ready(function() {
		                function removeTurkishChars(string) {
		                    var TurkishChars = { "ş": "s", "Ş": "S", "ı": "i", "İ": "I", "ç": "c", "Ç": "C", "ğ": "g", "Ğ": "G", "ü": "u", "Ü": "U", "ö": "o", "Ö": "O" };
		                    string = string.replace(/[şŞıİçÇğĞüÜöÖ]/g, function(letter){ return TurkishChars[letter]; });
		                    return string;
		                }
		                var ToC = "<nav role='navigation' class='table-of-contents'><h2>İçindekiler</h2><ul>";
		                var newLine, el, title, link;
		                $("#content h2, #content h3").each(function(i) {
		                    el = $(this);
		                    title = el.text();
		                    link = removeTurkishChars(title);
		                    link = link.replace(/[^a-zA-Z0-9]/g, ' ') // replace any character that is not alphanumeric with space
		                    link = link.replace(/\s+/g, '-') // replace any space with -
		                    link = link.toLowerCase().replace(/-+$/, '') // remove trailing dash
		                    link = "#" + link;
		                    el.attr("id", link.slice(1));
		                    if(el.is("h2")){
		                      newLine = "<li class='level-1'><a href='" + link + "'>" + title + "</a></li>";
		                    }
		                    else{
		                      newLine = "<li class='level-2'><a href='" + link + "'>" + title + "</a></li>";
		                    }
		                    ToC += newLine;
		                });
		                ToC += "</ul></nav>";
		                $("#table-of-contents").prepend(ToC);

		                // Add smooth scroll effect to links in Table of Contents
		                $("nav.table-of-contents a").on("click", function(event) {
		                    event.preventDefault();
		                    var target = $(this.hash);
		                    $("html, body").animate({
		                        scrollTop: target.offset().top - 110 // account for 110px offset
		                    }, 1000);
		                });
		            });
		        });
		    </script>
		<?php
	}
}
add_action('wp_footer','wlnk_table_of_content_javascript',999);