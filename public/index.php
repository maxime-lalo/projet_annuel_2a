<?php
require_once __DIR__ . '/../assets/conf.php';
require_once __DIR__ . '/../assets/functions.php';

$url = $_SERVER['REQUEST_URI'];
$url = substr($url, 1,strlen($url));

if (substr($url,-1) == '/') {
	$url = substr($url, 0,strlen($url)-1);
}

require_once __DIR__ . '/../views/header.php';
if (empty($url)) {
	DEFINE('LANG',DEFAULT_LANG);
	require_once __DIR__ . '/../views/index.php';
}else{
	$explodedUrl = explode("/", explode("?",$url)[0]);

	DEFINE('LANG',$explodedUrl[0]);
	unset($explodedUrl[0]);

	$path = "/".implode("/", $explodedUrl).".php";
	if (file_exists(__DIR__ . '/../views/' . $path)) {
		if (in_array(LANG,POSSIBLE_LANGUAGES)) {
			if (empty($explodedUrl)) {
				require_once __DIR__ . '/../views/index.php';
			}else{
				http_response_code(200);
				require_once __DIR__ . '/../views/' . $path;
			}
		}else{
			http_response_code(400);
			require_once __DIR__ . '/../views/errors/400.php';
		}
	}else{
		http_response_code(404);	
		require_once __DIR__ . '/../views/errors/404.php';
	}
}
require_once __DIR__ . '/../views/footer.php';
?>