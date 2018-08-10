<?php
require 'dal.php';

switch ($_SERVER["REQUEST_METHOD"]) {
	case 'GET':
		_handleGetRequest();
		break;
	case 'POST':
		_handlePostRequest();
		break;
	default:
		break;
}

function _handleGetRequest(){
	$htmlData = _getDataFromClassNamer();
	$words = _extractWords($htmlData);
	insertWords($words);
	$result = getWords();
	echo _prepareWordsForDisplay($result);
}

function _getDataFromClassNamer(){
	$url = 'https://www.classnamer.org/';
	return file_get_contents($url);
}

function _extractWords($htmlData){
	$pattern = '/(<p id="classname">[a-zA-Z]*<wbr>[a-zA-Z]*<wbr>[a-zA-Z]*<\/p>)/';
	$result = array();
	preg_match($pattern, $htmlData, $result);
	$result = reset($result);
	$result = str_replace('<p id="classname">', '', $result);
	$result = str_replace('<wbr>', '', $result);
	$result = str_replace('</p>', '', $result);
	return preg_split('/(?=[A-Z])/', $result, -1, PREG_SPLIT_NO_EMPTY);
}

function _prepareWordsForDisplay($result){
	$valuesToEcho = "";
	if($result){
		while($row = $result->fetch_assoc())
		{
			$word = $row['word'];
			$count = $row['amount'];
			$valuesToEcho .= "<font size='+".$count."'>".$word."(".$count.")</font> ";
		}	
	}	

	return $valuesToEcho;
}

function _handlePostRequest(){
	$result = deleteWords();
	Print '<script>window.location.assign("index.php");</script>';
} 

?>