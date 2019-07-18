<?php

/*
 * Ajax Save bid
 */
function wpsap_save_bid_fn() {
         
    parse_str($_POST['form_data'],$form_data);
    foreach($form_data as $f_key => $data) {
        $$f_key = $data;
    }   
    
    $bid_value_arr = $form_data;
    $bid_value_arr['bid_time'] = current_time( 'mysql' );

    $user_info = get_userdata($current_user_id);
    $user_name = $user_info->user_login;
    $current_user_role = implode(', ', $user_info->roles);

    $num_of_bid = get_post_meta($current_post_id,'_wpsap_numb_of_bid',true);
    $user_bid_post_id = get_user_meta($current_user_id,'_wpsap_user_bid_post_id',true);

    if($num_of_bid=='') {
        $num_of_bid = '1';
    } else {
        $num_of_bid = $num_of_bid + 1; 
    }
      
    if((empty($user_bid_post_id) || !in_array($current_post_id,$user_bid_post_id)) && ($current_user_role!='administrator')) {
        $user_bid_post_id[] = $current_post_id;
            if($min_bid_amount<=$bidding_price) {
                update_post_meta($current_post_id,'_wpsap_current_bid',$bidding_price);      
                update_post_meta($current_post_id,'_wpsap_numb_of_bid'.$num_of_bid, $bid_value_arr );
                update_post_meta($current_post_id,'_wpsap_numb_of_bid',$num_of_bid);
                update_user_meta($current_user_id,'_wpsap_user_bid_post_id',$user_bid_post_id);
                echo '<div class="wpsap_alert_success">Bid successfully placed.</div>';
            } else {
                echo "<div class='wpsap_alert_fail'>You can not place a less then ".$min_bid_amount.'</div>';
            }
    } else if($current_user_role=='administrator'){
        echo '<div class="wpsap_alert_fail">Admin can not bid.</div>';    
    } else {
        echo '<div class="wpsap_alert_fail">You have already bid on this property.</div>';
    }

    wp_die();
}
add_action('wp_ajax_wpsap_save_bid_action', 'wpsap_save_bid_fn');

