<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
$muviko->verifySession(false);
$muviko->getLanguage();
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

$page['name'] = $muviko->translate('Sign_Up');
$page['footer'] = true;

if(isset($_POST['continue'])) {
	$query = array();
	$subscription_expiration = strtotime('+31 days',time());
	$full_name = $_POST['full_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$phone = $_POST['phone'];
	$query['notify_url'] = $muviko->getDomain().'/payment/paypal.php';
	$query['cmd'] = '_xclick';
	$query['business'] = $muviko->settings->paypal_email;
	$query['currency_code'] = $muviko->settings->subscription_currency;
	$query['custom'] = json_encode(array('full_name' => $full_name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'subscription_expiration' => $subscription_expiration, 'action' => 'register'));
	$query['return'] = $muviko->getDomain().'/index.php';
	$query['item_name'] = $muviko->settings->subscription_name;
	$query['quantity'] = 1;
	$query['amount'] = $muviko->settings->subscription_price;
	$query_string = http_build_query($query);
	header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('register'));
include($muviko->getFooterPath());