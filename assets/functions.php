<?php 

function translate(string $toTranslate):?string{
	if (LANG == "fr") {
		return $toTranslate;
	}else{
		include __DIR__ . '/../translations/'.LANG.'.php';
		if (isset($translations[$toTranslate])) {
			return $translations[$toTranslate];
		}else{
			return '<span style="color:red">Missing translation : <b>'.$toTranslate.'</b></span>';
		}
	}
}