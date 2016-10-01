<?php 
include('header.php');
include('db/memberlib.php');
?>


<div id="hd2">
	<h1>		
		<a href="menu.php" id="menuTopLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" >
			管理メニュートップ</a>≫
		<font style="font-weight: 800">&nbsp;会員一覧</font>
		
	</h1>
	<a href="logout.php" style="float: right;"><img src="images/global/btn_logout.gif" alt="ログアウト" border="0" id="imgLogout"></a>
</div>
<div id="pageTitle">会員情報 ≫一覧</div>


<form id="frm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="act" id="hidAct"></input>
	<input type="hidden" name="pid" id="hidPid"></input>
</form>
<div align="right" style="padding-right:10px;margin-bottom:10px;">
	<a href='memberdetail.php'>
		<img src="images/global/demobtn_register.gif" alt="新規登録" border="0" id="imgRegister">
	</a>	
</div>
<?php
	$members = getAllMember();
	if(sizeof($members) > 0){?>
<div class="pager">全<font color="red"><?php echo sizeof($members)?></font>件中：1-<?php echo sizeof($members)?>件目</div>
<?php }?>
<table  cellspacing="1" cellpadding="0" class="listTbl tablesorter" id="tablesorter">
	<thead>
	<tr>		
		<th class="textcenter" >会員番号</th>
		<th class="textcenter" >会員名</th>
		<th class="textcenter" >電話番号</th>
		<th class="textcenter" >メールアドレス</th>
		<th class="textcenter" ></th>
	</tr>	
	<?php foreach($members as $member){ ?>
	<tr>
		<td><?php echo $member->memberNo?> </td>
		<td><?php echo $member->memberName?></td>
		<td><?php echo $member->tel;?></td>
		<td><?php echo $member->email;?></td>
		<td class="textcenter"><a href="memberdetail.php?pid=<?php echo $member->pid?>">編集</a></td>
	</tr>
	<?php }?>
	</thead>
	
</table>

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
</script>
