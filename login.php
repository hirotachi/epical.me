<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

if(isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$user = $muviko->getUser(false,$email,'email');
	if(is_object($user)) {
		if($muviko->hashPassword($password) == $user->password) {
			$session_id = $muviko->generateSessionID();
			$profile = $muviko->getProfile($user->last_profile);
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$db->query("INSERT INTO sessions(session_id,user_ip,user_id,profile_id,language,is_active,time) VALUES ('".$session_id."','".$user_ip."','".$user->id."','0','english','1','".time()."')") or die(mysqli_error($db));
			$array = array('fl_session_id' => $session_id, 'fl_user_id' => $user->id, 'is_admin' => $user->is_admin, 'fl_language' => 'english');
			$muviko->startUserSession($array);
			$muviko->setProfile($profile->id,0);
			header('Location: '.$muviko->settings->redirect_after_login);
			exit;
		} else {
			$error = 'Invalid Credentials';
			header('Location: '.$muviko->getDomain().'/index.php?error='.$error);
			exit;
		}
	} else {
		$error = 'Invalid Credentials';
		header('Location: '.$muviko->getDomain().'/index.php?error='.$error);
		exit;
	}
}