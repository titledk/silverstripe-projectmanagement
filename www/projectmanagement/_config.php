<?php
//getting rid of a NOTICE error in Sapphire TableListField
error_reporting(E_ALL ^ E_NOTICE);


global $project;
$project = 'projectmanagement';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('blackcandypm');

SiteTree::set_create_default_pages(false);

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

Director::addRules(8, array(
	'dump//$Action' => 'MySQLDump'
));