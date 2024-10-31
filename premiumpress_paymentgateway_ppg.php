<?php
/*
Plugin Name: PayPro Global Payment Gateway for PremiumPress
Plugin URI: http://www.korablev.org/paypro-global-payment-gateway/
Description: This is the PayPro Global payment plugin for PremiumPress themes. ** NOTE ** Once actived simply check your payments list, the new one will be added.
Version: 1.0.1
Author: Alexey Korablev
Author URI: http://www.korablev.org
License: GPLv2 or later
*/

function kf_encrypt($str, $key)
{
	 $data = "";
	 $td = mcrypt_module_open('des', '', 'ecb', '');
	 $ckey = $key;
	 $iv = $key;
	 mcrypt_generic_init($td, $ckey, $iv);
	 $data = mcrypt_generic($td, $str);
	 mcrypt_generic_deinit($td);
	 mcrypt_module_close($td);
	 return $data;
}

function gateway_ppg($data){

global  $userdata; get_currentuserinfo();
$ADD = explode("**",get_user_meta($userdata->ID, 'jabber', true));
$total_price_ppg=str_replace(',','',$data['price']['total']);
$total_price_ppg=str_replace('.',',',$total_price_ppg);
$price_config = "price=".$total_price_ppg."-USD^^^name=".get_option('gateway_ppg_product_name')."^^^desc=".get_option('gateway_ppg_product_desc');
$key = get_option('gateway_ppg_product_key');
if (trim($key)=='') {
  $key='ABCDEFGH';
  echo '<br><p style="padding:6px; color:white;background:red; margin-top:10px;"><b>ERROR: PRODUCT KEY IS MISSING</b></p>';
}
if (trim(get_option('gateway_ppg_product'))==''){
  echo '<br><p style="padding:6px; color:white;background:red; margin-top:10px;"><b>ERROR: PRODUCT ID IS MISSING</b></p>';
}
$hash_value = base64_encode(kf_encrypt($price_config, $key));
if (get_option('gateway_ppg_testmode')=='yes'){$testmode='&testmode=1';}else{$testmode='';}
$orderpage_ppg="https://secure.payproglobal.com/orderpage.aspx?products=".get_option('gateway_ppg_product').'&hash='.$hash_value.$testmode;

?>
<form  method="POST" action="<?php echo $orderpage_ppg; ?>" name="ppg_gateway">
<input type="hidden" name="templateid" value="<?php echo get_option('gateway_ppg_template_id'); ?>"/>
<input type="hidden" name="Coupon" value="<?php echo get_option('gateway_ppg_product_coupon'); ?>"/>
<input type="hidden" name="Company" VALUE="<?php echo get_user_meta($userdata->ID, 'company', true); ?>">
<input type="hidden" name="ProductName" value="<?php echo get_option('gateway_ppg_product_name'); ?>"/>
<input type="hidden" name="ProductDesc" value="<?php echo get_option('gateway_ppg_product_desc'); ?>"/>
<input TYPE="hidden" NAME="FirstName" VALUE="<?php echo get_user_meta($userdata->ID, 'first_name', true); ?>">
<input TYPE="hidden" NAME="LastName" VALUE="<?php echo get_user_meta($userdata->ID, 'last_name', true); ?>">
<input type="hidden" name="Phone" value="<?php echo $ADD[5]; ?>"/>
<input type="hidden" name="Zip" value="<?php echo $ADD[4]; ?>"/>
<input type="hidden" name="Address1" value="<?php echo $ADD[2]; ?>"/>
<input type="hidden" name="City" value="<?php echo $ADD[3]; ?>"/>
<input type="hidden" name="State" value="<?php echo $ADD[1]; ?>"/>
<input type="hidden" name="Country" value="<?php echo $ADD[0]; ?>"/>
<input type="hidden" name="Email" value="<?php echo $userdata->user_email; ?>"/>
<INPUT TYPE="hidden" NAME="CustomField1" VALUE="<?php echo $data['orderid']; ?>">
<?php echo MakePayButton('javascript:document.ppg_gateway.submit();'); ?>
</FORM>

<?php }

function ppg_new_gateway($gateways){

$nId = count($gateways)+52;
$gateways[$nId]['name'] 		= "PayPro Global (dynamic settings)"; // STRING - NAME OF YOUR GATEWAY
$gateways[$nId]['logo'] 		= "https://www.payproglobal.com/img/banners_buttons/BuyNow_buttons_gif/BuyNow_button_09.gif"; // STRING - http:// link to your logo file
$gateways[$nId]['website'] 		= "http://www.payproglobal.com"; // STRING - http:// link to the merchants website for more details
$gateways[$nId]['function'] 	= "gateway_ppg"; // STRING - name of your callback function
$gateways[$nId]['callback'] 	= "yes"; // YES/NO - FOR DISPLAY PURPOSES ONLY
$gateways[$nId]['fields'] 	= array(
'1' => array('name' => 'Enable Gateway', 'type' => 'listbox','fieldname' => 'gateway_ppg','list' => array('yes'=>'Enable','no'=>'Disable') ),
'2' => array('name' => 'Test Mode', 'type' => 'listbox','fieldname' => 'gateway_ppg_testmode','list' => array('yes'=>'Enable','no'=>'Disable') ),
'3' => array('name' => 'Product ID *', 'type' => 'text', 'fieldname' => 'gateway_ppg_product'),
'4' => array('name' => 'Secret Key *', 'type' => 'text', 'fieldname' => 'gateway_ppg_product_key'),
'5' => array('name' => 'Template ID', 'type' => 'text', 'fieldname' => 'gateway_ppg_template_id'),
'6' => array('name' => 'Product Name', 'type' => 'text', 'fieldname' => 'gateway_ppg_product_name'),
'7' => array('name' => 'Product Description', 'type' => 'text', 'fieldname' => 'gateway_ppg_product_desc'),
'8' => array('name' => 'Discount Coupon', 'type' => 'text', 'fieldname' => 'gateway_ppg_product_coupon'),
'9' => array('name' => 'Display Name', 'type' => 'text', 'fieldname' => 'gateway_ppg_name', 'default' => 'Pay Now via PayPro Global'),
'10' => array('name' => 'Display Icon', 'type' => 'text', 'fieldname' => 'gateway_ppg_icon', 'default' =>'https://www.payproglobal.com/img/banners_buttons/BuyNow_buttons_gif/BuyNow_button_22.gif' ),
'11' => array('name' => 'Callback URL', 'type' => 'text', 'fieldname' => 'gateway_ppg_callback' )
);

return $gateways;

}
add_action('premiumpress_admin_payments_gateways','ppg_new_gateway');

function gateway_ppg_callback($orderID){

global $PPTPayment;

$orderID=$_POST['CUSTOM_FIELD1'];
if ($_POST['IS_DELAYED_PAYMENT']==0){$OrderStatus=5;}else{$OrderStatus=0;}
// 5-payment received, 3-completed, 8-refund, 0-waitig, 6- payment failed
if (isset($orderID)){
    $PPTPayment->UpdateOrderStatus($OrderStatus, $orderID);
    return "thankyou";
	}else{
		return ""; // LEAVE FOR SYSTEM TO PICK  UP
	}
}

add_action('premiumpress_callback_paymentstatus','gateway_ppg_callback');

?>