<?php 
//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class for registering a new settings page under Settings.
 */
class WpsapOptionsPage {

    /**
    * Holds the values to be used in the fields callbacks
    */
    private $options;
 
    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'wpsap_add_auction_setting_page' ) );
        add_action('admin_init', array( $this, 'wpsap_register_settings') );
    }

    /**
    * Registers Auction settings page under Settings.
    */
    public function wpsap_add_auction_setting_page() {

        add_options_page(
            __( 'Auction Settings Page', 'hire-expert-developer' ), // Page Title
            __( 'Auction Settings', 'hire-expert-developer' ), // Menu Title
            'manage_options', // Capablilties
            'wpsap_settings_page', // Page slug
            array(
                $this,
                'wpsap_settings_page_cb' // callback function
            )
        );
    }
   
    /**
     * Settings page display callback.
     */ 
    public function wpsap_settings_page_cb() {

        // Set options class property
        $this->options = get_option( 'wpsap_setting_option' );
       
        ?>
        <div id="wpsap_settings_id" class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        
        <form method="post" action="options.php">

          <?php 
            wp_nonce_field( 'wpsap_setting_nonce_action', 'wpsap_security' ); 
            settings_fields( 'wpsap_section_group' ); // Settubg Group
            do_settings_sections( 'wpsap_settings_page' ); // Page slug
            submit_button(); 
          ?>
          </form>
        </div>


        <?php  
      
    }

    /**
     * Register and Add Settings.
     */
    public function wpsap_register_settings() {

       //Register Settings.    
        register_setting(
            'wpsap_section_group',  // settings section
            'wpsap_setting_option' // setting name
        );
        
        add_settings_section(
        'wpsap_settings_section_id', // Section ID
        'Auction Settings', // Title
        '', // Callback
        'wpsap_settings_page' // Page Name wpsap_settings_page
        );  

        add_settings_field(
        'wpsap_show_hide_auction', // ID
        'Show/Hide Auction', // Title 
        array( $this, 'wpsap_show_hide_auction_cb' ), // Callback
        'wpsap_settings_page', // Page Name wpsap_settings_page
        'wpsap_settings_section_id' // Section ID          
        );      

        add_settings_field(
        'wpsap_select_post_type', // ID
        'Select Post Type', // Title 
        array( $this, 'wpsap_select_post_type_cb' ), // Callback
        'wpsap_settings_page', // Page Name wpsap_settings_page
        'wpsap_settings_section_id' // Section ID          
        );
        
    }

    /** 
     * Get the settings option array and print one of its values
     */
    

    public function wpsap_show_hide_auction_cb()
    {   
        if(isset( $this->options['wpsap_show_hide_auction'] )) {
            $wpsap_show_hide_auction = esc_attr( $this->options['wpsap_show_hide_auction']);
        } else {
            $wpsap_show_hide_auction = 1;
        } 
        ?>
        <select id="wpsap_show_hide_auction" name="wpsap_setting_option[wpsap_show_hide_auction]">
            <option <?php selected( $wpsap_show_hide_auction , 1 ); ?> value="1">Yes</option>
            <option <?php selected( $wpsap_show_hide_auction , 0 ); ?> value="0">No</option>
        </select>
        <p class="description">Show/Hide the auction functionality form the theme.</p>
<?php 
    }

     public function wpsap_select_post_type_cb()
    {   
        if(isset( $this->options['wpsap_select_post_type'] )) {
            $wpsap_select_post_type = esc_attr( $this->options['wpsap_select_post_type']);
        } else {
            $wpsap_select_post_type = 1;
        } 
        ?>
        <select id="wpsap_select_post_type" name="wpsap_setting_option[wpsap_select_post_type]">
              <?php $selected = $options['post_type'];  
                $args = array(
                'public'   => true 
                );
                $output = 'names';
                $operator = 'and'; 
                $post_types = get_post_types( $args, $output, $operator ); 
                
                $exclude = array('page','attachment');
                foreach ( $post_types  as $post_type ) {
                    if(!in_array($post_type,$exclude)) {
                    ?>
                    <option value="<?php echo $post_type; ?>" <?php selected( $wpsap_select_post_type, $post_type ); ?> ><?php echo $post_type; ?></option>
                    <?php }                                         
                 }
              ?>
            </select>
        <p class="description">Select the post type for adding auction functionality. Default: post</p>
<?php 
    }
    

}
 
$WpsapOptionsPage = new WpsapOptionsPage();

