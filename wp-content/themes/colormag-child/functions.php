<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action( 'after_setup_theme', function () {
    // load custom translation file for the parent theme
    load_theme_textdomain( 'colormag', get_stylesheet_directory() . '/languages/colormag' );
    // load translation file for the child theme
    load_child_theme_textdomain( 'colormag-child', get_stylesheet_directory() . '/languages' );
} );