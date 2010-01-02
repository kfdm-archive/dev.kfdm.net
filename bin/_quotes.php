<?php
Console::debug();
Console::debug('Quotes');
$dbnew->query('TRUNCATE `quotes`');
foreach($dbold->query('SELECT * FROM `quotes`') as $row) {
//	var_dump($row); break;
	Console::debug(' - Adding quote #'.$row->id);
	$dbnew->insert('quotes',array(
		'id'				=> $row->id,
		'quote'				=> $row->quote,
		'postdate'			=> $row->postdate,
	));
}