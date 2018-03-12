<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
$muviko->verifySession(true);
$muviko->getLanguage();
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

$page['name'] = $muviko->translate('Settings');
$page['footer'] = true;

$profile = $muviko->getProfile($_SESSION['fl_profile']);

if(isset($_POST['upgrade_membership'])) {
	$query = array();
	$subscription_expiration = strtotime('+31 days',time());
	$id = $muviko->user->id;
	$query['notify_url'] = $muviko->getDomain().'/payment/paypal.php';
	$query['cmd'] = '_xclick';
	$query['business'] = $muviko->settings->paypal_email;
	$query['currency_code'] = $muviko->settings->subscription_currency;
	$query['custom'] = json_encode(array('id' => $id, 'subscription_expiration' => $subscription_expiration, 'action' => 'upgrade'));
	$query['return'] = $muviko->getDomain().'/settings.php';
	$query['item_name'] = $muviko->settings->subscription_name;
	$query['quantity'] = 1;
	$query['amount'] = $muviko->settings->subscription_price;
	$query_string = http_build_query($query);
	header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
}

if(isset($_POST['renew_membership'])) {
	$query = array();
	$subscription_expiration = strtotime('+31 days',time());
	$id = $muviko->user->id;
	$query['notify_url'] = $muviko->getDomain().'/payment/paypal.php';
	$query['cmd'] = '_xclick';
	$query['business'] = $muviko->settings->paypal_email;
	$query['currency_code'] = $muviko->settings->subscription_currency;
	$query['custom'] = json_encode(array('id' => $id, 'subscription_expiration' => $subscription_expiration, 'action' => 'renew'));
	$query['return'] = $muviko->getDomain().'/settings.php';
	$query['item_name'] = $muviko->settings->subscription_name;
	$query['quantity'] = 1;
	$query['amount'] = $muviko->settings->subscription_price;
	$query_string = http_build_query($query);
	header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('settings'));
include($muviko->getFooterPath());