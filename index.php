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

$page['name'] = $muviko->settings->website_title;
$page['footer'] = true;

$featured_movie = $muviko->getFeaturedMovie();
$genres = $muviko->getSections();
$my_list = $muviko->getMyList();

include($muviko->getHeaderPath());
include($muviko->getPagePath('index'));
include($muviko->getFooterPath());