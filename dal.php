<?php
require 'ChromePhp.php'; // for debug php in chrome console with ChromePhp::log()

function _connectToDB(){

	$db = parse_url(getenv("DATABASE_URL"));
	$db["path"] = ltrim($db["path"], "/");

	$mysqli = new mysqli($db["host"], $db["user"], $db["pass"], $db["path"]);

	
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
	$conn.
	$result = $mysqli->query("select * from words where word in (".$wordsStrings.")");
	if($result){
		while($row = mysql_fetch_array($result))
		{
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
	$result = $mysqli->query("INSERT INTO words (word,amount) VALUES $valuesToInsert ON DUPLICATE KEY UPDATE amount=amount");

	$mysqli->close();
}

function getWords(){
	$mysqli = _connectToDB();
	$result = $mysqli->query("select * from words");
	$mysqli->close();

	return $result;
}

function deleteWords(){
	$mysqli = _connectToDB();
	$result = $mysqli->query("delete from words");
	$mysqli->close();

	return $result;
}


?>