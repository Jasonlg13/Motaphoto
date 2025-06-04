<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'motaphoto-style', get_template_directory_uri() . '/assets/css/front.css' );     
    wp_enqueue_script( 'motaphoto-scripts', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

add_theme_support( 'post-thumbnails' );

add_theme_support( 'title-tag' );

function register_my_menu(){
    register_nav_menu('main', "Menu principal");
    register_nav_menu('footer', "Menu pied de page");
 }
 add_action('after_setup_theme', 'register_my_menu');