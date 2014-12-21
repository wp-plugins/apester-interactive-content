<?php
/**
 * @package Qmerce
 */
/*
Plugin Name: Apester Interactive Content
Plugin URI: http://apester.co/
Description: The Apester Interactive Content plugin allows anyone to easily and freely create, embed and share interactive, playful and related content items (polls, trivia, etc.) into posts and articles, in a matter of seconds.
If you wish for better engagement, virality, circulation, native advertisement campaigns and monetization results, you came to the right place!
Version: 1.7
Author: Apester
Author URI: http://apester.com/
License: GPLv2 or later
Text Domain: apester
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'QMERCE_MINIMUM_WORDPRESS_VERSION', '3.8' );
define( 'QMERCE_VERSION', '1.7' );
define( 'QMERCE_INTERACTION_BASEURL', 'http://interaction.qmerce.com' );
define( 'QMERCE_EDITOR_BASEURL', 'http://editor.qmerce.com' );
define( 'QMERCE_USER_SERVICE', 'http://users.qmerce.com' );
define( 'QMERCE_RENDERER_BASEURL', 'http://renderer.qmerce.com' );
define( 'QMERCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'QMERCE__PLUGIN_FILE', __FILE__ );

if ( !function_exists( 'add_action') ) {
    throw new Exception( 'Can\'t call plugin directly' );
}

if ( is_admin() ) {
    require_once( QMERCE_PLUGIN_DIR . 'inc/qmerce-settings.class.php' );
    require_once( QMERCE_PLUGIN_DIR . 'inc/qmerce-admin-box.class.php' );
}

require_once( QMERCE_PLUGIN_DIR . 'inc/qmerce-widget.php' );
require_once( QMERCE_PLUGIN_DIR . 'inc/qmerce-automation.php' );
require_once( QMERCE_PLUGIN_DIR . 'inc/qmerce-tag-composer.php' );
require_once( QMERCE_PLUGIN_DIR . 'inc/qmerce-shortcodes.php' );

add_action( 'wp_enqueue_scripts', 'qmerce_add_sdk' );
add_action('widgets_init', 'qmerce_register_widgets');
add_filter('the_content', array(new QmerceAutomation(), 'renderHtml'));

// Add SDK
function qmerce_add_sdk() {
    $configuration = array(
        'rendererBaseUrl' => QMERCE_RENDERER_BASEURL,
    );
    wp_register_script( 'qmerce_js_sdk', plugins_url( '/public/js/qmerce-sdk.js', QMERCE__PLUGIN_FILE ) );
    wp_enqueue_script( 'qmerce_js_sdk' );
    wp_localize_script( 'qmerce_js_sdk', 'configuration', $configuration);
}

function qmerce_register_widgets() {
    register_widget( 'QmerceWidget' );
}
