<?php
/*
 Plugin Name: ClassiPress OnlineNaira Payment Gateway
 Plugin URI: http://wordpress.org/plugins/classipress-onlinenaira-payment-gateway/
 Description: Extend the ClassiPress 3.3 application theme to include OnlineNaira as an additional payment gateway.
 Author: Taras Ninko 
 Author URI: http://deepvision.com.ua
 Version: 0.1
 License: GPLv2 or later
*/


add_action( 'init', 'online_naira_setup', 1000 );

function online_naira_setup(){
    include 'online-naira-gateway.php';
}