<?php 
/*
*  Plugin Name: WP Paginate CPT Categories
*  Author: Michael Hicks
*  Description: Paginates Custom Post Type Categories
*  Version: 0.1
*  Author: Michael Hicks
*  Author URI: https://michaelhicks.me
*  License: GPL v2 or later
*  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*  Text Domain: CPTCAT
*/

//prefix - wpcpt

if( ! defined( 'ABSPATH' ) ) {
    return;
}

/**
 * Bootstrap Plugin
 */
require_once 'vendor/autoload.php';

/*
*  Load Scripts 
*/
add_action( 'wp_enqueue_scripts', 'wpcpt_load_plugin_scripts' );

function wpcpt_load_plugin_scripts() {
    wp_enqueue_script('vue', plugin_dir_url(__FILE__) . 'js/vue.global.prod.js');
    wp_enqueue_style('bootstrap-for-vue', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');
}

/*
*  Register CPT
*/
add_action( 'init', 'wpcpt_register_cpt' );

function wpcpt_register_cpt() {
    $args = array(

        'label'                => __('Books', 'CPTCAT'),
        'description'           => __( 'Default CPT', 'CPTCAT' ),
        'public'                => true,
        'hierarchical'          => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'show_in_admin_bar'     => false,
        'show_in_rest'          => true,
        'menu_position'         => null,
        'menu_icon'             => 'default',
        'capability_type'       => 'post',
        'capabilities'          => array(),
        'supports'              => array( 'title', 'editor', 'revisions' ),
        'taxonomies'            => array(),
        'has_archive'           => true,
        'rewrite'               => array( 'slug' => 'books' ),
        'query_var'             => true,
        'can_export'            => true,
        'delete_with_user'      => false,
        'supports' => array( 'title', 'editor', 'custom-fields' ),
        'template'              => array(),
        'template_lock'         => false,

    );

    register_post_type( 'books', $args );
}

/**
* Taxonomy: Books Categories
*/
function wpcpt_register_books_category() {

    $labels = [
        "name" => __( "Books Categories", "CPTCAT" ),
        "singular_name" => __( "Books Category", "CPTCAT" ),
    ];

    $args = [
        "label" => __( "Books Categories", "CPTCAT" ),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => [ 'slug' => 'books_category', 'with_front' => true, ],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "rest_base" => "books_category",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "show_in_quick_edit" => false,

            ];
    register_taxonomy( "books_category", [ "books" ], $args );
}
add_action( 'init', 'wpcpt_register_books_category' );

/*
*  Add custom meta box
*/
function wpcpt_add_metaboxes_to_cpt() {
    $post_type = 'books';
    add_meta_box(
        'yt_url',               
        'YouTube URL',     
        'wpcpt_youtube_url_callback',  
        $post_type,
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpcpt_add_metaboxes_to_cpt' );

/*
*   Metabox Callbacks
*/
function wpcpt_youtube_url_callback( $post ) {
    $value = get_post_meta( $post->ID, 'yt_url', true ); ?>
    <label for="yt_url">Add YouTube URL Here</label><br/>
    <input name="yt_url" id="yt_url" value="<?php echo $value; ?>" />
    <?php
}

/*
*   Save Metaboxes
*/
function wpcpt_youtube_url_savedata( $post_id ) {
    if ( array_key_exists( 'yt_url', $_POST ) ) {
        update_post_meta(
            $post_id,
            'yt_url',
            $_POST['yt_url']
        );
    }
}
add_action( 'save_post', 'wpcpt_youtube_url_savedata' );

/*
*  Register Custom Meta Box so it shows in wp rest api
*/
add_action('init', 'wpcpt_register_metaboxes_for_rest_api');

function wpcpt_register_metaboxes_for_rest_api() {
    add_action( 'rest_api_init', function () {
        register_rest_field('books', 'yt_url', array(
            'get_callback' => function( $post_arr ) {
                return get_post_meta( $post_arr['id'], 'yt_url', true );
            },
        ) );
    } );
}

/*
*  Add Shortcode
*/
add_shortcode('wpcptCat', 'wpcpt_show_cpt');

function wpcpt_show_cpt() {
    echo(
        "<div id='vue-app'><wpcpt-cat /></div>"
    );

    wp_enqueue_script('app', plugin_dir_url(__FILE__) . '/js/app.js');
}


