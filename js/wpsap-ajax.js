/*
 * Save Bidding By Ajax 
 */

function wpsap_save_bid(){

   var bidform = jQuery('#wpsap_bid_form').serialize();
    jQuery('.wpsap_alert_fail').remove();
    jQuery('.wpsap_alert_success').remove();
    
    jQuery.ajax({ 
        type: 'POST',   
        url: LOCOBJ.ajax_url, 
        data: { 
            "action": "wpsap_save_bid_action", 
            "form_data":bidform 
        }, 
        success: function(data){ 
            // alert(data);
            if(data!='0') {
                jQuery('.biddingformwr').after(data);
            } else {
                jQuery('.biddingformwr').after('<div class="wpsap_alert_fail">User registration required for bidding..</div>');
            }
        }	
        
    });
    
}