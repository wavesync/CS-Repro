<?php
include('lib/idiorm.php');
include('db/define.php');
include('db/userlib.php');

$user_check= unserialize($_SESSION['USER']);

if(!isset($_SESSION['USER']) || !isset($user_check) || $user_check == null)
{
	header("Location: login.php");
	exit;
}
else
{
	
	$ret = login($user_check->userId, $user_check->password);
	if($ret == false)
	{
		header("Location: login.php");
		exit;
	}	
}
?>