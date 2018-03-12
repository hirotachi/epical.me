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

$genre_id = $_GET['id'];
$genre_name = $_GET['genre_name'];

$page['name'] = ucwords($genre_name,'-');
$page['footer'] = true;

if(!isset($_GET['filter'])) {
	$filter = 'ORDER BY id DESC';
	$option = 'oldest';
} else {
	if($_GET['filter'] == 'oldest') {
		$filter = 'ORDER BY id DESC';
		$option = 'oldest';
	} elseif($_GET['filter'] == 'newest') {
		$filter = 'ORDER BY id ASC';
		$option = 'newest';
	} elseif($_GET['filter'] == 'random') {
		$filter = 'ORDER BY RAND()';
		$option = 'random';
	} else {
		$filter = 'ORDER BY id DESC';
		$option = 'oldest';
	}
}

$genre_movies = $db->query("SELECT * FROM movies WHERE movie_genres LIKE '%".$genre_id."%' OR movie_genres LIKE '%".$genre_id."' OR movie_genres LIKE '".$genre_id."%' ".$filter."");

include($muviko->getHeaderPath());
include($muviko->getPagePath('category'));
include($muviko->getFooterPath());