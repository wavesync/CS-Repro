<?php include('header.php');?>
<?php include('db/bukkenlib.php');?>
<?php include('db/codelib.php');?>

<?php

	$isShowTableHeader = false;	
	$searchInfo = getBukken(null);
	
	$searchInfo->memberFlg = '02';
	$searchInfo->pageIndex = 1;
	$searchInfo->pageSize = 20;	
	$searchInfo->scrollTop = 0;
	$countItem = 0;
	
	if($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_SESSION['searchObject']))
		{			
			$searchInfo = unserialize($_SESSION['searchObject']);
			
			if(!isset($searchInfo->pageSize) || $searchInfo->pageSize == ''){
				$searchInfo->pageSize = 20;
			}
			if(isset($_GET['p'])){
				$searchInfo->pageIndex = $_GET['p'];
			}
			
			$objectList = searchBukken($searchInfo, $countItem);	
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
		$searchInfo->objectCode = $_POST['objectCode'];
		$searchInfo->publishFlg = $_POST['publishFlg'];		
		if(isset($_POST['address'])){
			$searchInfo->address = implode(',', $_POST['address']);
		}
		else{
			$searchInfo->address = null;
		}
		
		$searchInfo->senyuAreaFrom = $_POST['senyuAreaFrom'];
		$searchInfo->senyuAreaTo = $_POST['senyuAreaTo'];
		$searchInfo->priceFrom = $_POST['priceFrom'];
		$searchInfo->priceTo = $_POST['priceTo'];
		if(isset($_POST['madori'])){
			$searchInfo->madori = implode(',', $_POST['madori']);
		}
		else{
			$searchInfo->madori = null;
		}
		$searchInfo->pageSize = $_POST['pageSize'];
		if(!isset($searchInfo->pageSize) || $searchInfo->pageSize == '' || $searchInfo->pageSize == 0){
			$searchInfo->pageSize = 20;
		}
		$searchInfo->pageIndex = 1;
		$searchInfo->sortField = $_POST['sortField'];
		$searchInfo->sortOrder = $_POST['sortOrder'];
		$searchInfo->scrollTop = $_POST['scrollTop'];
		
		$_SESSION['searchObject'] = serialize($searchInfo);
		
		$objectList = searchBukken($searchInfo, $countItem);
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
	
	function showSortClass($field, $searchInfo){
		$class = 'sorter-header';
		if($searchInfo->sortField == $field){
			$class = 'sorter-headerAsc';
			if($searchInfo->sortOrder == 'desc'){
				$class = 'sorter-headerDesc';
			}
		}
		echo 'class="'.$class.'"';
	}
	
?>
<div id="hd2">
	<h1>		
		<a href="menu.php" id="menuTopLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" >
			管理メニュートップ</a>≫		
		<?php if($isShowTableHeader){?>
		<font style="font-weight: 800">&nbsp;物件情報検索結果一覧</font>
		<?php }else{?>
		<font style="font-weight: 800">&nbsp;物件情報検索</font>
		<?php }?>
	</h1>
</div>
<div id="pageTitle">
	<?php if($isShowTableHeader){?>
	物件情報検索結果一覧
	<?php }else{?>
	物件情報検索
	<?php }?>
</div>
<div id="pageDiscription" style="display:none">
	<?php if($isShowTableHeader){?>
	登録物件の検索結果です。再検索される場合は該当項目を選択し「検索」ボタンを押してください。<br/>
	新規登録の場合は「新規登録」ボタンを押してください。
	<?php }else{?>
	新規登録の場合は「新規登録」ボタンを、既に登録済み情報の更新の場合は、該当項目を選択し「検索」ボタンを押してください。<br/>
	尚、<font color="#F67F05">登録済み情報を全件表示</font>する場合は、項目を選択せず、「検索」ボタンを押してください。
	<?php }?>
</div>
<br>
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
		<td>
			<input type=radio name="memberFlg" value="02" <?php if($searchInfo->memberFlg == "02"){echo 'checked';}  ?>/>東日本レインズ
			<input type="radio" name="memberFlg" value="00" <?php if($searchInfo->memberFlg == "00"){echo 'checked';}?> />自社
			<input type="radio" name="memberFlg" value="01" <?php if($searchInfo->memberFlg == "01"){echo 'checked';}  ?>/>他社			
		</td>
		<th nowrap width="15%" >ネット公開</th> 
		<td nowrap> 
			<input type="radio" name="publishFlg" value="" <?php if(isset($searchInfo) && $searchInfo->publishFlg == ""){echo 'checked';}?> />全て
			<input type="radio" name="publishFlg" value="01" <?php if(isset($searchInfo) && $searchInfo->publishFlg == "01"){echo 'checked';}  ?>/>公開中
			<input type=radio name="publishFlg" value="00" <?php if(isset($searchInfo) && $searchInfo->publishFlg == "00"){echo 'checked';}  ?>/>非公開 
		</td> 
	</tr>
	<tr>
		<th>建物コード</th>
		<td>
			<input type="text" name="objectCode" id="objectCode" size="20" style="ime-mode:active" value="<?php echo $searchInfo->objectCode ?>" /> 
		</td>
		<th>建物名</th>
		<td>
			<input type="text" name="objectName" id="objectName" size="40" style="ime-mode:active" value="<?php echo $searchInfo->objectName ?>" /> 
		</td>
	</tr>
	<tr>
		<th>エリア</th>
		<td colspan="3">
			<?php MakeCodeMstMultiCheckbox('0028', 'address', $searchInfo->address, 6);?>
		</td>
	</tr>
	<tr>
		<th>専有面積</th>
		<td>
			<input type="text" name="senyuAreaFrom" maxlength="6" style="ime-mode:disable;width:80px;text-align: right" value="<?php echo $searchInfo->senyuAreaFrom ?>">&nbsp;㎡～
			<input type="text" name="senyuAreaTo" maxlength="6" style="ime-mode:disable;width:80px;text-align: right" value="<?php echo $searchInfo->senyuAreaTo ?>">&nbsp;㎡
		</td>
		<th>物件価格</th>
		<td>
			<input type="text" name="priceFrom" maxlength="6" style="ime-mode:disable;width:120px;text-align: right" value="<?php echo $searchInfo->priceFrom ?>">&nbsp;万円～
			<input type="text" name="priceTo" maxlength="6" style="ime-mode:disable;width:120px;text-align: right" value="<?php echo $searchInfo->priceTo ?>">&nbsp;万円
		</td>
	</tr>
	<tr>
		<th>間取り</th>
		<td colspan="3"><?php MakeCodeMstMultiCheckbox('0015', 'madori', $searchInfo->madori, 10);?></td>
	</tr>
	
	
</table> 
<br>
<input type="hidden" name="pageSize" id="hidPageSize" value="<?php echo $searchInfo->pageSize?>">
<input type="hidden" name="sortField" id="sortField" value="<?php echo $searchInfo->sortField?>">
<input type="hidden" name="sortOrder" id="sortOrder" value="<?php echo $searchInfo->sortOrder?>">
<input type="hidden" name="scrollTop" id="scrollTop" value="<?php echo $searchInfo->scrollTop?>">
</form>

<div align="center">
	<a href='bukkendetail.php'>
		<img src="images/global/demobtn_register.gif" alt="新規登録" border="0" id="imgRegister"></a>
	<a href="#" onclick="submit();" >
		<img src="images/global/demobtn_search.gif" alt="検索" border="0"></a>
	<a href="#">
		<img src="images/global/demobtn_clear.gif" alt="クリア" border="0" onclick="ClearCon()" ></a>
</div>

</div>

<br>
<?php if(isset($objectList)){?>
<div class="pagesize">
ページ行数
	<select id="pageSize" onchange="submit()">
		<option value="20">&nbsp;20件</option>
		<option value="30">&nbsp;30件</option>
		<option value="40">&nbsp;40件</option>
		<option value="50">&nbsp;50件</option>
		<option value="100">&nbsp;100件</option>
	</select>
	<script>
		$('#pageSize').val(<?php echo $searchInfo->pageSize?>);		
	</script>
	
	<?php 
	/*ページング*/
	$start = $searchInfo->pageSize*($searchInfo->pageIndex - 1);
	$showPage = 5; //ページャー数表示
	$max_pages = ceil ( $countItem / $searchInfo->pageSize ); // ページ数
	
	//ページ数は1以上
	if($max_pages > 1){
		$eitherside = ($showPage * $searchInfo->pageSize);
		
		//前
		print('&nbsp;&nbsp;');
		if($searchInfo->pageIndex > 1){
			$previous = $searchInfo->pageIndex - 1;
			print('<a href="bukkenlist.php?p='.$previous.'">&nbsp;<<&nbsp;</a>');
		}
		
		if ($start + 1 > $eitherside) print (" .... ") ;	
		$pageIndex = 1;
		for($y = 0; $y < $countItem; $y += $searchInfo->pageSize) {
			$class = ($y == $start) ? "pageselected" : "";
			if (($y > ($start - $eitherside)) && ($y < ($start + $eitherside))) {
				?>
					&nbsp;<a class="<?php print($class);?>" href="<?php print("bukkenlist.php".($pageIndex>0?("?p=").$pageIndex:""));?>"><?php print($pageIndex);?></a>&nbsp; 
				<?php
		    }
		    $pageIndex++;
		}
		if (($start + $eitherside) < $countItem) print (" .... ") ;
		
		if($searchInfo->pageIndex < $max_pages){
			$next = $searchInfo->pageIndex + 1;
			print('<a href="bukkenlist.php?p='.$next.'">&nbsp;>>&nbsp;</a>');
		}
	}
	
	?>
	
</div>
<div class="pager">全<font color="red"><?php echo $countItem?></font>
<?php	
	$begin = $searchInfo->pageSize*($searchInfo->pageIndex - 1) + 1;
	$end = $begin - 1 + $searchInfo->pageSize;
	if($end > $countItem) $end = $countItem;
	echo '件中：'.$begin.'-'.$end.'件目';
?>

</div>
<table  cellspacing="1" cellpadding="0" class="listTbl">
	<tr>
		<td colspan="9" id="tableHeader">登録情報検索結果</td>
	</tr>
</table>
<table class="listTbl" id="tablesorter">
	<thead>
	<tr>
		<th class="sorter-noSort" style="width:56px !important">詳細</th>
		<th <?php showSortClass('objectCode', $searchInfo)?> style="width:80px !important" onclick="sort(this, 'objectCode')">建物番号</th>		
		<th <?php showSortClass('objectCodeReins', $searchInfo)?> style="width:120px !important" onclick="sort(this, 'objectCodeReins')">レインズ物件番号</th>
		<th class="sorter-noSort">建物名</th>
		<th class="sorter-noSort" >路線<br>駅</th>
		<th class="sorter-noSort" >所在地</th>
		<th class="sorter-noSort" style="width:50px !important">間取<br>階数</th>
		<th <?php showSortClass('senyuArea', $searchInfo)?> style="width:80px !important" onclick="sort(this, 'senyuArea')">専有面積</th>
		<th <?php showSortClass('price', $searchInfo)?>  onclick="sort(this, 'price')">価格</th>				
	</tr>
	</thead>
	<tbody>
	<?php
		$index = -1;
		foreach($objectList as $bukken){
			$index++;
			$class = 'odd';
			if($index % 2 > 0) $class = 'even';
	?>
		<tr <?php echo 'class="'.$class.'"'?>>
			<td style="width:56px !important">
				<a style="width:55px" href="bukkendetail.php?pid=<?php echo $bukken->pid ?>">
					<img src="images/global/demobtn_s_renewal.gif" alt="詳細" border="0" >
				</a>
				<a style="width:55px;display:none" href="#" onclick="javascript:DeleteItem(<?php echo $bukken->pid?>);"  >
					<img src="images/global/demobtn_s_delete.gif" alt="削除" border="0" />
				</a>
			</td>
			<td class="textcenter"><?php echo $bukken->objectCode ?></td>
			<td class="textcenter"><?php echo $bukken->objectCodeReins ?></td>
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
				<?php echo displayPrice($bukken->price)?> 
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

function submit(){
	$('#hidPageSize').val($('#pageSize').val());
	$('#scrollTop').val($(window).scrollTop());
	document.forms['frm'].submit();
}

function sort(obj, col){
	order = 'asc';
	if(obj.className == 'sorter-headerAsc') order = 'desc';
	$('#sortField').val(col);
	$('#sortOrder').val(order);
	$('#scrollTop').val($(window).scrollTop());
	document.forms['frm'].submit();
}

function ClearCon()
{
	//$('#objectName').val('');
	$('input[type=text]').each(function(){
		$(this).val('');
	});
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
	
	$("input[type=checkbox]:checked").each(function () {
		$(this).removeAttr("checked");
	});	
}
/**/
function scrollSearchDiv(){
	if($('#searchDiv').is(':visible')){
		$('#imgSwitch').attr('src', 'images/global/north-mini.png');	
		localStorage.setItem('bkSearchShow', 0);	
	}		
	else {
		$('#imgSwitch').attr('src', 'images/global/south-mini.png');
		localStorage.setItem('bkSearchShow', 1);		
	}
	$('#searchDiv').slideToggle("slow");
}

</script>

<script type="text/javascript" src="./js/jquery-latest.min.js" charset="utf-8"></script>
<script type="text/javascript" src="./js/jquery.tablesorter.js" charset="utf-8"></script>

<script type="text/javascript">
	$(document).ready(function(){
		//$("#tablesorter").tablesorter({headers: {0:{sorter:false},1:{sorter:true},2:{sorter:true},3:{sorter:true},4:{sorter:false},5:{sorter:false},6:{sorter:false},7:{sorter:'digit'},8:{sorter:'digit'}}, sortList:[[1,0]], widgets: ['zebra']});
		//$(window).scrollTop(<?php echo $searchInfo->scrollTop?>);
		$("html, body").animate({ scrollTop: "<?php echo $searchInfo->scrollTop?>px" }, 300);
		if(localStorage.getItem('bkSearchShow') == 0){
			$('#searchDiv').hide();
		}
		else {
			$('#searchDiv').show();
		}
	});
		
</script>