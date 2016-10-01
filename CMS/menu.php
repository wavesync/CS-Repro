<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>CMSメニュー</title>
		<link href="css/default.css" rel="stylesheet" type="text/css">

	</head>
	<body>
	
	<?php include('lock.php');?>
	
	<div id="topLine"></div>
	<div id="secondLine"></div>
	<div id="content">
		<div id="hd">	
			<div id="headerstaff">
				<table>
					<tr style="height: 25px;">
						<td>
							<span id="headerstaff1">こんにちは、<?php echo $user_check->userName;?>　様&nbsp;&nbsp;</span>
						</td>
					</tr>
					<tr>
						<td>
							<span id="headerstaff2">管理画面&nbsp;</span>
						</td>
					</tr>
				</table>						
			</div>		
			<img src="images/global/logo.gif" alt="会社ロゴ" style="z-index: 3" />			
		</div>
		<div id="hd2">
			<h1 id="h1Title">管理メニュートップ</h1>
			<a href="logout.php" style="float: right;"><img src="images/global/btn_logout.gif" alt="ログアウト" border="0" id="imgLogout"></a>
		</div>
	<br/><br/>
	 <table id="menu">
	 	<tr>
			<td colspan="2" class="headerTitle">管理メニュー</td>
		</tr>
		<tr>
			<th><img src="images/global/menuIndex.png" border="0"></img></th>
			<td class="menuItem"><a class="linkItem" onmouseover="Focus(this)" onmouseout="LostFocus(this)" href="bukkenlist.php">物件情報</a></td>
		</tr>
		<tr>
			<th><img src="images/global/menuIndex.png" border="0"></img></th>
			<td class="menuItem"><a class="linkItem" onmouseover="Focus(this)" onmouseout="LostFocus(this)" href="memberlist.php">会員情報</a></td>
		</tr>
		
		<tr>
			<td colspan="2"></td>
		</tr>
	 </table>	
	<br/><br/><br/><br/>
	
	</div>
		
	<?php include 'footer.php'; ?>
	</body>
	
</html>