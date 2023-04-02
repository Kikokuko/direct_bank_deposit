<?php
/*
* $Id: DIRBANKNZ.php  2010-6-22 06:57:21Z Nigel Thomson - adjusted from Crystal Jones code $
* v1.5.5.01 $Id: dirbank.php OldNGrey (BMH)) 2023-03-26 dirbanknz.php for zc157
* 2023-03-26 removed cheques and moneyorders
* 2023-03-30 sdk change order, added bank/branch, make za version
*/
$ln=$_SESSION['customer_last_name'] ?? '';    // required for inclusion below
$id=$_SESSION['customer_id'] ?? '';

define ('MODULE_PAYMENT_DIRBANKZA_PAYABLE', '');
define ('MODULE_PAYMENT_DIRBANKZA_ADDRESS', '');
define ('MODULE_PAYMENT_DIRBANKZA_TEXT_TITLE', 'Direct Bank EFT');
define ('MODULE_PAYMENT_DIRBANKZA_DESCRIPTION','Bank EFT or Deposit');

define('EMAIL_TEXT_NO_DELIVERY',   '<p>No Delivery, see below:');

if (defined('MODULE_PAYMENT_DIRBANKZA_STATUS')  {
    define('MODULE_PAYMENT_DIRBANKZA_TEXT_EMAIL_FOOTER',
        "Please use the following details to transfer your total order value:\n\n" .
		"\nAccount Name:  " . MODULE_PAYMENT_DIRBANKZA_ACCNAM . 
		"\nAccount No.:   " . MODULE_PAYMENT_DIRBANKZA_ACCNUM .
        "\nBank Name:     " . MODULE_PAYMENT_DIRBANKZA_BANKNAM .
        "\nBranch Code:   " . MODULE_PAYMENT_DIRBANKZA_BANKCODE .
        "\n\nThanks for your order which will ship immediately once we receive payment in the above account.\n" );

    define ('MODULE_PAYMENT_DIRBANKZA_HTML_EMAIL_FOOTER',
        '<BR>Please use the following details to transfer your total order value:<br>' .
		"\nAccount Name:  " . MODULE_PAYMENT_DIRBANKZA_ACCNAM .
		"\nAccount No.:   " . MODULE_PAYMENT_DIRBANKZA_ACCNUM .		
        "\nBank Name:     " . MODULE_PAYMENT_DIRBANKZA_BANKNAM .
        "\nBranch Code:   " . MODULE_PAYMENT_DIRBANKZA_BANKCODE .
        '<p>Thanks for your order which will ship immediately once we receive payment in the above account.');

    //  insert details on payment screen //
    define ('MODULE_PAYMENT_DIRBANKZA_TEXT_DESCRIPTION',
        '<br>Banking and Address details will also be sent to your email once the order is confirmed3.<br>'  .
       '<br>Please use the following details to transfer your total order value:<br><em>' .
		"\nAccount Name:  " . MODULE_PAYMENT_DIRBANKZA_ACCNAM . 
		"\nAccount No.:   " . MODULE_PAYMENT_DIRBANKZA_ACCNUM .
        "\nBank Name:     " . MODULE_PAYMENT_DIRBANKZA_BANKNAM .
        "\nBranch Code:   " . MODULE_PAYMENT_DIRBANKZA_BANKCODE .
        "\nReference:      " . $ln ."-" . $id . "-%s" .
        '</em><p>Thanks for your order which will ship immediately <br>once we receive payment in the above account." ') ;
}
