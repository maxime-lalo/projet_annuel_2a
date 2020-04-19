<?php
require_once __DIR__ . '/../.env';
require_once __DIR__ . '/../assets/functions.php';

$url = $_SERVER['REQUEST_URI'];
$url = substr($url, 1,strlen($url));
if (substr($url,-1) == '/') {
	$url = substr($url, 0,strlen($url)-1);
}
$explodedUrl = explode("/", explode("?",$url)[0]);

if ($explodedUrl[0] == 'public') {
	$explode = explode('.',$url);
	if (isset($explode[1])) {
		$extension = $explode[count($explode)-1];
		header('Content-Type: '.getMimeType($extension));

		$urlRequest = __DIR__ . "/../" . $url;
		if (file_exists($urlRequest)) {
			require_once $urlRequest;
		}else{
			echo "Ressource non trouvée";
		}
	}else{
		http_response_code(404);
		echo "Ressource non trouvée";
	}
}else{
	if (empty($url)) {
		DEFINE('LANG',DEFAULT_LANG);
	}else{
		if (in_array($explodedUrl[0],POSSIBLE_LANGUAGES)) {
			DEFINE('LANG',strtolower($explodedUrl[0]));
			unset($explodedUrl[0]);
		}else{
			DEFINE('LANG',DEFAULT_LANG);
		}
	}

	require_once __DIR__ . '/../views/header.php';

	if (empty($url)) {
		require_once __DIR__ . '/../views/index.php';
	}else{
		$path = "/".implode("/", $explodedUrl).".php";
		if (file_exists(__DIR__ . '/../views/' . $path)) {
			if (in_array(LANG,POSSIBLE_LANGUAGES)) {
				http_response_code(200);
				require_once __DIR__ . '/../views/' . $path;
			}else{
				http_response_code(400);
				require_once __DIR__ . '/../views/errors/400.php';
			}
		}else{
			if (empty($explodedUrl)) {
				http_response_code(200);
				require_once __DIR__ . '/../views/index.php';
			}else{
				http_response_code(404);	
				require_once __DIR__ . '/../views/errors/404.php';
			}
		}
	}
	require_once __DIR__ . '/../views/footer.php';
}

?>