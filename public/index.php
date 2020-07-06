<?php
session_start();
require_once __DIR__ . '/../.env';
require_once __DIR__ . '/../utils/functions.php';
require_once __DIR__ . '/../utils/database/DatabaseManager.php';
require_once __DIR__ . '/../vendor/autoload.php';


// Create the Transport
$transport = (new Swift_SmtpTransport('tls://smtp.gmail.com', 587))
    ->setUsername('maxime.lalo.pro@gmail.com')
    ->setPassword('ac5aabba1e&&')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Wonderful Subject'))
    ->setFrom(['john@doe.com' => 'John Doe'])
    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
    ->setBody('Here is the message itself')
;

// Send the message
$result = $mailer->send($message);

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
}elseif($explodedUrl[0] == 'file'){
    if (!isset($_GET['file'])){
        echo "Veuillez renseigner un nom de fichier";
    }else{
        $fileName = $_GET['file'];
        $file = __DIR__ . '/../uploads/' . $fileName;
        var_dump($file);
        if (file_exists($file)){
            if (isset($_GET['type'])){
                if ($_GET['type'] == "download"){
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    exit;
                }elseif($_GET['type'] == "view"){
                    $explodeName = explode(".",$fileName);
                    header(getMimeType($explodeName[count($explodeName)-1]));
                    $fp = fopen($file,"r");
                    while($data = fread($fp, 1024)){
                        echo $data;
                    }
                    fclose($fp);
                    exit;
                }else{
                    echo "Type non valide";
                }
            }else{
                echo "Veuillez renseigner un type, download ou view";
            }
        }else{
            echo "Fichier introuvable";
        }
    }
}elseif($explodedUrl[0] == 'api'){
    require_once __DIR__ . "/../api/header.php";
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
		if (file_exists(__DIR__ . '/../views/' . $path) ) {
			if (in_array(LANG,POSSIBLE_LANGUAGES)) {
				http_response_code(200);
				require_once __DIR__ . '/../views/' . $path;
			}else{
				http_response_code(400);
				require_once __DIR__ . '/../views/errors/400.php';
			}
		}else{
			if (empty($explodedUrl) OR (isset($explodedUrl[0]) && $explodedUrl[0] == "")) {
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