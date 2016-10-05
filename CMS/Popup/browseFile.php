<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<META http-equiv="Content-Style-Type" content="text/css">
<title> 物件管理 => 物件ファイルライブラリ</title>
<link href="../css/default.css" rel="stylesheet" type="text/css">
<SCRIPT src="../js/popup.js"></SCRIPT>
</head>

<body>
<div id="topLine"></div>
<div id="secondLine"></div>	
<br/>
<table style="float:left">
	<tr>
		<td>
			<div id="popupPageTitle">画像登録・更新</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id="popupText">物件の画像登録と更新ができます。</div>
		</td>
	</tr>
</table>

<!-- status area -->
<?php
include('../lib/idiorm.php');
include('../db/define.php');
include('../db/codelib.php');
include '../db/bukkenfilelib.php';
	
//$path = '../upload/';

$file1 = getBukkenFile(null);
$file2 = getBukkenFile(null);
$file3 = getBukkenFile(null);
$file4 = getBukkenFile(null);
$file5 = getBukkenFile(null);
$file6 = getBukkenFile(null);
$file7 = getBukkenFile(null);
$file8 = getBukkenFile(null);
$file9 = getBukkenFile(null);
$file10 = getBukkenFile(null);
$file11 = getBukkenFile(null);
$file12 = getBukkenFile(null);
$file13 = getBukkenFile(null);


if($_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_GET['bukkenId']))
	{
		$bukkenId = $_GET['bukkenId'];
		$fileList = getBukkenFiles($bukkenId);
		if(isset($fileList))
		{
			$count = count($fileList); 
			if($count > 0) $file1 = $fileList[0];
			if($count > 1) $file2 = $fileList[1];
			if($count > 2) $file3 = $fileList[2];
			if($count > 3) $file4 = $fileList[3];
			if($count > 4) $file5 = $fileList[4];
			if($count > 5) $file6 = $fileList[5];
			if($count > 6) $file7 = $fileList[6];
			if($count > 7) $file8 = $fileList[7];
			if($count > 8) $file9 = $fileList[8];
			if($count > 9) $file10 = $fileList[9];
			if($count > 10) $file11 = $fileList[10];
			if($count > 11) $file12 = $fileList[11];
			if($count > 12) $file13 = $fileList[12];

		}						
	}
}
else
{
	$bukkenId = $_POST['bukkenId'];
	if(isset($_POST['act']) && $_POST['act'] === 'register')
	{
		setFileInfo($file1, '1', $bukkenId);
		setFileInfo($file2, '2', $bukkenId);
		setFileInfo($file3, '3', $bukkenId);
		setFileInfo($file4, '4', $bukkenId);
		setFileInfo($file5, '5', $bukkenId);
		setFileInfo($file6, '6', $bukkenId);
		setFileInfo($file7, '7', $bukkenId);
		setFileInfo($file8, '8', $bukkenId);
		setFileInfo($file9, '9', $bukkenId);
		setFileInfo($file10, '10', $bukkenId);
		setFileInfo($file11, '11', $bukkenId);
		setFileInfo($file12, '12', $bukkenId);
		setFileInfo($file13, '13', $bukkenId);
		
	}
	else if(isset($_POST['act']) && $_POST['act'] === 'delete')
	{
		deleteBukkenFile($_POST['hdfCurrent']);
	}
	
	$file1 = getBukkenFile(null);
	$file2 = getBukkenFile(null);
	$file3 = getBukkenFile(null);
	$file4 = getBukkenFile(null);
	$file5 = getBukkenFile(null);
	$file6 = getBukkenFile(null);
	$file7 = getBukkenFile(null);
	$file8 = getBukkenFile(null);
	$file9 = getBukkenFile(null);
	$file10 = getBukkenFile(null);
	$file11 = getBukkenFile(null);
	$file12 = getBukkenFile(null);
	$file13 = getBukkenFile(null);

	$fileList = getBukkenFiles($bukkenId);
	if(isset($fileList))
	{
		$count = count($fileList); 
		if($count > 0) $file1 = $fileList[0];
		if($count > 1) $file2 = $fileList[1];
		if($count > 2) $file3 = $fileList[2];
		if($count > 3) $file4 = $fileList[3];
		if($count > 4) $file5 = $fileList[4];
		if($count > 5) $file6 = $fileList[5];
		if($count > 6) $file7 = $fileList[6];
		if($count > 7) $file8 = $fileList[7];
		if($count > 8) $file9 = $fileList[8];
		if($count > 9) $file10 = $fileList[9];
		if($count > 10) $file11 = $fileList[10];
		if($count > 11) $file12 = $fileList[11];
		if($count > 12) $file13 = $fileList[12];
	}
}

function setFileInfo($file, $id, $bukkenId)
{
	$path = '../upload/';
	if($_POST['id'.$id] === '')
	{			
		if ($_FILES['uploadFile'.$id]['error'] > 0)
		{							
		}
		else
		{
			$realPath =  $path.guid().'/';
			
			mkdir($realPath, 0777);
			chmod($realPath, 0777);		

			move_uploaded_file($_FILES['uploadFile'.$id]["tmp_name"], $realPath.$_FILES['uploadFile'.$id]["name"]);
			chmod($realPath.$_FILES["uploadFile".$id]["name"], 0777);
			
			$file = getBukkenFile(null);
			$file->bukkenId = $bukkenId;
			$file->comment = $_POST['comment'.$id];
			$file->name = $_FILES['uploadFile'.$id]["name"];
			$file->path = $realPath.$_FILES['uploadFile'.$id]["name"];
			$file->fileOrder = $_POST['fileOrder'.$id];
			saveBukkenFile($file);
			
		}
	}
	else
	{
		$file = getBukkenFile($_POST['id'.$id]);
		$file->comment = $_POST['comment'.$id];
		$file->fileOrder = $_POST['fileOrder'.$id];
		saveBukkenFile($file);
	}
}

function CalculateImageSize($filepath)
{
	$path = '../'.str_replace('..', '', $filepath);
	$info = getimagesize($path);																		
	$w = $info[0];
	$h = $info[1];													
	if($w / $h > 90 / 70)
	{								
		if($w > 90)
		{ 
			$h = intval($h * 90 / $w);
			$w = 90;								
		}
	}
	else
	{													
		if($h > 70)
		{
			$w = intval($w * 70 / $h);
			$h = 70;
		}
	}				
	$padding = intVal((90 - $w) / 2);		
	$paddingHeight = intVal((70 - $h) / 2);
	$arr = array($w, $h, $padding, $paddingHeight);
	return $arr;	
}

?>

<!-- End status area -->
			
<form name="RF0121-000PForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="f">

<input type="hidden" name="hdfCurrent" id="hdfCurrent">	
<input type="hidden" name="bukkenId" value="<?php echo $bukkenId?>">	
<input type="hidden" name="act" id="hdfAct">		

<!-- 1ブロック -->
<br/><br/>
<div align="left" class="imageDiv">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">	
	<tr >
		<?php 
			$hasFile = false;
			if($file1->pid !== '' && $file1->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file1->path);
		?>
			<td rowspan=3 style="text-align:center">			
				<img src='<?php echo $file1->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br/>
				<button onclick="javascript:ClickMe('<?php echo $file1->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file1->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">	
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload1">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile1" value="" id="fUpload1" onchange="GetSize('1')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('1')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment1" size="50" value="<?php echo $file1->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">			
			<select name="fileOrder1" id="fileOrder1">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
				
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id1" value="<?php echo $file1->pid?>"></input>

</div>
<!-- 1ブロック終わり -->

<!-- 2ブロック -->

<div align="left"  class="imageDiv">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file2->pid !== '' && $file2->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file2->path);
		?>		
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file2->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file2->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file2->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">									
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload2">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile2" value="" id="fUpload2" onchange="GetSize('2')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('2')">
				
			</td>
		<?php } ?>							
	</tr>
	<tr>
		<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment2" size="50" value="<?php echo $file2->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder2" id="fileOrder2">
				<option value="0">トップ</option>
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id2" value="<?php echo $file2->pid?>"></input>
</div>

<!-- 2ブロック終わり -->	
	
<!-- 3ブロック -->

<div align="left" class="imageDiv">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file3->pid !== '' && $file3->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file3->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file3->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file3->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file3->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload3">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile3" value="" id="fUpload3" onchange="GetSize('3')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('3')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment3" size="50" value="<?php echo $file3->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder3" id="fileOrder3">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id3" value="<?php echo $file3->pid?>"></input>
</div>

<!-- 3ブロック終わり -->		

<!-- 4ブロック -->

<div align="left" class="imageDiv">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file4->pid !== '' && $file4->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file4->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file4->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file4->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file4->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload4">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile4" value="" id="fUpload4" onchange="GetSize('4')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('4')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment4" size="50" value="<?php echo $file4->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder4" id="fileOrder4">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id4" value="<?php echo $file4->pid?>"></input>
</div>

<!-- 4ブロック終わり -->	

<!-- 5ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file5->pid !== '' && $file5->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file5->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file5->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file5->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file5->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload5">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile5" value="" id="fUpload5" onchange="GetSize('5')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('5')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment5" size="50" value="<?php echo $file5->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder5" id="fileOrder5">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id5" value="<?php echo $file5->pid?>"></input>
</div>

<!-- 5ブロック終わり -->	

<!-- 6ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file6->pid !== '' && $file6->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file6->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file6->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file6->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file6->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload6">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile6" value="" id="fUpload6" onchange="GetSize('6')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('6')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment6" size="50" value="<?php echo $file6->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder6" id="fileOrder6">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id6" value="<?php echo $file6->pid?>"></input>
</div>

<!-- 6ブロック終わり -->	

<!-- 7ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file7->pid !== '' && $file7->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file7->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file7->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file7->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file7->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload7">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile7" value="" id="fUpload7" onchange="GetSize('7')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('7')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment7" size="50" value="<?php echo $file7->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder7" id="fileOrder7">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id7" value="<?php echo $file7->pid?>"></input>
</div>

<!-- 7ブロック終わり -->	

<!-- 8ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file8->pid !== '' && $file8->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file8->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file8->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file8->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file8->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload8">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile8" value="" id="fUpload8" onchange="GetSize('8')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('8')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment8" size="50" value="<?php echo $file8->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder8" id="fileOrder8">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id8" value="<?php echo $file8->pid?>"></input>
</div>

<!-- 9ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file9->pid !== '' && $file9->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file9->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file9->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file9->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file9->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload9">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile9" value="" id="fUpload9" onchange="GetSize('9')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('9')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment9" size="50" value="<?php echo $file9->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder9" id="fileOrder9">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id9" value="<?php echo $file9->pid?>"></input>
</div>

<!-- 10ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file10->pid !== '' && $file10->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file10->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file10->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file10->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file10->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload10">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile10" value="" id="fUpload10" onchange="GetSize('10')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('10')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment10" size="50" value="<?php echo $file10->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder10" id="fileOrder10">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id10" value="<?php echo $file10->pid?>"></input>
</div>

<!-- 10ブロック終わり -->	

<!-- 11ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file11->pid !== '' && $file11->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file11->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file11->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file11->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file11->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload11">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile11" value="" id="fUpload11" onchange="GetSize('11')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('11')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment11" size="50" value="<?php echo $file11->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder11" id="fileOrder11">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id11" value="<?php echo $file11->pid?>"></input>
</div>

<!-- 11ブロック終わり -->

<!-- 12ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file12->pid !== '' && $file12->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file12->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file12->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file12->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file12->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload12">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile12" value="" id="fUpload12" onchange="GetSize('12')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('12')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment12" size="50" value="<?php echo $file12->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder12" id="fileOrder12">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id12" value="<?php echo $file12->pid?>"></input>
</div>

<!-- 12ブロック終わり -->	

<!-- 13ブロック -->

<div align="left">

<table class="imglistTbl" width="500" cellpadding="2" cellspacing="0">
	<tr >
		<?php 
			$hasFile = false;
			if($file13->pid !== '' && $file13->pid !== null)
			{
				$hasFile = true;
				$arr = CalculateImageSize($file13->path);
		?>
			<td rowspan=3 style="text-align:center">				
				<img src='<?php echo $file13->path?>' style="width:<?php echo $arr[0]?>px;height:<?php echo $arr[1]?>px;padding:<?php echo $arr[3].'px '.$arr[2].'px '.$arr[3].'px '.$arr[2].'px' ?>;"><br>
				<button onclick="javascript:ClickMe('<?php echo $file13->pid?>');">
					<img src="../images/global/btn2_delete.png" alt="削除" border="0" />
				</button>
			</td>		
			<td style="width:40px;" class="tdBackground tdText">名前：</td>
			<td style="border:0px" class="tdBackground"><?php echo $file13->name?></td>
		<?php }else{?>
			<td class="tdText" style="padding-top: 5px;">アップロード</td>
			<td class="tdBackground">					
				<span style="font-size:10px;color:red;display:none;font-weight:bold" id="spUpload13">画像サイズは１MByte以下としてください。</span>
				<input type="file" name="uploadFile13" value="" id="fUpload13" onchange="GetSize('13')">
				<img src="../images/global/demobtn_s_clear.gif" border="0" align="middle" onclick="ClearSize('13')">
			</td>
		<?php } ?>							
	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" style="width:92px;">コメント：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground">コメント：</td>
	  	<?php }?>
	  	<td class="tdBackground"><input type="text" name="comment13" size="50" value="<?php echo $file13->comment?>"></td>
  	</tr>
	<tr>
	  	<?php if(!$hasFile){?>
	  	<td class="tdText" nowrap>表示順番：</td>
	  	<?php }else{?>
	  	<td class="tdText tdBackground" nowrap>表示順番：</td>
	  	<?php }?>
	  	<td class="tdBackground">
			<select name="fileOrder13" id="fileOrder13">
				<option value="0">トップ</option>
				
				<option value="1">大（左）小（2）</option>
				<option value="2">小（3）</option>
				<option value="3">小（4）</option>
				<option value="4">小（5）</option>
				<option value="5">小（6）</option>
				<option value="6">小（7）</option>
				<option value="7">小（8）</option>
				<option value="8">小（9）</option>
				<option value="9">小（10）</option>
				<option value="10">小（11）</option>
				<option value="11">小（12）</option>
				<option value="100">大（右）</option>
			</select>
			
		</td>
  	</tr>  	
  	
</table>
<input type="hidden" name="id13" value="<?php echo $file13->pid?>"></input>
</div>

<!-- 13ブロック終わり -->	

</form>

<br>
<div align="center">		
	<img onclick="javascript:Register();" src="../images/global/demobtn_confirm.gif" alt="登録" border="0" onmouseover="ImageMouse2(this, 'demobtn_confirm_o.gif')" onmouseout="ImageMouse2(this, 'demobtn_confirm.gif')"/>
	<img onClick="window.close()" src="../images/global/demobtn_close.gif" alt="閉じる" border="0" onmouseover="ImageMouse2(this, 'demobtn_close_o.gif')" onmouseout="ImageMouse2(this, 'demobtn_close.gif')"/>
</div>
<br/>
<script language="javascript">
document.getElementById('fileOrder1').selectedIndex = -1;
document.getElementById('fileOrder2').selectedIndex = -1;
document.getElementById('fileOrder3').selectedIndex = -1;
document.getElementById('fileOrder4').selectedIndex = -1;
document.getElementById('fileOrder5').selectedIndex = -1;
document.getElementById('fileOrder6').selectedIndex = -1;
document.getElementById('fileOrder7').selectedIndex = -1;
document.getElementById('fileOrder8').selectedIndex = -1;
document.getElementById('fileOrder9').selectedIndex = -1;
document.getElementById('fileOrder10').selectedIndex = -1;
document.getElementById('fileOrder11').selectedIndex = -1;
document.getElementById('fileOrder12').selectedIndex = -1;
document.getElementById('fileOrder13').selectedIndex = -1;

<?php if(isset($file1)){?>
document.getElementById('fileOrder1').value = '<?php echo $file1->fileOrder?>';
<?php } ?>
<?php if(isset($file2)){?>
document.getElementById('fileOrder2').value = '<?php echo $file2->fileOrder?>';
<?php } ?>
<?php if(isset($file3)){?>
document.getElementById('fileOrder3').value = '<?php echo $file3->fileOrder?>';
<?php } ?>
<?php if(isset($file4)){?>
document.getElementById('fileOrder4').value = '<?php echo $file4->fileOrder?>';
<?php } ?>
<?php if(isset($file5)){?>
document.getElementById('fileOrder5').value = '<?php echo $file5->fileOrder?>';
<?php } ?>
<?php if(isset($file6)){?>
document.getElementById('fileOrder6').value = '<?php echo $file6->fileOrder?>';
<?php } ?>
<?php if(isset($file7)){?>
document.getElementById('fileOrder7').value = '<?php echo $file7->fileOrder?>';
<?php } ?>
<?php if(isset($file8)){?>
document.getElementById('fileOrder8').value = '<?php echo $file8->fileOrder?>';
<?php } ?>
<?php if(isset($file9)){?>
document.getElementById('fileOrder9').value = '<?php echo $file9->fileOrder?>';
<?php } ?>
<?php if(isset($file10)){?>
document.getElementById('fileOrder10').value = '<?php echo $file10->fileOrder?>';
<?php } ?>
<?php if(isset($file11)){?>
document.getElementById('fileOrder11').value = '<?php echo $file11->fileOrder?>';
<?php } ?>
<?php if(isset($file12)){?>
document.getElementById('fileOrder12').value = '<?php echo $file12->fileOrder?>';
<?php } ?>
<?php if(isset($file13)){?>
document.getElementById('fileOrder13').value = '<?php echo $file13->fileOrder?>';
<?php } ?>

function ClickMe(index)
{
	if(confirm("削除しますか？"))
	{
		document.getElementById('hdfCurrent').value = index;
		buttonClick('f','delete');
	}
	else
	{
		return false;
	}
}
function Register()
{
	if(CheckFileSize())
	{
		if(CheckFileLength())
		{
			if(confirm("登録しますか？"))
			{
				buttonClick('f','register');
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
function CheckFileLength()
{
	var files = document.getElementsByTagName("input");
	for(var i = 0 ; i < files.length ; i++)
	{
		var file = files[i];
		if(file.type == 'file' && file.value != '')
		{
			var index = file.value.lastIndexOf("\\");
			var name = file.value.substring(index + 1, file.value.length);
			if(name.length > 23)
			{
				alert('画像の名称は２３文字以内にしてください（拡張子含む）。');
				return false;
			}
		}
	}
	return true;
}
function CheckFileSize()
{
	var spans = document.getElementsByTagName("span");
	for(var i = 0 ; i < spans.length ; i++)
	{
		if(spans[i].id.indexOf('spUpload') >= 0 && spans[i].style.display != 'none')
			return false;
	}
	return true;
}
function GetSize(index)
{
	
	var spID = "spUpload" + index;
	var sp = document.getElementById(spID);
	sp.style.display = 'none';
	
	var id = "fUpload" + index;
	
	var myFSO = new ActiveXObject("Scripting.FileSystemObject");
	var filepath = document.getElementById(id).value;
	var thefile = myFSO.getFile(filepath);
	var size = thefile.size;		
	
	//var filepath = document.getElementById(id).value;
	//var size = fileSize(filepath);
	if(size > 1048576)
	{
		sp.style.display = 'block';
	}
	else
	{
		sp.style.display = 'none';
	}
}
function ClearSize(index)
{
	var id = "fUpload" + index;	
	var fileInput = document.getElementById(id);	
	fileInput.parentNode.innerHTML = fileInput.parentNode.innerHTML;
	document.getElementById("spUpload" + index).style.display = 'none';
}

</script>
<Script Language=VBScript> 

Function fileSize(fileSpec)
Dim isSize

Set fso = CreateObject("Scripting.FileSystemObject")
Set contentFile = fso.GetFile(fileSpec)
isSize = contentFile.Size
fileSize = isSize
Set contentFile = Nothing
Set fso = Nothing
End Function

</Script>
</body>
</html>