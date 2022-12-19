<?php
/**
 * Plugin Name: VOGN 1-2-1 Conversations
 * Plugin URI: https://vonsternberg.design
 * Description: One to one members conversation.
 * Version: 1.0.0
 * Author: vonsternberg.design
 * Author URI: https://vonsternberg.design
*/

include_once('inc/functions.php');

function vogn_one_to_one_conversations() {
    
    //users
    $group_members = get_users( 'role=contributor' );
    $current_user = wp_get_current_user();
    $current_user_name = $current_user->slug;
   
    //taxonomy
    $terms = get_terms([
        'taxonomy'   => 'interlocutors',
        'hide_empty' => false,
    ]);

    if( current_user_can( 'administrator' ) ) {
        //table of all meetings
        $html = '<div class="table-container">';
        $html .= '<table class="form-table">';
            $html .= '<thead>';
                $html .= '<tr>';
                    $html .= '<th class="empty"></th>';
                    foreach ($terms as $term) {
                        $html .= '<th class="partner-name">';
                            $html .= '<div class="title">' . $term->name . '</div>';
                        $html .= '</th>';
                    }
                $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
                $member_counter=1;
                foreach ($group_members as $user) {
                    $html .= '<tr>';
                        $html .= '<th class="member-name">' . $user->display_name . '</th>';
                        $interlocutors = wp_get_object_terms($user->ID, 'interlocutors');
                        $partner_counter=1;
                        foreach ($terms as $term) {
                            if ( $partner_counter != $member_counter ) {
                                $html .= '<td class="' . $user->user_nicename . ' ' . $term->slug . '">';
                                    foreach($interlocutors as $interlocutor) {
                                        if ( $term->slug === $interlocutor->slug ) {
                                            $html .= '<span>√</span>';
                                        }
                                    }
                                $html .= '</td>';
                            } else {
                                $html .= '<td class="member-equal-partner"></td>';
                            }    
                            $partner_counter++;
                        }
                    $html .= '</tr>';
                    $member_counter++;
                }
            $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        //get table in pdf format
        $html .= '<div class="download-button">';
            $html .= '<input type="button" id="getPDF" value="Als PDF herunterladen">';
        $html .= '</div>';
        
    }

    //hide in member's check list member name
    foreach ($terms as $term) {
        if ( is_page( 'meine-vier-augen-gespraeche' ) && ($term->slug === $current_user->user_nicename) ) { ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $(".related-term[data-id=<?php echo $term->term_id ?>]").addClass("hidden");
                });
            </script> <?php
        } 
    }

    $html .= do_shortcode('[frontend_admin form="2323"]');

    return $html;

}
add_shortcode( "vogn_one_to_one_conversations", "vogn_one_to_one_conversations" );