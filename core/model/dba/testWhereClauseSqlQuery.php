<?php


$sqlQuery = 'SELECT * FROM personTest WHERE lName = :v';
test($sqlQuery);

$sqlQuery = 'SELECT * FROM personTest WHERE lName = :wer && lName2 = :sgf';
test($sqlQuery);

$sqlQuery = 'SELECT * FROM personTest WHERE lName = :dfg_dsf || lName2 = :dee_dd';
test($sqlQuery);

$sqlQuery = 'SELECT * FROM personTest WHERE lName = :lName || lName2 = :lName';
test($sqlQuery);

$sqlQuery = 'SELECT * FROM personTest WHERE (lName = :lName || lName2 = :lName)  && lName3 = :lName';
test($sqlQuery);


function testWhereClause($sqlQuery) {
		
	preg_match('//', $sqlQuery, $matches);

	var_dump($matches);
}