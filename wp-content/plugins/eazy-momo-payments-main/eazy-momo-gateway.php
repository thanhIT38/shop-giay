<?php
/*
 * Plugin Name: EazyMoMo
 * Plugin URI: #
 * Description: Integrate Mobile Money Payments on your website for faster checkout.
 * Author: Ashime A. Amandong
 * Author URI: aashime.amandong@gmail.com
 * Version: 1.0.3
 *
 */

/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'eazymomo_add_gateway_class' );
function eazymomo_add_gateway_class( $gateways ) {
	$gateways[] = 'WC_EazyMoMo_Gateway'; // your class name is here
	return $gateways;
}
 
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'eazymomo_add_plugin_page_settings_link');
function eazymomo_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'admin.php?page=wc-settings&tab=checkout&section=eazymomo' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}
/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'eazymomo_init_gateway_class' );
function eazymomo_init_gateway_class() {
 
	class WC_EazyMoMo_Gateway extends WC_Payment_Gateway {
 
 		/**
 		 * Class constructor, more about it in Step 3
 		 */
 		public function __construct() {
            $this->id = 'eazymomo'; // payment gateway plugin ID
            $this->icon = 'data:image/png;base64,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'; // URL of the icon that will be displayed on checkout page near your gateway name
            $this->has_fields = true; // in case you need a custom credit card form
            $this->method_title = 'EazyMoMo Gateway';
            $this->method_description = 'Plugin to Integrate Mobile Money Payments on your website'; // will be displayed on the options page
        
            // gateways can support subscriptions, refunds, saved payment methods,
            // but in this tutorial we begin with simple payments
            $this->supports = array(
                'products'
            );
        
            // Method with all the options fields
            $this->init_form_fields();
        
            // Load the settings.
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option( 'enabled' );
            $this->momo_email = $this->get_option( 'momo_email' );
            
            // This action hook saves the settings
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
        
            // You can also register a webhook here
            // add_action( 'woocommerce_api_{webhook name}', array( $this, 'webhook' ) );

 		}
 
		/**
 		 * Plugin options, we deal with it in Step 3 too
 		 */
 		public function init_form_fields(){
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable EazyMoMo Gateway',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'no'
                ),
                'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'description' => 'This controls the title which the user sees during checkout.',
                    'default'     => 'Mobile Money',
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => 'Description',
                    'type'        => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default'     => 'Pay using Mobile Money payment gateway.',
                ),
                'momo_email' => array(
                    'title'       => 'Mobile Money Email',
                    'description' => 'Email used for Mobile Money developers Portal',
                    'type'        => 'email',
                    'placeholder' => 'johndoe@gmail.com',
                    'desc_tip'    => true,
                )
                // 'license_key' => array(
                //     'title'       => 'EazyMoMo license key',
                //     'description' => 'Your Activation Code',
                //     'type'        => 'email',
                //     'placeholder' => 'johndoe@gmail.com',
                //     'desc_tip'    => true,
                // )
            );
 
	 	}
 
		/**
		 * You will need it if you want your custom credit card form, Step 4 is about it
		 */
		public function payment_fields() {
             
            // ok, let's display some description before the payment form
            if ( $this->description ) {
                // you can instructions for test mode, I mean test card numbers etc.
            
                    $this->description .= '<br> <a style="text-decoration:none; cursor:text;"> Dial <strong>*126#</strong> on your mobile phone to approve the payment.</a>';
                    $this->description  = trim( $this->description );
                
                // display the description with <p> tags etc.
                echo wpautop( wp_kses_post( $this->description ) );
            }
        
            // I will echo() the form, but you can close PHP tags and print it directly in HTML
            echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
        
            // Add this action hook if you want your custom payment gateway to support it
            do_action( 'woocommerce_credit_card_form_start', $this->id );
        
            // I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
            echo '<div class="form-row form-row-wide"><label>Enter Mobile Money Number <span class="required">*</span></label>
                    <input name="eazymomo_number" id="eazymomo_number" type="text" autocomplete="off">
                </div>
                <div class="clear"></div>';
        
            do_action( 'woocommerce_credit_card_form_end', $this->id );
        
            echo '<div class="clear"></div></fieldset>';
 
		}
 
		/*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
	 	public function payment_scripts() {

	 	}
 
		/*
 		 * Fields validation, more in Step 5
		 */
		public function validate_fields() {
 
            if( empty( $_POST[ 'eazymomo_number' ]) ) {
                    wc_add_notice(  'Mobile Money Number is required!', 'error' );
                    return false;
                }
                return true;
		}
 
		/*
		 * We're processing the payments here, everything about it is in Step 5
		 */
		public function process_payment( $order_id ) {
            global $woocommerce;

            // we need it to get any order detailes
            $order = wc_get_order( $order_id );    
        
            /*
            * Array with parameters for API interaction
            */
            $_amount  = $order->get_total() - 0;
            $_tel     = $_POST[ 'eazymomo_number' ];
            $_email   = $this->momo_email;
            
            /*
            * Your API interaction could be built with curl 
            */
            $momoUrl = 'https://developer.mtn.cm/OnlineMomoWeb/faces/transaction/transactionRequest.xhtml?idbouton=2&typebouton=PAIE&_amount='.$_amount.'&_tel='.$_tel.'&_clP=&_email='.$_email.'&submit.x=113&submit.y=42';
            $returned_content_json = $this->get_data($momoUrl);

            $response = json_decode($returned_content_json, true);   
        
            if( !is_wp_error( $response ) ) {
        
                // it could be different depending on your payment processor
                if ( $response['StatusCode'] == '01' ) {
        
                    // we received the payment
                    $order->payment_complete();
                    $order->reduce_order_stock();
        
                    // some notes to customer (replace true with false to make it private)
                    $order->add_order_note( 'Hey, You have successfully completed your order! Thank you!', true );
        
                    // Empty cart
                    $woocommerce->cart->empty_cart();
        
                    // Redirect to the thank you page
                    return array(
                        'result' => 'success',
                        'redirect' => $this->get_return_url( $order )
                    );
        
                } else {
                    wc_add_notice(  'Error Processing Payment. Please try again.', 'error' );
                    return;
                }
        
            } else {
                wc_add_notice(  'Connection error Connecting.', 'error' );
                return;
            }
 
	 	}
 
		/*
		 * In case you need a webhook, like PayPal IPN etc
		 */
		public function webhook() {
 
 
         }
         public function get_data($url) {
            $ch = curl_init();
            $timeout = 120;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            // curl_close($ch);
            return $data;
        }
 	}
}