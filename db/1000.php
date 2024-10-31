<?php

$query_create = array();
$query_create_detail = array();
$query_update = array();

// Create query
$query_create[] = sprintf("
	CREATE TABLE  `%s` (
		`ID` bigint(20) NOT NULL AUTO_INCREMENT,
		`Name` VARCHAR(512),
		`ShortCode` VARCHAR(512),		
		PRIMARY KEY (  `ID` )
	)
", $wpdb->prefix.'woocomere_pricing_table_name');

$query_create[] = sprintf("
	CREATE TABLE  `%s` (
		`ID` bigint(20) NOT NULL AUTO_INCREMENT,
		`ProductID` bigint(20) NOT NULL,
		`Label` varchar(512),
		`Original` VARCHAR(512),
		`Description` int(2) DEFAULT '0',
		`IDName` bigint(20) NOT NULL,
		PRIMARY KEY (  `ID` )
	)ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
", $wpdb->prefix.'woocomere_pricing_table_detail');






