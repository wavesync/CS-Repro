<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<META http-equiv="Content-Style-Type" content="text/css">
<title>路線検索</title>
<link href="../css/popdefault.css" rel="stylesheet" type="text/css">

</head>

<?php
include('../lib/idiorm.php');
include('../db/define.php');
include('../db/codelib.php');
include '../db/stationlib.php';

	function SearchLineByName()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$ensenMei = $_POST['lineName'];
			$ret = searchLine($ensenMei);
			
			if($ret != null)
			{			
				$ensenCd = $_POST['saveEnsen'];
				
				echo "<option value=\"\">未選択</option>";						
				foreach($ret as $line)
				{
					$val = $line->ensenCd;
					$text = $line->ensenMei;
										
					if($val === $ensenCd)
					{
						echo "<option value=\"$val\" selected>";	
					}
					else
					{
						echo "<option value=\"$val\">";
					}
					echo "$text";
					echo "</option>";
				}
			}
		}
	}

	function SearchStationByLine()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if($_POST['action'] === '1')
			{
				$ensenCd = $_POST['saveEnsen'];
				$ret = searchStation($ensenCd);
				
				if($ret != null)
				{				
					foreach($ret as $line)
					{
						$val = $line->ekiMei;										
						echo "<option value=\"$val\">";
						echo "$val";
						echo "</option>";
					}
				}
			}
		}
	}
?>

<body class="popUp">
<h1 class="sub1Pop"><img src="../images/global/list_2.gif" align="middle" class="list1">路線検索</h1>	
	<p class="result"></p>	
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="MainForm" ENCTYPE="multipart/form-data">
	
	<table cellspacing="0" class="dataTbl">
		<tr>
			<th width="50%" >沿線名を入力してください</th>
			<td width="50%" class="cell">
				<input type="text" name="lineName" size="20" value="<?php echo $_POST['lineName']?>" style="ime-mode:active" class="">
			</td>
		</tr>
		<tr>
			<th>沿線</th>	
			<td class="cell">
		        <select name="ensenCd" id="lstEnsen" onchange="Search(1);">
		        	<?php SearchLineByName();?>
		        </select>
		    </td>
		</tr>
		 
		<tr>
			<th >駅名</th>
			<td class="cell">
		        <select name="result" id="lstResult" onchange="javascript:document.getElementById('hrefSelect').style.display = 'inline'">
		        	<?php SearchStationByLine();?>
		        </select>
		    </td>
		</tr>
		 
		</table>
		<input type="hidden" name="action" id="hidAction"></input>
		<input type="hidden" name="saveEnsen" id="hidEnsen"></input>
		<input type="hidden" name="index" id="hidIndex" value='<?php echo $_POST['index']?>'></input>
		
	<div class="btnBlockPop">
		<a href="javascript:SelectLine();" style="display:none" id="hrefSelect"><img src="../images/global/btn_blue_select.gif" alt="選択" border="0" /></a>
		<a href="javascript:Search(0);"><img src="../images/global/btn_blue_search.gif" alt="選択" border="0" /></a>				
		<a href="javascript:window.close();"><img src="../images/global/btn_blue_close.gif" alt="閉じる" border="0" /></a>
	</div>	
</form>
</body>
</html>
<script language="javascript">

<?php
if($_SERVER["REQUEST_METHOD"] == "GET")
{
?>
document.getElementById('hidIndex').value = <?php echo $_GET['index']?>;
<?php } ?>

var lstEnsen = document.getElementById('lstEnsen');
var lstResult = document.getElementById('lstResult');

if(lstResult.value != '' && lstEnsen.value != '')
{
	document.getElementById('hrefSelect').style.display = 'inline';
}
else
{
	document.getElementById('hrefSelect').style.display = 'none';
}

function Search(index)
{	
	var hidEnsen = document.getElementById('hidEnsen');
	hidEnsen.value =  document.getElementById('lstEnsen').value;	
	document.getElementById('hidAction').value = index;
	
	if(index == 0)
	{
		hidEnsen.value = '';
	}
	if(index == 0 || (index == 1 && hidEnsen.value != ''))
	{
		document.forms['MainForm'].submit();
	}
}
function SelectLine()
{
	var index = document.getElementById('hidIndex').value;		
	window.opener.GetStation(lstEnsen.options[lstEnsen.selectedIndex].text, lstResult.options[lstResult.selectedIndex].text, index);
	window.close();
}
</script>