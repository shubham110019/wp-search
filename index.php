<?php
/* 

Plugin Name: Wp live Search

Plugin URI: https://github.com/shubham110019/wp-search.git 

Description: WordPress post type search is a feature that allows users to search for content within specific post types on a WordPress website. 

Version: 1.0 

Author: Shubham ralli

Author URI: https://github.com/shubham110019

*/




function enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
     wp_enqueue_script( 'wp-ls-js', plugins_url( '/js/wpls.js', __FILE__ ), array(), '1.0', true );
     wp_enqueue_style( 'wp-ls-css', plugins_url( '/css/wpls.css', __FILE__ ) );
     wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
     
    // wp_localize_script( 'wp-ls-js-admin', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    wp_localize_script( 'wp-ls-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );



function search_function() {
    $search_query = $_POST['search_query'];
    $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : 'post';

    $args = array(
        'post_type' => $post_type,
        's' => $search_query,
        'post_status' => 'publish',
    );
    
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            // Display search result here

            echo '<li class="search-result">';
            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            echo '</li>';
        }
    } else {
        echo '<li>No results found.</li>';
    }
    wp_reset_postdata();
    die();
}

add_action( 'wp_ajax_search_function', 'search_function' );
add_action( 'wp_ajax_nopriv_search_function', 'search_function' );



function search_form_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'post_type' => 'post',
        'placeholder' => 'search'
    ), $atts );
    $output = "<div class='wp-col-search' id='wp-col-search'>";
    $output .= '<form id="search-form" class="search-form" method="post" action="' . admin_url( 'admin-ajax.php' ) . '">';
    $output .= '<input type="text" class="search-box" name="search_query" placeholder="' . esc_attr( $atts['placeholder'] ) . '">';
    $output .= '<input type="hidden" name="action" value="search_function">';
    $output .= '<input type="hidden" name="post_type" value="' . esc_attr( $atts['post_type'] ) . '" data-post-type="' . esc_attr( $atts['post_type'] ) . '">';
    $output .= '<div class="srt-icon"><i class="fa fa-search" aria-hidden="true"></i></div>';

    $output .= '<div class="srtrigt-icon"></div>';

    $output .= '<div class="live-searchbox"><ul class="search-results"></ul></div>';
    $output .= '</form>';
    $output .= '</div>';
    return $output;
}
add_shortcode( 'search_form', 'search_form_shortcode' );


