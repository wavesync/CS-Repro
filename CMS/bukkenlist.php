<?php include('header.php');?>
<?php include('db/bukkenlib.php');?>
<?php include('db/codelib.php');?>

<?php

	$isShowTableHeader = false;	
	$searchInfo = getBukken(null);	
	
	
	if($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_SESSION['searchObject']))
		{			
			$arr = $_SESSION['searchObject'];			
			$searchInfo->memberFlg = $arr[0];
			$searchInfo->objectName = $arr[1];	
			$searchInfo->publishFlg = $arr[2];
			$searchInfo->address = $arr[3];
			
			$objectList = searchBukken($searchInfo);	
			$isShowTableHeader = true;		
		}		
	} 
	else if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$isShowTableHeader = true;
		#削除
		$act = $_POST['act'];
		if(isset($act) && $act === 'delete')
		{
			DeleteAllBukken($_POST['pid']);
		}
		#削除
		$searchInfo->memberFlg = $_POST['memberFlg'];
		$searchInfo->objectName = $_POST['objectName'];
		$searchInfo->publishFlg = $_POST['publishFlg'];		
		if(isset($_POST['address'])){
			$searchInfo->address = implode(',', $_POST['address']);
		}
		else{
			$searchInfo->address = null;
		}
		
		$_SESSION['searchObject'] = array($_POST['memberFlg'], $_POST['objectName'], $_POST['publishFlg'], $searchInfo->address);	
		$objectList = searchBukken($searchInfo);
	}
	function CleanNumber($num)
	{
		if(isset($num) && $num != '')
		{
			if ($num==0) {return 0;}
			return rtrim(rtrim($num, '0'), '.');
		} 
		else
		{
			return '';
		}
	}
	
	
?>
<div id="hd2">
	<h1>		
		<a href="menu.php" id="menuTopLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" >
			管理メニュートップ</a>≫		
		<?php if($isShowTableHeader){?>
		<font style="font-weight: 800">&nbsp;【売買物件】登録情報検索結果一覧</font>
		<?php }else{?>
		<font style="font-weight: 800">&nbsp;【売買物件】新規登録・登録情報検索</font>
		<?php }?>
	</h1>
</div>
<div id="pageTitle">
	<?php if($isShowTableHeader){?>
	【売買物件】登録情報検索結果一覧
	<?php }else{?>
	【売買物件】新規登録・登録情報検索
	<?php }?>
</div>
<div id="pageDiscription">
	<?php if($isShowTableHeader){?>
	登録物件の検索結果です。再検索される場合は該当項目を選択し「検索」ボタンを押してください。<br/>
	新規登録の場合は「新規登録」ボタンを押してください。
	<?php }else{?>
	新規登録の場合は「新規登録」ボタンを、既に登録済み情報の更新の場合は、該当項目を選択し「検索」ボタンを押してください。<br/>
	尚、<font color="#F67F05">登録済み情報を全件表示</font>する場合は、項目を選択せず、「検索」ボタンを押してください。
	<?php }?>
</div>

<table class="dataTbl"> 
	<tr>
		<td id="tableHeader" style="border-right:none !important">検索</td>
		<td id="tableHeader" style="border-left:none !important">
			<a href="#" onclick="scrollSearchDiv()" style="float:right;padding-right:5px;"><img id="imgSwitch" src="images/global/south-mini.png"></a>
		</td>
	</tr>
</table>

<div id="searchDiv">

<form id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="act" id="hidAct"></input>
<input type="hidden" name="pid" id="hidPid"></input>
	
<table class="dataTbl"> 
	<tr>
		<th width="15%" >自社・他社</th>
		<td colspan="3">
			<input type="radio" name="memberFlg" value="00" <?php if($searchInfo->memberFlg == "00"){echo 'checked';}?> />自社
			<input type="radio" name="memberFlg" value="01" <?php if($searchInfo->memberFlg == "01"){echo 'checked';}  ?>/>他社
			<input type=radio name="memberFlg" value="02" <?php if($searchInfo->memberFlg == "02"){echo 'checked';}  ?>/>東日本レインズ
		</td>
	</tr>
	<tr>
		<th>建物名</th>
		<td width="35%" >
			<input type="text" name="objectName" id="objectName" size="55" style="ime-mode:active" value="<?php echo $searchInfo->objectName ?>" /> 
		</td>
		<th nowrap width="15%" >ネット公開</th> 
		<td nowrap> 
			<input type="radio" name="publishFlg" value="" <?php if(isset($searchInfo) && $searchInfo->publishFlg == ""){echo 'checked';}?> />全て
			<input type="radio" name="publishFlg" value="01" <?php if(isset($searchInfo) && $searchInfo->publishFlg == "01"){echo 'checked';}  ?>/>公開中
			<input type=radio name="publishFlg" value="00" <?php if(isset($searchInfo) && $searchInfo->publishFlg == "00"){echo 'checked';}  ?>/>非公開 
		</td> 
	</tr>
	
	<tr>
		<th>エリア</th>
		<td colspan="3">
			<?php MakeCodeMstMultiCheckbox('0028', 'address', $searchInfo->address, 6);?>
		</td>
	</tr>
	
</table> 
<br>
</form>

<div align="center">
	<a href='bukkendetail.php'>
		<img src="images/global/demobtn_register.gif" alt="新規登録" border="0" id="imgRegister"></a>
	<a href="#" onclick="document.forms['frm'].submit();" >
		<img src="images/global/demobtn_search.gif" alt="検索" border="0"></a>
	<a href="#">
		<img src="images/global/demobtn_clear.gif" alt="クリア" border="0" onclick="ClearCon()" ></a>
</div>

</div>

<br>
<?php if(isset($objectList)){?>
<div class="pager">全<font color="red"><?php echo sizeof($objectList)?></font>件中：1-<?php echo sizeof($members)?>件目</div>
<table  cellspacing="1" cellpadding="0" class="listTbl">
	<tr>
		<td colspan="9" id="tableHeader">登録情報検索結果</td>
	</tr>
</table>
<table class="listTbl tablesorter" id="tablesorter">

	<thead>
	<tr>
		<th class="textcenter" style="width:56px !important">
		詳細・<br/>削除
		</th>
		<th class="textcenter" >建物番号</th>		
		<th class="textcenter">建物名</th>
		<th class="textcenter" >路線<br>駅</th>
		<th class="textcenter" >所在地</th>
		<th class="textcenter" >間取<br>階数</th>
		<th class="textcenter" >専有面積</th>
		<th class="textcenter" >価格</th>				
	</tr>
	</thead>
	<tbody>
	<?php foreach($objectList as $bukken){ ?>
		<tr>
			<td style="width:56px !important">
				<a style="width:55px" href="bukkendetail.php?pid=<?php echo $bukken->pid ?>">
					<img src="images/global/demobtn_s_renewal.gif" alt="詳細" border="0" >
				</a>
				<a style="width:55px" href="#" onclick="javascript:DeleteItem(<?php echo $bukken->pid?>);"  >
					<img src="images/global/demobtn_s_delete.gif" alt="削除" border="0" />
				</a>
			</td>
			<td class="textcenter"><?php echo $bukken->objectCode ?></td>
			<td><?php echo $bukken->objectName ?></td>
			<td>
				<?php echo $bukken->route1Name ?><br>
				<?php echo $bukken->station1Name ?>
			</td>
			<td><?php echo $bukken->address ?></td>			
			<td class="textcenter">
				 <?php echo getCodeTitle('0015', $bukken->madori) ?><br>
				 <?php echo $bukken->syozaiKai ?>
			</td>
			<td class="textcenter"><?php if($bukken->senyuArea !== null && $bukken->senyuArea !== '') {echo CleanNumber($bukken->senyuArea).'㎡';} ?></td>
			<td class="textcenter" nowrap>
				<?php 
					if($bukken->price !== null && $bukken->price !== '')
					{echo $bukken->price;}
					else{ echo '－'; } 
				?> 
			</td>						
		</tr>
	<?php }?>
	</tbody>
</table>
<?php } ?>

<br/>
<a href="menu.php" id="menuLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" > 《 管理メニュートップへ</a>
<br/><br/>

</div>
<?php include 'footer.php'; ?>	

<script language="javascript">
function DeleteItem(pid)
{
	if(confirm('削除しますか？'))
	{
		document.getElementById('hidAct').value = 'delete';
		document.getElementById('hidPid').value = pid;
		document.forms['frm'].submit();
	}
	else
	{
		document.getElementById('hidAct').value = '';
	}
}
function ClearCon()
{
	$('#objectName').val('');
	$("input[name^=memberFlg]").each(function () {
		if($(this).val() == '02'){
			$(this).trigger('click');
		}		
	});

	$("input[name=publishFlg]").each(function () {
		if($(this).val() == ''){
			$(this).trigger('click');
		}
	});
	
	$("input[name^=address]:checked").each(function () {
		$(this).removeAttr("checked");
	});	
}
/**/
function scrollSearchDiv(){
	if($('#searchDiv').is(':visible')){
		$('#imgSwitch').attr('src', 'images/global/north-mini.png');
	}		
	else {
		$('#imgSwitch').attr('src', 'images/global/south-mini.png');
	}
	$('#searchDiv').slideToggle("slow");
}

</script>

<script type="text/javascript" src="./js/jquery-latest.min.js" charset="utf-8"></script>
<script type="text/javascript" src="./js/jquery.tablesorter.js" charset="utf-8"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#tablesorter").tablesorter({headers: {0:{sorter:false},1:{sorter:true},2:{sorter:true},3:{sorter:false},4:{sorter:false},5:{sorter:false},6:{sorter:'digit'},7:{sorter:'digit'}}, sortList:[[1,0]], widgets: ['zebra']});
	});
		
</script>