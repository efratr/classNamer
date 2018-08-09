<?php
require 'ChromePhp.php'; // for debug php in chrome console with ChromePhp::log()

function _connectToDB(){

	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	ChromePhp::log("url: ");
	ChromePhp::log($url);
	
	$server = $url["host"];
	ChromePhp::log("server: ");
	ChromePhp::log($server);
	
	$username = $url["user"];
	ChromePhp::log("user: ");
	ChromePhp::log($username);
	
	$password = $url["pass"];
	ChromePhp::log("pass: ");
	ChromePhp::log($password);
	
	$db = substr($url["path"], 1);
	ChromePhp::log("db: ");
	ChromePhp::log($db);

	$mysqli = new mysqli("localhost", "my_user", "my_password", "world");

	/* check connection */
	if ($mysqli->connect_errno) {
    	ChromePhp::log("Connect failed:");
    	ChromePhp::log($mysqli->connect_error);
    	exit();
	}
	
	return $mysqli;
}

function insertWords($words){
	ChromePhp::log("before connect to db");
	$mysqli = _connectToDB();
	ChromePhp::log("after connect to db");
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
}

function getWords(){
	$mysqli = _connectToDB();
	$result = $mysqli->query("select * from words");

	return $result;
}

function deleteWords(){
	$mysqli = _connectToDB();
	return $mysqli->query("delete from words");
}


?>