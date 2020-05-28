<?php 
function translate(string $toTranslate):?string{
	if (LANG == "fr") {
		return $toTranslate;
	}else{
		include __DIR__ . '/../translations/'.LANG.'.php';
		if (isset($translations[$toTranslate])) {
			return $translations[$toTranslate];
		}else{
			return '<span style="color:#ff0000">Missing translation in lang <b>' .strtoupper(LANG).'</b>  =>  <b>'.$toTranslate.'</b></span>';
		}
	}
}

function requirePublic(string $ressource):?string{
	return NULL;
}

function getMimeType(string $extension):?string{
	switch ($extension) {
		case 'css':
			return 'text/css';
			break;
		case 'json':
			return 'application/json';
			break;
		case 'js':
			return 'application/javascript';
			break;
		case 'png':
			return 'image/png';
			break;
        case 'jpg':
        case 'jpeg':
			return 'image/jpeg';
			break;
        default:
			return "text/html";
			break;
	}
}

function timestampFormat(string $timestamp):string{
    return date('d/m/Y H:i',strtotime($timestamp));
}