<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>ログイン</title>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<META http-equiv="Content-Style-Type" content="text/css">
<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="topLine"></div>
<div id="secondLine"></div>
<div id="content">	
	<div id="hd">
		<div id="headerstaff" style="padding-top:30px">
			<span id="headerstaff2"></span>
		</div>
		<img src="images/global/logo.gif" alt="会社ロゴ" />
	</div>

	<br/><br/><br/><br/><br/>
	<?php
		include('lib/idiorm.php');
		include('db/define.php');
		include("db/userlib.php");
		$error = "";
				
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$userID=addslashes($_POST['loginID']); 
			$password=addslashes($_POST['password']); 
			$ret= login($userID, $password);			
			if($ret === true || $ret === 1)
			{
				header("Location: menu.php");
				exit;
			}
			else 
			{
				$error="※【入力エラー】ID、パスワードが正しくありません";
			}
		}						
	?>	
	<?php if($error != ""){ ?>
	<div style="width:100%;color:red;font-size:14px;font-family:ＭＳ Ｐゴシック; font-weight:bold;padding-bottom:15px;text-align:center;"><?php echo $error; ?></div>
	<?php } ?>
	<form method="post" action="" id="login">

	<table id="login">
		<tr>
			<td colspan="2" style="height:10px;"></td>
		</tr>
		<tr style="height:40px">
			<td colspan="2" class="headerTitle">ログイン</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;font-size:12px;color:#333;height:50px">ユーザーIDとパスワードを入力してください。</td>
		</tr>
		<tr>
			<th>ユーザーID：</th>
			<td>
			<input type="text" name="loginID" id="txtLogin" style="ime-mode:disabled; width:230px;font-size:12px;" maxlength="30" />
			</td>
		</tr>
		<tr>	
			<th>パスワード：</th>
			<td><input type="password" name="password" style="ime-mode:disabled; width:230px;font-size:12px;" maxlength="10" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<a href="javascript:document.forms['login'].submit();">
					<img src="images/global/demobtn_login.gif" alt="認証" border=0 onmouseover="ImageMouse(this, 'demobtn_login_o.gif')" onmouseout="ImageMouse(this, 'demobtn_login.gif')" /></a>
				<br/>	
			</td>
		</tr>
	</table>

	</form>
	<br/><br/><br/><br/><br/>
</div>
<?php include 'footer.php'; ?>
	</body>
</html>