<?php
require_once __DIR__ . '/../assets/conf.php';
require_once __DIR__ . '/../assets/functions.php';

$url = $_SERVER['REQUEST_URI'];
$url = substr($url, 1,strlen($url));

require_once __DIR__ . '/../views/header.php';
if (empty($url)) {
	require_once __DIR__ . '/../views/index.php';
}else{
	$explodedUrl = explode("/", explode("?",$url)[0]);

	DEFINE('LANG',$explodedUrl[0]);
	unset($explodedUrl[0]);
	
	if (in_array(LANG,POSSIBLE_LANGUAGES)) {
		if (empty($explodedUrl)) {
			require_once __DIR__ . '/../views/index.php';
		}else{
			$path = "/".implode("/", $explodedUrl).".php";
			if (file_exists(__DIR__ . '/../views/' . $path)) {
				http_response_code(200);
				require_once __DIR__ . '/../views/' . $path;
			}else{
				http_response_code(404);	
				require_once __DIR__ . '/../views/errors/404.php';
			}
		}
	}else{
		http_response_code(400);
		require_once __DIR__ . '/../views/errors/400.php';
	}
}
require_once __DIR__ . '/../views/footer.php';
?>