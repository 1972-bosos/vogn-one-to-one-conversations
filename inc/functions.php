<?php

/** 
 * Register taxonomy
 */
function vogn_otoc_interlocutors_taxonomy() {
    if ( !taxonomy_exists( 'interlocutors' ) ) {
        $labels = array(
            'name'                       => _x( 'Gesprächspartner', 'taxonomy general name' ),
            'singular_name'              => _x( 'Gesprächspartner', 'taxonomy singular name' ),
            'menu_name'                  => __( 'Gesprächspartner' ),
            'all_items'                  => __( 'Alle Gesprächspartner' ),
            'edit_item'                  => __( 'Gesprächspartner bearbeiten' ),
            'view_item'                  => __( 'Gesprächspartner anzeigen' ),
            'update_item'                => __( 'Gesprächspartner aktualisieren' ),
            'add_new_item'               => __( 'Neuen Gesprächspartner hinzufügen' ),
            'new_item_name'              => __( 'Name des Gesprächspartners' ),
            'parent_item'                => __( 'Eltern-Kategorie' ),
            'parent_item_colon'          => __( 'Eltern-Kategorie:' ),
            'search_items'               => __( 'Gesprächspartner suchen' ),
            'popular_items'              => __( 'Beliebter Gesprächspartner' ),
            'separate_items_with_commas' => __( 'Gesprächspartner durch Kommas trennen' ),
            'add_or_remove_items'        => __( 'Gesprächspartner hinzufügen oder entfernen' ),
            'choose_from_most_used'      => __( 'Wählen Sie aus den am häufigsten verwendeten Gesprächspartnern' ),
            'not_found'                  => __( 'Kein Gesprächspartner gefunden' ),
            'back_to_items'              => __( 'Zurück zum Gesprächspartner' )
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => false,
            'show_admin_column' => true,
            'description' => "Gesprächspartner",
            'hierarchical' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'gespraechspartner'),
        );
        register_taxonomy( 'interlocutors', 'user', $args );
    }
}
add_action( 'init', 'vogn_otoc_interlocutors_taxonomy', 0 );

/** 
 * Add taxonomy to admin menu
 */
function vogn_otoc_add_interlocutors_taxonomy_to_admin_page() {
    if ( taxonomy_exists( 'interlocutors' ) ) {
        $tax = get_taxonomy( 'interlocutors' );
	    add_users_page(
		    esc_attr( $tax->labels->menu_name ),
		    esc_attr( $tax->labels->menu_name ),
		    $tax->cap->manage_terms,
		    'edit-tags.php?taxonomy=' . $tax->name
	    );
    }
}
add_action( 'admin_menu', 'vogn_otoc_add_interlocutors_taxonomy_to_admin_page' );

/** 
 * Add term to taxonomy
 */
function vogn_otoc_add_interlocutor() {
    if ( taxonomy_exists( 'interlocutors' ) ) {
        $group_members = get_users( 'role=contributor' );
        foreach ( $group_members as $user ) {
            wp_insert_term(
                $user->display_name,
                'interlocutors',
                array(
                    'description' => $user->user_email,
                    'slug'        => $user->slug
                )
            );
        }
    }
}
add_action( 'init', 'vogn_otoc_add_interlocutor' );

/**
 * Unsets the 'posts' column and adds a 'users' column
 */
function vogn_otoc_manage_interlocutors_user_column( $columns ) {
    unset( $columns['posts'] );
    $columns['users'] = __( 'Mitglieder' );
    return $columns;
}
add_filter( 'manage_edit-interlocutors_columns', 'vogn_otoc_manage_interlocutors_user_column' );

/**
 * The ID of the term being displayed in the table.
 */
function vogn_otoc_manage_interlocutors_column( $display, $column, $term_id ) {
    if ( 'users' === $column ) {
        $term = get_term( $term_id, 'interlocutors' );
        echo $term->count;
    }
}
add_filter( 'manage_interlocutors_custom_column', 'vogn_otoc_manage_interlocutors_column', 10, 3 );

/** 
 * Load jquery scripts
*/
function vogn_otoc_scripts() {
    wp_register_script('vogn-otoc', plugins_url('/vogn-one-to-one-conversations/js/vogn-otoc.js'), array('jquery'), true);
    wp_register_script('html2pdf', plugins_url('/vogn-one-to-one-conversations/js/html2pdf.js'), array('jquery'), true);
    if ( is_page('vier-augen-gespraeche') ) {
        //format date in table
        wp_enqueue_script('vogn-otoc');
        //html to pdf
        wp_enqueue_script('jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js', array('jquery'),true);
        wp_enqueue_script('html2pdf');
    }
}
add_action( 'wp_enqueue_scripts', 'vogn_otoc_scripts' );

/** 
 * Load css style
*/
function vogn_load_style(){
    wp_enqueue_style( 'vogn-one-to-one-conversations-style', plugins_url('/vogn-one-to-one-conversations/css/style.css') );
}
add_action( 'wp_enqueue_scripts', 'vogn_load_style' );