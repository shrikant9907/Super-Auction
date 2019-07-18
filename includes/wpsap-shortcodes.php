<?php 
//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Function for show bidding counter on posts
*/
function wpsap_show_bidding_counter($atts , $content = null) {
	
	global $post, $current_user; 
	wp_get_current_user();

	$post_id = $post->ID; // Current Post ID
	$user_id = $current_user->ID; // Current User ID

	// Auction Details 
	$min_bid_amount = get_post_meta($post_id,'_wpsap_min_bid_amount',true); // Min Bid Amount
	$auction_status = get_post_meta($post_id,'_wpsap_auction_status',true); // Auction Status
	$auction_ending_date = get_post_meta($post_id,'_wpsap_auction_ending_date',true); // Auction Ending Date
	$current_bid = get_post_meta($post_id,'_wpsap_current_bid',true);  // Current Bid
	$currency = '$';
	$timezone = get_option('timezone_string'); // Get Time Zone

	if(empty($auction_ending_date)) {
		// return;
		$auction_ending_date = "No date selected.";
	}

	if(empty($min_bid_amount)) {
		$min_bid_amount = 50;
	}

	if(empty($auction_status)) {
		$auction_status = 'Open';
	}

	if(empty($timezone)) {
		$timezone = 'UTC';
	}

	if(empty($current_bid)) {
		$current_bid = 'Your are first.';
	} else {
		$current_bid = $currency.$current_bid;
	}	

	$wpsap_counter = '';

	ob_start();
	?>

	<!-- Auction Counter -->
	<div class="wpsap_counter_wr">
		<ul class="counter_ul">
			<li class="counter"><span class="years">00</span>Years</li>
			<li class="counter"><span class="months">00</span>Months</li>
			<li class="counter"><span class="weeks">00</span>Weeks</li>
			<li class="counter"><span class="days">00</span>Days</li>
			<li class="counter"><span class="hours">00</span>Hours</li>
			<li class="counter"><span class="minutes">00</span>Minutes</li>
			<li class="counter"><span class="seconds">00</span>Seconds</li>
		</ul>
	</div>	

	<!-- Auction Details  -->
	<div class="wpsap_auction_details">
		<div class="auctionend"><span>Auction End:</span><?php echo $auction_ending_date; ?></div>
		<div class="timezone"><span>Time Zone:</span><?php echo $timezone; ?></div>
		<div class="currentbid"><span>Current Bid:</span><?php echo $current_bid; ?></div>
		<div class="biddingformwr">
		<form id="wpsap_bid_form" class="bidding" name="bidding_submit" method="post" enctype="multipart/form-data">
			<input type="hidden" class="bidding_user" name="current_user_id" value="<?php echo $user_id; ?>" />
			<input type="hidden" class="min_bid_amount" name="min_bid_amount" value="<?php echo $min_bid_amount; ?>" />
			<input type="hidden" class="bidding_post_id" name="current_post_id" value="<?php echo $post_id; ?>" />
			<input type="number" name="bidding_price" value="<?php echo $min_bid_amount; ?>" />
		<input type="button" name="bidding_submit" onclick="wpsap_save_bid();" value="BID" />
		</form>
		</div>
	</div>
		
	<?php 
	$wpsap_counter = ob_get_clean();

	return $wpsap_counter; 
}
add_shortcode('bidding_counter','wpsap_show_bidding_counter');
//[bidding_counter]

/*
 * Function for show auction history
 */
function auction_history() { 

    global $post, $current_user; 
    wp_get_current_user();
    $post_id = $post->ID;
    $user_id = $current_user->ID;
    $output_history = '';
    
	$num_of_bid = get_post_meta($post_id,'_wpsap_numb_of_bid',true); // Number of bids
	$bid_user_status = get_post_meta($post_id,'_wpsap_bid_user_status',true); // Bidding User Status
	$auction_status = get_post_meta($post_id,'_wpsap_auction_status',true); // Auction Status	
	$currency = '$';
    
    if(!empty($num_of_bid) || $num_of_bid!='0') {
    ob_start(); 
    ?>
    <div class="wpsap_auction_history">
    	<div class="wpsap_heading">Auction History:</div>
		<div class="wpsap_row">
			<div class="wpsap_auction_datetime heading">Date/Time</div>
			<div class="wpsap_auction_price heading">Price</div>
			<div class="wpsap_auction_user heading">User</div>
		</div>
	    <?php while($num_of_bid>=1) { 
	   	$bid_history = get_post_meta($post_id,'_wpsap_numb_of_bid'.$num_of_bid, true );
				foreach($bid_history as $h_key => $data) {
				$$h_key = $data;
				}

		if(in_array($current_user_id,$bid_user_status)) {	
			$user_data = get_userdata($current_user_id);
			$user_name = $user_data->user_login;	 
	   	?>
	    <div class="wpsap_row">
		    <div class="wpsap_auction_datetime"><?php echo $bid_time; ?></div>
		    <div class="wpsap_auction_price"><?php echo $currency.$bidding_price; ?></div>
		    <div class="wpsap_auction_user"><?php echo $user_name; ?></div>
	    </div>
	    <?php } 
	     $num_of_bid = $num_of_bid - 1;
	    }
    	} else { ?>
	    	<div class="auction_history no_bid text-center clearfix"> There is no bid on current property.</div>
	    <?php
	    }
    	?>
    
    </div>
    <?php 
   	$output_history = ob_get_clean(); 
    return $output_history;
}

add_shortcode('show_auction_history','auction_history');
//[show_auction_history]


/*
 * Approve User 
 */
function wpsap_register_result_metabox() {
    add_meta_box( 'approve_user_metabox', __( 'Auction Result', 'hire-expert-developer' ), 'wpsap_result_metabox_cb', 'post' );
}
add_action( 'add_meta_boxes', 'wpsap_register_result_metabox', 10, 5  );
 
/**
 * Meta box display callback.
 */
function wpsap_result_metabox_cb( $post ) {

    $post_id = $post->ID;

    $output_history = '';
    
    $num_of_bid = get_post_meta($post_id,'_wpsap_numb_of_bid',true);
    $bid_user_status = get_post_meta($post_id,'_wpsap_bid_user_status',true);
    if(!empty($num_of_bid) || $num_of_bid!='0') {
    
		$output_history .= '<table style="width:100%;"><tr><td style="width:25%;"><b>Date/Time</b></td><td style="width:25%;"><b>Price</b></td><td style="width:25%;"><b>User</b></td><td style="width:24%;"><b>Approve/Deny</b></td></tr>';

		while($num_of_bid>=1) { 
			$bid_history = get_post_meta($post_id,'_wpsap_numb_of_bid'.$num_of_bid, true );
				foreach($bid_history as $h_key => $data) {
				$$h_key = $data;
				} 

			$output_history .= '<tr class="h_row"><td style="width:25%;">'.$bid_time.'</td><td style="width:25%;">$'.$bidding_price.'</td><td style="width:25%;">'.$current_user_id.'</td><td style="width:23%;"><input type="checkbox" name="bid_user_status[]" ';

			if(is_array($bid_user_status)) { 
				foreach($bid_user_status as $status) {
					ob_start();
					checked( $status, $current_user_id );
					$output_history .= ob_get_clean();
				}
			}

			$output_history .= 'value="'.$current_user_id.'" /></td></tr>';

			$num_of_bid = $num_of_bid - 1;
		}

		$output_history .= '</table>';
    
    } else {
        $output_history .= '<div class="auction_history no_bid text-center clearfix"> There is no bid on current property.</div>';
    }
    echo $output_history;
}
 
/**
 * Save meta box content.
 */
function wpsap_save_metabox( $post_id ) {

	// if ( ! check_ajax_referer( 'wpsap_setting_nonce_action', 'wpsap_security' ) ) {
	// 	return;
	// }
   
    if ( isset( $_POST['bid_user_status'] ) ) {
        update_post_meta( $post_id, '_wpsap_bid_user_status', $_POST['bid_user_status'] );
    } else {
    	update_post_meta( $post_id, '_wpsap_bid_user_status', '' );
    }
}
add_action( 'save_post', 'wpsap_save_metabox' );


