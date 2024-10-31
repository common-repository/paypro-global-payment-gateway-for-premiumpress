=== PayPro Global Payment Gateway for PremiumPress ===
Contributors: Alexey Korablev
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RKEAJ9SJXQF5U
Tags: payment, gateway, payproglobal, ppg, premiumpress, shopperpress
Requires at least: 2.9
Tested up to: 3.4
Stable tag: 1.0.1
License: GPLv2 or later

Enables the PayPro Global reseller as the payment gateway for all PremiumPress themes. PayPro Global is the reseler of electronic goods.

== Description ==

Enables the PayPro Global reseller as the payment gateway for all PremiumPress themes. Allows to use
PayPro Global reseller as the payment method for premium WordPress shops to process orders.

**Requirements:**

*   WordPress 2.9++
*   One of the PremiumPress Themes
*   PHP 5.1.xx
*   MySql 5.1.xx
*   PHP 4.xx and MySql 4.xx not supported

= Instruction =

1. Logon to your PPG account
2. Go to Products and select the product you want to use with dynamic settings
3. Scroll down to 'Dynamic settings' options and check the Allow box
4. Enable the 'Use Hash' option
5. Enter the 'Secret Key' value of 8 symbols
6. Point 'Instant Payment Notification URL' to the callback page of your site
7. Optionally - specify the value for 'ThankYou Page Redirect URL' to clear the basket (example: http://yoursite.com/index.php?emptyCart=1 )
8. Save your product settings.
9. Use your PPG 'Product ID' and 'Secret Key' values to configure the gateway.

= Features =

* Automatically adds the new payment gateway to the payments list
* Allows to use product dynamic settings provided by PPG payment system
* Performs the after-payment callback which sets the order status automatically
* Supports the test ordering mode to check the full order flow without a charge
* Makes it possible to customize the order page template, product name and description
* Allows to specify the global discount coupon for your sales
* Automatically transmits all contact information to the order page
* Generates special hash value automatically for each transaction

Based on the demo/test plugin provided by Mark Fail.

== Installation ==

1. Upload 'premiumpress_paymentgateway_ppg' folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Payments setup page of the PremiumPress theme.
4. Configure PayProGlobal payment gateway.

== Frequently Asked Questions ==

= How can I enable dynamic settings for my product? =

1. Logon to your PPG account at https://sellers.payproglobal.com/
2. Go to Products and select the product you want to use with dynamic settings
3. Scroll down to 'Dynamic settings' options and check the Allow box
4. Enable the 'Use Hash' option for security
5. Enter the 'Secret Key' value to appropriate box (8 symbols)
6. Save your product settings.
7. Use your PPG 'Product ID' and 'Secret Key' values to configure the gateway.

== Screenshots ==

1. Main screen
2. Plugin options
3. PPG Settings

== Changelog ==

= 1.0 =
* Initial release

= 1.0.1 =
* Description updated


== Upgrade Notice ==

= 1.0 =
* No update is required for first release