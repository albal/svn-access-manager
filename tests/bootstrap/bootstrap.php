<?php
/*
 * 
 * $Id: bootstrap.php 549 2018-01-17 09:55:50Z kriegeth $
 * 
 */

$_SESSION['SVNSESSID']['username'] = 'admin';
$_SESSION['SVNSESSID']['admin'] = 'n';
$_SESSION['SVNSESSID']['dbquery'] = 'SELECT * FROM test;';
$_SESSION['SVNSESSID']['dberror'] = 'Test error';
$_SESSION['§SVNSESSID']['dbfunction'] = 'Test function';

include_once ('config.inc.php');

?>