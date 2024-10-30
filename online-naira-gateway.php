<?php


define('CPNC_TD','online-naira');

class APP_OnlineNaira_Gateway extends APP_Gateway{

	public function __construct() {
		parent::__construct( CPNC_TD, array(
			'dropdown' => __( 'OnlineNaira', CPNC_TD ),
			'admin' => __( 'OnlineNaira', CPNC_TD ),
		) );

	}


	/**
	 * Builds the administration settings form
	 * @return array scbForms style form
	 */
	public function form() {

		$form_values = array(

			array(  'title' => __('User name', CPNC_TD),
							'name'=>'username',
							'desc' => sprintf( __("Your OnlineNiara Username.", CPNC_TD), 'https://onlinenaira.com/' ),
							'tip'  => __('Enter your OnlineNaira username. This is where your money gets sent.', CPNC_TD),
							'css'  => 'min-width:250px;',
							'type' => 'text',
							'req'  => '',
							'min'  => '',
							'std'  => '',
							'vis'  => ''),

			array(  'title' => __('API Key', CPNC_TD),
							'name'=>'apikey',
							'desc' => sprintf( __("Ask OnlineNaira for your apikey.", CPNC_TD), 'https://onlinenaira.com/' ),
							'tip' => __('Enter your OnlineNaira API key.', CPNC_TD),
							'css' => 'min-width:250px;',
							'type' => 'text',
							'req' => '',
							'min' => '',
							'std' => '',
							'vis' => ''),

		);

		$return_array = array(
			"title" => __( 'OnlineNaira Options', CPNC_TD ),
			"fields" => $form_values
		);

		return $return_array;

	}

	/**
	 * Processes a Bank Transfer Order to display
	 * instructions to the user
	 * @url https://onlinenaira.com/api.htm
	 * @param  APP_Order $order   Order to display information for
	 * @param  array $options     User entered options
	 * @return void
	 */
	public function process( $order, $options ) {


		$return_url = $order->get_return_url();
		$cancel_url = $order->get_cancel_url();
		$return_url = add_query_arg( array('success' => '1'), $return_url );
		$cancel_url = add_query_arg( array('cancel' => '1'), $cancel_url );
		
		$currency = $order->get_currency();
		$order_id = $order->get_ID();
		$my_order = appthemes_get_order( $order_id );


		if ( $my_order->get_gateway() != CPNC_TD )
			return;

		// get the username

		$username = $options["username"];
		$apikey = $options["apikey"];

		$status = $my_order->get_status();


		switch ($status){
			case APPTHEMES_ORDER_PENDING:
				if( isset($_GET["success"]) && $_GET["success"] == 1 ){
					$order->complete();
				}elseif( isset($_GET["cancel"]) && $_GET["cancel"] == 1 ){
					return;
				}else{

					$post_url = 'https://onlinenaira.com/process.htm';
?>
					<form name="paymentform" method="post" action="<?php echo esc_url( $post_url ) ?>" accept-charset="utf-8">
						<input type="hidden" name="member" value="<?php echo esc_attr( $username ); ?>"/>
						<input type="hidden" name="action" value="payment"/>
						<input type="hidden" name="product" value="<?php echo esc_attr( $order_id ); ?>" />
						<input type="hidden" name="price" value="<?php echo esc_attr( $order->get_total() ); ?>" />
						<input type="hidden" name="apikey" value="<?php echo esc_attr( $apikey ); ?>" />
						<input type="hidden" name="item_quantity" value="1" />
						<input type="hidden" name="ureturn" value="<?php echo esc_url( $return_url ); ?>"/>
						<input type="hidden" name="ucancel" value="<?php echo esc_url( $cancel_url ); ?>"/>

						<center><input type="submit" class="btn_orange" value="<?php _e('Continue &rsaquo;&rsaquo;', CPNC_TD); ?>" /></center>

						<script type="text/javascript"> setTimeout("document.paymentform.submit();", 500); </script>

					</form>
<?php					
				}
				break;
		}
	}
}

appthemes_register_gateway( 'APP_OnlineNaira_Gateway' );


