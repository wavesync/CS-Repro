<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<META http-equiv="Content-Style-Type" content="text/css">
<title>郵便番号検索</title>
<link href="../css/popdefault.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
</head>

<?php
include('../lib/idiorm.php');
include('../db/define.php');
include('../db/codelib.php');
include '../db/ziplib.php';
$zipVal = '';
if($_SERVER["REQUEST_METHOD"] == "GET")
{
	$zipVal = $_GET['zip'];
}
else if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$zipVal = $_POST['zip'];		
}
function SearchByZip()
{		
	$zipList = null;
	if($_SERVER["REQUEST_METHOD"] == "GET")
	{
		$zipList = searchZip($_GET['zip']);
	}	
	else
	{
		$zipList = searchZip($_POST['zip']);
	}
	if($zipList != null)
	{				
		foreach($zipList as $zip)
		{
			$zipCode = $zip->zip;
			$pref = $zip->pref.$zip->city.$zip->cityArea;
			$pref2 = $zip->pref.'|'.$zip->city.'|'.$zip->cityArea;
			
			echo "<option value=\"$pref\" address=\"$pref2\">";
			echo "〒$zipCode&nbsp;住所:$pref";
			echo "</option>";
		}
	}
							
}
?>	 

<body class="popUp">
<h1 class="sub1Pop"><img src="../images/global/list_2.gif" align="middle" class="list1">郵便番号検索</h1>	
	<p class="result"></p>	
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="MainForm">
	
	<table class="dataTbl">
		<tr align="center">
			<th width="10%">〒</th>	
			<td>
				<input type="text" name="zip" size="8" maxlength="7" value="<?php echo $zipVal?>" />例）1066017
				<a href="javascript:document.forms['MainForm'].submit();">
					<img src="../images/global/btn_blue_search.gif" alt="選択" border="0" />
				</a> 
			</td>
		</tr>
		<tr>
			<th>住所</th>
			<td>
	        <select name="result" size="10" style="width: 410px;" ondblclick='SelectAddress(this)'>
				<?php SearchByZip()?>
	        </select>
			</td>
			</tr>			
	</table>
	<div style="text-align:right;font-size:70%;color:red;padding-top:5px">該当住所をダブルクリックしてください。</div>
	<div class="btnBlockPop">
						
		<a href="javascript:window.close();">
			<img src="../images/global/btn_blue_close.gif" alt="閉じる" border="0" />
		</a>
	</div>	
</form>
</body>
</html>
<script type="text/javascript">
function SelectAddress(obj)
{
	if(obj.value != "")
	{
		val = $(obj).find('option:selected').attr('address');
		window.opener.GetAddress(val);
		window.close();
	}
}
</script>