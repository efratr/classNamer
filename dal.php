<?php

function _connectToDB(){

	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);

	$conn = new mysqli($server, $username, $password, $db);
}

function insertWords($words){
	_connectToDB();
	$wordsStrings = array();
	foreach ($words as $word) {
		$wordsStrings []= "'".$word."'";
	}

	$wordsStrings = implode(',',$wordsStrings);
	$valuesToInsert = array();
	$existingWords = array();
	$result = mysql_query("select * from words where word in (".$wordsStrings.")");
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
	mysql_query("INSERT INTO words (word,amount) VALUES $valuesToInsert ON DUPLICATE KEY UPDATE amount=amount");
}

function getWords(){
	_connectToDB();
	$result = mysql_query("select * from words");

	return $result;
}

function deleteWords(){
	_connectToDB();
	return mysql_query("delete from words");
}


?>