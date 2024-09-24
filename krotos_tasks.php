<?php
/*
Plugin Name: Task Manager by MaKrotos
Description: Task Manager by MaKrotos
Version: 1.0
Author: MaKrotos
*/
require plugin_dir_path(__FILE__) . 'tasks_page_content.php';

function create_task_post_type() {
    register_post_type( 'tasks',
        array(
            'labels' => array(
                'name' => __( 'Tasks' ),
                'singular_name' => __( 'Task' )
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}
add_action( 'init', 'create_task_post_type' );

function add_tasks_menu_item() {
    add_menu_page(
        'Tasks', // page title
        'Tasks', // menu title
        'manage_options', // capability
        'tasks', // menu slug
        'tasks_page_content', // function that handles the page content
        'dashicons-list-view', // icon URL
        6 // position
    );
}


function enqueue_task_manager_scripts() {
    wp_enqueue_script( 'task-manager-script', plugin_dir_url(__FILE__) . 'js/script.js' );
}

add_action( 'admin_menu', 'add_tasks_menu_item' );
add_action( 'admin_enqueue_scripts', 'enqueue_task_manager_scripts' );

?>
