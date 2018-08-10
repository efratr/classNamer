<?php
require 'ChromePhp.php'; // for debug php in chrome console with ChromePhp::log()

function _connectToDB(){

	//todo - should be in env file
	$host = "remotemysql.com";
	$user = "ovWJU5rBsT";
	$password = "8UFLFNd9Hp";
	$db = "ovWJU5rBsT";

	$mysqli = new mysqli($host, $user, $password, $db);

	// Check connection
	if ($mysqli->connect_error) {
    	die("Connection failed: " . $mysqli->connect_error);
	}

	return $mysqli;
}

function insertWords($words){
	$mysqli = _connectToDB();
	$wordsStrings = array();
	foreach ($words as $word) {
		$wordsStrings []= "'".$word."'";
	}

	$wordsStrings = implode(',',$wordsStrings);
	$valuesToInsert = array();
	$existingWords = array();
	$result = $mysqli->query("select * from ovWJU5rBsT.words where word in (".$wordsStrings.")");
	if($result){
		while ($row = $result->fetch_assoc()) {
        	$word = $row['word'];
			$existingWords []= $word;
			$newCount = $row['amount']++;
			$valuesToInsert []= "('".$word."',".$newCount.")";
    	}	
	}

	foreach($words as $word) {
		if(!in_array($word, $existingWords)){
			$valuesToInsert []= "('$word',1)";
		}
	}

	$valuesToInsert = implode(',', $valuesToInsert);
	$result = $mysqli->query("INSERT INTO ovWJU5rBsT.words (word,amount) VALUES $valuesToInsert ON DUPLICATE KEY UPDATE amount=amount");

	$mysqli->close();
}

function getWords(){
	$mysqli = _connectToDB();
	$result = $mysqli->query("select * from ovWJU5rBsT.words");
	$mysqli->close();

	return $result;
}

function deleteWords(){
	$mysqli = _connectToDB();
	$result = $mysqli->query("delete from ovWJU5rBsT.words");
	$mysqli->close();

	return $result;
}

?>