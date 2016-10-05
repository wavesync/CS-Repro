<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>CMS</title>
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/dateformat.js"></script>
<script type="text/javascript" src="js/String.js"></script>
<script type="text/javascript" src="js/json/json2.js"></script>
<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript" src="js/lib.js"></script>
<script type="text/javascript" src="js/init.js"></script>

<link href="css/default.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php 
		include('lock.php');		
		$end = end((explode('/', $_SERVER['REQUEST_URI'])));
	?>
	
	<div id="topLine"></div>
	<div id="secondLine"></div>
	<div id="content">
		<div id="hd">	
			<div id="headerstaff">
				<table>
					<tr style="height: 15px;">
						<td>
							<span id="headerstaff1">
								<a href="logout.php" style="float: right;"><img src="images/global/btn_logout.gif" alt="ログアウト" border="0" id="imgLogout"></a>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<span id="headerstaff2">
								こんにちは、<?php echo $user_check->userName;?>　様&nbsp;&nbsp;
							</span>
						</td>
					</tr>
				</table>						
			</div>		
			<img src="images/global/logo.gif" alt="会社ロゴ" style="z-index: 3" />			
		</div>
		