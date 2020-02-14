<?
include_once 'functions.php';
writeToLog($_REQUEST, 'REQUEST');
$action = str_replace('optima_', '', $_REQUEST['code']);
include_once 'actions/'.$action.'.php';
