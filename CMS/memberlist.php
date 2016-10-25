<?php 
include('header.php');
include 'db/codelib.php';
include('db/memberlib.php');

$searchInfo = getMember(null);
$members = null;//getAllMember();
$searchInfo->pageIndex = 1;
$searchInfo->pageSize = 20;
$searchInfo->scrollTop = 0;
$countItem = 0;

if($_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_SESSION['searchMember']))
	{
		$searchInfo = unserialize($_SESSION['searchMember']);
		if(!isset($searchInfo->pageSize) || $searchInfo->pageSize == ''){
			$searchInfo->pageSize = 20;
		}
		if(isset($_GET['p'])){
			$searchInfo->pageIndex = $_GET['p'];
		}
		$members = searchMember($searchInfo, $countItem);
	}
}
else if($_SERVER["REQUEST_METHOD"] == "POST")
{

// 	$act = $_POST['act'];
// 	if(isset($act) && $act === 'delete')
// 	{
// 		DeleteAllBukken($_POST['pid']);
// 	}
	#削除
	$searchInfo->memberNo = $_POST['memberNo'];
	$searchInfo->memberName = $_POST['memberName'];
	$searchInfo->tel = $_POST['tel'];
	if(isset($_POST['hopeArea'])){
		$searchInfo->hopeArea = implode(',', $_POST['hopeArea']);
	}
	else{
		$searchInfo->hopeArea = null;
	}
	$searchInfo->hopePriceFrom = $_POST['hopePriceFrom'];
	$searchInfo->hopePriceTo = $_POST['hopePriceTo'];
	$searchInfo->hopeSquareFrom = $_POST['hopeSquareFrom'];
	$searchInfo->hopeSquareTo = $_POST['hopeSquareTo'];
	$searchInfo->hopeYear = $_POST['hopeYear'];
		
	$searchInfo->pageSize = $_POST['pageSize'];
	if(!isset($searchInfo->pageSize) || $searchInfo->pageSize == '' || $searchInfo->pageSize == 0){
		$searchInfo->pageSize = 20;
	}
	$searchInfo->pageIndex = 1;
	$searchInfo->sortField = $_POST['sortField'];
	$searchInfo->sortOrder = $_POST['sortOrder'];
	$searchInfo->scrollTop = $_POST['scrollTop'];
	
	$_SESSION['searchMember'] = serialize($searchInfo);
	$members = searchMember($searchInfo, $countItem);
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
		<font style="font-weight: 800">&nbsp;会員情報検索結果一覧</font>
		
	</h1>
</div>
<div id="pageTitle">会員情報検索結果一覧</div>

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

<form id="frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="act" id="hidAct"></input>
	<input type="hidden" name="pid" id="hidPid"></input>
	
	<table class="dataTbl"> 		
		<tr>
			<th colspan="2" style="width:15%">会員番号</th>
			<td><input type="text" name="memberNo" size="12" style="ime-mode:active" value="<?php echo $searchInfo->memberNo?>"></td>
			<th style="width:15%">氏名</th>
			<td><input type="text" name="memberName" size="12" style="ime-mode:active" value="<?php echo $searchInfo->memberName?>"></td>
			<th style="width:15%">電話番号</th>
			<td><input type="text" name="tel" size="12" style="ime-mode:disable" value="<?php echo $searchInfo->tel?>"></td>
		</tr>
		<tr>
			<th rowspan="4" style="width:15px;text-align: center;border-right: 1px solid #CCC;">希<br>望<br>条<br>件</th>
			<th>希望エリア</th>
			<td colspan="5">
				<?php MakeCodeMstMultiCheckbox('0028', 'hopeArea', $searchInfo->hopeArea, 6);?>
			</td>
		</tr>
		
		<tr>
			<th>価格帯</th>
		<td colspan="5">
			<select name="hopePriceFrom" id="lstHopePriceFrom" >
				<option value="">下限なし</option>
				<?php MakeCodeMstCombo('0027', false, $searchInfo->hopePriceFrom)?>
			</select>&nbsp;～
			<select name="hopePriceTo" id="lstHopePriceTo" >
				<?php MakeCodeMstCombo('0027', false, $searchInfo->hopePriceTo)?>
				<option value="" <?php if(isNull($searchInfo->hopePriceTo) || $searchInfo->hopePriceTo == 0) echo 'selected="selected"' ?>>上限なし</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style="border-bottom: 1px solid #CCC;">専有面積</th>
		<td colspan="5" style="border-bottom: 1px solid #CCC;">
			<select name="hopeSquareFrom" id="lstHopeSquareFrom" >
				<option value="">下限なし</option>
				<?php MakeCodeMstCombo('0026', false, $searchInfo->hopeSquareFrom)?>
			</select>&nbsp;～
			<select name="hopeSquareTo" id="lstHopeSquareTo" >
				<?php MakeCodeMstCombo('0026', false, $searchInfo->hopeSquareTo)?>
				<option value="" <?php if(isNull($searchInfo->hopeSquareTo) || $searchInfo->hopeSquareTo == 0) echo 'selected="selected"' ?>>上限なし</option>
			</select>
		</td>
	</tr>
	<tr style="display:none">
		<th>築年数</th>
		<td colspan="5">
			<?php MakeCodeMstRadio('0025', 'hopeYear', $searchInfo->hopeYear, null);?>
		</td>
	</tr>
		
	</table>
	<input type="hidden" name="pageSize" id="hidPageSize" value="<?php echo $searchInfo->pageSize?>">
	<input type="hidden" name="sortField" id="sortField" value="<?php echo $searchInfo->sortField?>">
	<input type="hidden" name="sortOrder" id="sortOrder" value="<?php echo $searchInfo->sortOrder?>">
	<input type="hidden" name="scrollTop" id="scrollTop" value="<?php echo $searchInfo->scrollTop?>">
</form>
<br>
<div align="center">
	<a href='memberdetail.php'><img src="images/global/demobtn_register.gif" alt="新規登録" border="0" id="imgRegister"></a>
	<a href="#" onclick="document.forms['frm'].submit();" >
		<img src="images/global/demobtn_search.gif" alt="検索" border="0"></a>
	<a href="#"><img src="images/global/demobtn_clear.gif" alt="クリア" border="0" onclick="ClearCon()" ></a>
</div>

</div>

<?php if(isset($members)){?>
<div class="pagesize">
ページ行数
	<select id="pageSize" onchange="submit()">
		<option value="1">&nbsp;1件</option>
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
			print('<a href="memberlist.php?p='.$previous.'">&nbsp;<<&nbsp;</a>');
		}
		
		if ($start + 1 > $eitherside) print (" .... ") ;	
		$pageIndex = 1;
		for($y = 0; $y < $countItem; $y += $searchInfo->pageSize) {
			$class = ($y == $start) ? "pageselected" : "";
			if (($y > ($start - $eitherside)) && ($y < ($start + $eitherside))) {
				?>
					&nbsp;<a class="<?php print($class);?>" href="<?php print("memberlist.php".($pageIndex>0?("?p=").$pageIndex:""));?>"><?php print($pageIndex);?></a>&nbsp; 
				<?php
		    }
		    $pageIndex++;
		}
		if (($start + $eitherside) < $countItem) print (" .... ") ;
		
		if($searchInfo->pageIndex < $max_pages){
			$next = $searchInfo->pageIndex + 1;
			print('<a href="memberlist.php?p='.$next.'">&nbsp;>>&nbsp;</a>');
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
<table class="listTbl tablesorter" id="tablesorter">
	<thead>
		<tr>	
			<th class="sorter-noSort" style="width:56px !important">詳細</th>	
			<th <?php showSortClass('memberNo', $searchInfo)?>  onclick="sort(this, 'memberNo')">会員番号</th>
			<th <?php showSortClass('memberName', $searchInfo)?>  onclick="sort(this, 'memberName')">会員名</th>
			<th class="sorter-noSort" >電話番号</th>
			<th class="sorter-noSort" >メールアドレス</th>
			
		</tr>
	</thead>
	<tbody>	
		<?php
		$index = -1;
		foreach($members as $member){
			$index++;
			$class = 'odd';
			if($index % 2 > 0) $class = 'even';
		?>
		<tr <?php echo 'class="'.$class.'"'?>>
			<td>
				<a style="width:55px" href="memberdetail.php?pid=<?php echo $member->pid ?>">
					<img src="images/global/demobtn_s_renewal.gif" alt="詳細" border="0" >
				</a>
			</td>
			<td><?php echo $member->memberNo?> </td>
			<td><?php echo $member->memberName?></td>
			<td><?php echo $member->tel;?></td>
			<td><?php echo $member->email;?></td>
		</tr>
		<?php }?>
	
	</tbody>
</table>
<?php }?>

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


/**/
function scrollSearchDiv(){
	if($('#searchDiv').is(':visible')){
		$('#imgSwitch').attr('src', 'images/global/north-mini.png');
		localStorage.setItem('mbSearchShow', 0);
	}		
	else {
		$('#imgSwitch').attr('src', 'images/global/south-mini.png');
		localStorage.setItem('mbSearchShow', 1);
	}
	$('#searchDiv').slideToggle("slow");
}

function ClearCon()
{
	//$('#objectName').val('');
	$('input[type=text]').each(function(){
		$(this).val('');
	});

	$('select').each(function(){
		$(this).val('');
	});

	$("input[type=checkbox]:checked").each(function () {
		$(this).removeAttr("checked");
	});	
}

</script>

<script type="text/javascript" src="./js/jquery-latest.min.js" charset="utf-8"></script>
<script type="text/javascript" src="./js/jquery.tablesorter.js" charset="utf-8"></script>

<script type="text/javascript">
	$(document).ready(function(){
		//$("#tablesorter").tablesorter({headers: {0:{sorter:false},1:{sorter:true},2:{sorter:true},3:{sorter:false},4:{sorter:false},5:{sorter:false}}, sortList:[[1,0]], widgets: ['zebra']});
		//$(window).scrollTop(<?php echo $searchInfo->scrollTop?>);

		$("html, body").animate({ scrollTop: "<?php echo $searchInfo->scrollTop?>px" }, 300);
		
		if(localStorage.getItem('mbSearchShow') == 0){
			$('#searchDiv').hide();
		}
		else {
			$('#searchDiv').show();
		}
	});
		
</script>
