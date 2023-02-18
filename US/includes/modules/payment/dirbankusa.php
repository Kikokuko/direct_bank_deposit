<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |   
// | http://www.zen-cart.com/index.php                                    |   
// |                                                                      |   
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: DIRBANKUSA.php 1106 2009-11-24 22:05:35Z CRYSTAL JONES $ modify from Auzbank of OZcommerce module by birdbrain
// BMH (OldNGreY) 2023-02-05 
//                2023-02-05    ln48 Illegal string offset 'id'  on line 50. added && isset($order->delivery['country']['id']) to end of line
//                              class variables

$id=isset($_SESSION['customer_id']);
$ln=isset($_SESSION['customer_last_name']);

  class dirbankusa {
    
    public $_check;
    public $code;       
    public $description;        // $description is a soft name for this payment method @var string 
    public $email_footer;       //$email_footer is the text to me placed in the footer of the email @var string
    public $enabled;            //
    public $order_status;       // $order_status is the order status to set after processing the payment
    public $sort_order;         // $sort_order is the order priority of this payment module when displayed
    public $title;              // $title is the displayed name for this order total method
    

// class constructor
    function __construct() {
      global $order;

      $this->code = 'dirbankusa';
      $this->title = MODULE_PAYMENT_DIRBANKUSA_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_DIRBANKUSA_TEXT_DESCRIPTION;
      $this->email_footer = defined('MODULE_PAYMENT_DIRBANKUSA_TEXT_EMAIL_FOOTER');
      $this->sort_order = defined('MODULE_PAYMENT_DIRBANKUSA_SORT_ORDER') ? MODULE_PAYMENT_DIRBANKUSA_SORT_ORDER : NULL;
      $this->enabled = (defined('MODULE_PAYMENT_DIRBANKUSA_STATUS') && MODULE_PAYMENT_DIRBANKUSA_STATUS  == 'True') ;
      
if (!defined('MODULE_PAYMENT_DIRBANKUSA_ORDER_STATUS_ID')) {define('MODULE_PAYMENT_DIRBANKUSA_ORDER_STATUS_ID', '');}

      if ((int)MODULE_PAYMENT_DIRBANKUSA_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_DIRBANKUSA_ORDER_STATUS_ID ;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order, $db;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_DIRBANKUSA_ZONE > 0) && isset($order->delivery['country']['id']) ) { //BMH change
        $check_flag = false;
        $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_DIRBANKUSA_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while (!$check->EOF) {
          if ($check->fields['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
          $check->MoveNext();
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
// disable the module if the order only contains virtual products
      if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_DIRBANKUSA_TEXT_DESCRIPTION);
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_DIRBANKUSA_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Direct Bank Deposit Module', 'MODULE_PAYMENT_DIRBANKUSA_STATUS', 'True', 'Do you want to accept USA Bank Deposit payments?', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
	 $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_DIRBANKUSA_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_DIRBANKUSA_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Routing Number', 'MODULE_PAYMENT_DIRBANKUSA_ROUTING', '000-000-000', '9 digit routing number in the format 000-000-000', '6', '1', now());");
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account No.', 'MODULE_PAYMENT_DIRBANKUSA_ACCNUM', '12345678', 'Bank Account No.', '6', '1', now());");
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account Name', 'MODULE_PAYMENT_DIRBANKUSA_ACCNAM', 'Joe Bloggs', 'Bank Account Name', '6', '1', now());");
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Name', 'MODULE_PAYMENT_DIRBANKUSA_BANKNAM', 'The Bank', 'Bank Name', '6', '1', now());");
     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_DIRBANKUSA_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
   }

    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
	  return array('MODULE_PAYMENT_DIRBANKUSA_STATUS', 'MODULE_PAYMENT_DIRBANKUSA_ZONE', 'MODULE_PAYMENT_DIRBANKUSA_SORT_ORDER', 'MODULE_PAYMENT_DIRBANKUSA_ROUTING', 'MODULE_PAYMENT_DIRBANKUSA_ACCNUM', 'MODULE_PAYMENT_DIRBANKUSA_ACCNAM',  'MODULE_PAYMENT_DIRBANKUSA_BANKNAM', 'MODULE_PAYMENT_DIRBANKUSA_ORDER_STATUS_ID');
    }
  }
