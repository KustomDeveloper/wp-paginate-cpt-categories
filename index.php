<?php 
/*
*  Plugin Name: Vue Boilerplate
*  Author: Michael Hicks
*  Description: Vue Boilerplate
*  Version: 0.1
*/

/**
 * Bootstrap Plugin
 */
require_once 'vendor/autoload.php';

/*
*  Add Env Variables 
*/
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/*
*  Load Scripts 
*/
function load_plugin_scripts() {
    wp_enqueue_script('vue', plugin_dir_url(__FILE__) . 'js/vue.global.prod.js');
    wp_enqueue_style('bootstrap-for-vue', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');
}
add_action( 'wp_enqueue_scripts', 'load_plugin_scripts' );

/*
*  Add Shortcode
*/
add_shortcode('weatherApi' , 'weather_api');
function weather_api() {

    //get api key
    $api_key = $_ENV['WEATHER_API'];

    //get blog title
    $blog_title = get_bloginfo( 'name' );

    echo(
        "<div id='vue-app'><weather-api apikey='$api_key' /></div>"
    );

    wp_enqueue_script('app', plugin_dir_url(__FILE__) . '/js/app.js');
}

