<?php 
include('header.php');
include 'db/codelib.php';
include('db/memberlib.php');

$searchInfo = getMember(null);
$members = null;//getAllMember();

if($_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_SESSION['searchMember']))
	{
		$arr = $_SESSION['searchMember'];
		$searchInfo->memberName = $arr[0];
		$searchInfo->tel = $arr[1];
		$searchInfo->hopeArea = $arr[2];
		$searchInfo->hopePriceFrom = $arr[3];
		$searchInfo->hopePriceTo = $arr[4];
		$searchInfo->hopeSquareFrom = $arr[5];
		$searchInfo->hopeSquareTo = $arr[6];
		$searchInfo->hopeYear = $arr[7];

		$members = searchMember($searchInfo);
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
		
	$_SESSION['searchMember'] = array($_POST['memberName'], $_POST['tel'], $searchInfo->hopeArea, $_POST['hopePriceFrom'], $_POST['hopePriceTo'],
			$_POST['hopeSquareFrom'], $_POST['hopeSquareTo'],$_POST['hopeYear']);
	$members = searchMember($searchInfo);
}

?>


<div id="hd2">
	<h1>		
		<a href="menu.php" id="menuTopLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" >
			管理メニュートップ</a>≫
		<font style="font-weight: 800">&nbsp;会員一覧</font>
		
	</h1>
</div>
<div id="pageTitle">会員情報 ≫一覧</div>

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
	
	<table class="dataTbl tablesorter" id="tablesorter"> 		
		<tr>
			<th style="width:15%">氏名</th>
			<td><input type="text" name="memberName" size="12" style="ime-mode:active"></td>
			<th style="width:15%">電話番号</th>
			<td><input type="text" name="tel" size="12" style="ime-mode:disable"></td>
		</tr>
		<tr>
			<th>希望エリア</th>
			<td colspan="3">
				<?php MakeCodeMstMultiCheckbox('0028', 'hopeArea', $searchInfo->hopeArea, 6);?>
			</td>
		</tr>
		
		<tr>
			<th>ご予算</th>
		<td colspan="3">
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
		<th>専有面積</th>
		<td colspan="3">
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
	<tr>
		<th>築年数</th>
		<td colspan="3">
			<?php MakeCodeMstRadio('0025', 'hopeYear', $searchInfo->hopeYear, null);?>
		</td>
	</tr>
		
	</table>
</form>
<br>
<div align="center">
	<a href='memberdetail.php'><img src="images/global/demobtn_register.gif" alt="新規登録" border="0" id="imgRegister"></a>
	<a href="#" onclick="document.forms['frm'].submit();" >
		<img src="images/global/demobtn_search.gif" alt="検索" border="0"></a>
	<a href="#"><img src="images/global/demobtn_clear.gif" alt="クリア" border="0" onclick="ClearCon()" ></a>
</div>

</div>

<?php if(isset($members) && sizeof($members) > 0){?>
<div class="pager">全<font color="red"><?php echo sizeof($members)?></font>件中：1-<?php echo sizeof($members)?>件目</div>

<table class="listTbl tablesorter" id="tablesorter">
	<thead>
		<tr>		
			<th class="textcenter" >会員番号</th>
			<th class="textcenter" >会員名</th>
			<th class="textcenter" >電話番号</th>
			<th class="textcenter" >メールアドレス</th>
			<th class="textcenter" ></th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach($members as $member){ ?>
		<tr>
			<td><?php echo $member->memberNo?> </td>
			<td><?php echo $member->memberName?></td>
			<td><?php echo $member->tel;?></td>
			<td><?php echo $member->email;?></td>
			<td class="textcenter"><a href="memberdetail.php?pid=<?php echo $member->pid?>">編集</a></td>
		</tr>
		<?php }?>
	
	</tbody>
</table>
<?php }?>

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
		$("#tablesorter").tablesorter({headers: {0:{sorter:true},1:{sorter:true},2:{sorter:false},3:{sorter:false},4:{sorter:false},5:{sorter:false}}, sortList:[[0,0]], widgets: ['zebra']});
	});
		
</script>
