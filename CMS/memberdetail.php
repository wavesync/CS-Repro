<?php 
include('header.php');
include 'db/codelib.php';
include 'db/memberlib.php';

?>
<script src="js/tab.js"></script>
<br/>
<div id="pageTitle">
会員情報≫ 会員情報詳細
</div>
<br/>
<?php
	$info = null;
	$error = '';
	$action = 0;
	
	if($_SERVER["REQUEST_METHOD"] == "GET")
	{				
		if(isset($_GET['pid']))
		{
			$info = getMember($_GET["pid"]);						
		}
		else {			
			$info = getMember(null);
		}
	}
	else if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_POST['pid']) && $_POST['pid'] > 0)
		{
			$info = getMember($_POST['pid']);
		}
		else {
			$info = getMember(null);
		}
		
		bindMember($info); //POSTから会員情報を取得
		
		$deleteFlg = '00';
		if($_POST['deleteFlg'] === '01') $deleteFlg = '01';
		$info->deleteFlg = $deleteFlg;
				
		$action = $_POST['action'];
		$error = validMember($info);	
		
		if($error == '')
		{			
			if($action == 0)
			{
				$action = 1;
			}
			else if($action == 2)
			{				
				saveMember($info);
				header('Location:memberlist.php?back');
				exit;
			}
		}
		else
		{
			$action = 0;
		}		
		
	}
		
?>

<div align="center"> 
	<a href="memberlist.php"><img src="images/global/demobtn_list.gif" alt="一覧へ" border="0" ></a>
	<a href="#" onclick="document.forms['frm'].submit()">
		<img src="images/global/demobtn_confirm.gif" alt="確認" border="0" />
	</a> 		
</div>
<font color="red">
	<ul>
		<?php echo $error ?>
	</ul>
</font>
<br>
<form id="frm" method="post" action="" ENCTYPE="multipart/form-data" >
<input type="hidden" name="pid" value="<?php echo $info->pid ?>" id="pid">
<input type="hidden" name="action" value="<?php echo $action ?>" id="action">
<table class="dataTbl">
	<tr>
		<th width="15%" >コード</th>
		<td colspan="3">			
			<input type="text" name="memberNo"  readonly="readonly" style="width:150px" value="<?php echo $info->memberNo; ?>"  /> 			
		</td>
	</tr>
	<tr>
		<th>氏名(<span class="hissu">*</span>)</th>
		<td colspan="3">
			<input type="text" name="memberName" style="width:150px;ime-mode:active" value="<?php echo $info->memberName ?>">
		</td>		
	</tr>	
	<tr>
		<th >住所(<span class="hissu">*</span>)</th>
		<td colspan=3 >
			<table>
				<tr>
					<td valign="top" class="noborder">
						〒<input type="text" name="zipCode" size="8" maxlength="7" style="ime-mode:disabled" id="txtZip" value="<?php echo $info->zipCode ?>"/>
						<a href="javascript:OpenZip(document.getElementById('txtZip').value);">
							<img src='./images/global/btn2_search.png' alt="検索" border="0" style="margin-bottom: -5px;" /></a>
					</td>
					<td class="noborder">
						<table>
							<tr>
								<th class="noborder">都道府県</th>
								<td class="noborder"><input type="text" name="address1" size="20" maxlength="8" id="txtAddress1" style="ime-mode:active" value="<?php echo $info->address1 ?>" /></td>
								<th class="noborder">市区町村</th>
								<td class="noborder" colspan="3"><input type="text" name="address2" size="20" maxlength="64" id="txtAddress2" style="ime-mode:active" value="<?php echo $info->address2 ?>" /></td>								
							</tr>
							<tr>
								<th class="noborder">町丁目</th>
								<td class="noborder"><input type="text" name="address3" size="20" maxlength="128" id="txtAddress3" style="ime-mode:active" value="<?php echo $info->address3 ?>" /></td>
								<th class="noborder">番地・号</th>
								<td class="noborder"><input type="text" name="address4" size="20" maxlength="64" id="txtAddress4" style="ime-mode:active" value="<?php echo $info->address4 ?>" /></td>
								<th class="noborder">建物名・部屋番号</th>
								<td class="noborder"><input type="text" name="address5" size="20" maxlength="64" id="txtAddress5" style="ime-mode:active" value="<?php echo $info->address5 ?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
									
		</td>
	</tr>
		<tr>
		<th>電話番号(<span class="hissu">*</span>)</th>
		<td colspan="3">
			<input type="text" name="tel" style="width:150px;ime-mode:active" value="<?php echo $info->tel ?>">
		</td>		
	</tr>
	<tr>
		<th>メールアドレス(<span class="hissu">*</span>)</th>
		<td width="30%">
			<input type="text" name="email" style="width:300px;ime-mode:disabled" value="<?php echo $info->email ?>">
		</td>
		<th width="15%" >パスワード(<span class="hissu">*</span>)</th>
		<td width="30%">
			<input type="text" name="password" style="width:150px;ime-mode:disabled" value="<?php echo $info->password ?>">
		</td>
	</tr>	
	<tr>
		<th>当社からの連絡方法(<span class="hissu">*</span>)</th>
		<td>
			<input type="radio" name="connectMethod" id="connectMethod1" value="01" <?php if ($info->connectMethod == '01') {echo 'checked';}?>><label for="connectMethod1">電話</label>
			<input type="radio" name="connectMethod" id="connectMethod2" value="02" <?php if ($info->connectMethod == '02') {echo 'checked';}?>><label for="connectMethod2">メール</label>
			<input type="radio" name="connectMethod" id="connectMethod3" value="03" <?php if ($info->connectMethod == '03') {echo 'checked';}?>><label for="connectMethod3">郵送</label> 
		</td>
	
		<th>連絡希望時間(<span class="hissu">*</span>)</th>
		<td>
			<select name="connectTime" id="lstConnectTime" >	
				<?php MakeComboConnectTime(true, $info->connectTime);?> 
			</select>			
		</td>
	</tr>
	<tr>
		<th>家族構成</th>
		<td>
			<select name="family" id="lstFamily" >	
				<?php MakeComboFamily(true, $info->family);?> 
			</select>			
		</td>
		<th>年齢</th>
		<td>
			<select name="age" id="lstAge" >	
				<?php MakeComboAge(true, $info->age);?> 
			</select>			
		</td>
	</tr>
	<tr>
		<th>ご年収</th>
		<td>
			<select name="income" id="lstIncome" >	
				<?php MakeComboIncome(true, $info->income);?> 
			</select>万円			
		</td>
		<th>自己資金</th>
		<td>
			<select name="ownMoney" id="lstOwnMoney" >	
				<?php MakeComboIncome(true, $info->ownMoney);?> 
			</select>万円			
		</td>
	</tr>
	<tr>
		<th>その他</th>
		<td colspan="3">
			<textarea name="note" cols="100" rows="5"><?php echo $info->note;?></textarea>
		</td>
	</tr>
	<tr>
		<th>登録日</th>
		<td><input type="text" name="registerDate" style="width:150px;" readonly="readonly" value="<?php echo showDate($info->registerDate, 'Y/m/d'); ?>"></td>
		<th>削除フラグ</th>
		<td>
			<input type="checkbox" name="deleteFlg" value="01" <?php if($info->deleteFlg == '01') echo "checked";?> >
		</td>		
	</tr>					
</table>
</form>
<br/>

<div align="center"> 
	<a href="memberlist.php"><img src="images/global/demobtn_list.gif" alt="一覧へ" border="0" ></a>
	<a href="#" onclick="document.forms['frm'].submit()">
		<img src="images/global/demobtn_confirm.gif" alt="確認" border="0" />
	</a> 		
</div>

<!-- 子供一覧 -->
<div id="child" <?php if($info->pid <= 0) echo 'style="display:none"'?>>

<ul id="tablist">
<li><a href="#" class="current" onClick="return expandcontent('sc1', this)" theme="#EAEAFF">希望一覧</a></li>
<li><a href="#" onClick="return expandcontent('sc2', this)" theme="#EAEAFF">物件一覧</a></li>
</ul>

<DIV id="tabcontentcontainer">

<div id="sc1" class="tabcontent">
	
	<a href='#' onclick="newHope(<?php echo $info->pid?>)" style="float:right;padding:5px;padding-right:10px;">
		<img src="images/global/demobtn_register.gif" alt="新規登録" border="0" id="imgRegister">
	</a>
	<table class="listTbl tablesorter" id="tblHope" style="width:1100px;margin-top:10px;margin-bottom:10px;">
	<thead>
	<tr>		
		<th></th>
		<th class="textcenter" >地域</th>
		<th class="textcenter" >価格</th>
		<th class="textcenter" >専有面積</th>
		<th class="textcenter" >路線</th>
		<th class="textcenter" >駅</th>
		<th class="textcenter" >駅徒歩</th>
	</tr>	
	</thead>	
	<?php
	$hopes = array();
	$bukkens = array();
	if(isset($info->pid) && $info->pid > 0){
		$hopes = getHope($info->pid);
		$bukkens = getCareBukken($info->pid);
	}	
	
	?>
	
	<?php foreach($hopes as $hope){ ?>
	<tr>
		<td class="textcenter"><a href="#" onclick="hopeDetail(<?php echo $hope->pid?>)">詳細</a></td>
		<td><?php echo $hope->hopeArea?> </td>
		<td class="textcenter" ><?php echo displayFromTo($hope->hopePriceFrom, $hope->hopePriceTo, 10000, '万円')?></td>
		<td class="textcenter" ><?php echo displayFromTo($hope->hopeSquareFrom,$hope->hopeSquareTo, 1, '㎡');?></td>		
		<td><?php echo $hope->hopeLine;?></td>
		<td>
			<?php
				echo str_replace('|', '<br>', str_replace('-', ': ', $hope->hopeStation));
			?>
		</td>
		<td class="textcenter" ><?php echo $hope->hopeWalk;?></td>
	</tr>
	<?php }?>
	
	</table>

</div>

<div id="sc2" class="tabcontent">
	<table class="listTbl tablesorter" id="tblBukken" style="width:1100px;margin-top:10px;margin-bottom:10px;">
	<thead>
	<tr>		
		<th class="textcenter" >物件コード</th>
		<th class="textcenter" >物件名</th>
		<th class="textcenter" >住所</th>
		<th class="textcenter" >価格</th>
	</tr>	
	</thead>
	<?php foreach($bukkens as $bk){
	?>
	<tr>
		<td class="textcenter" ><a href="bukkendetail.php?pid=<?php echo $bk->pid?>" target="_blank"><?php echo $bk->objectCode?></a></td>
		<td><?php echo $bk->objectName?></td>
		<td><?php echo $bk->address?></td>
		<td class="textcenter" ><?php echo ($bk->price/10000).'万円'?></td>
	</tr>
	<?php }?>	
	</table>
</div>

</DIV>

</div>
<!-- 子供一覧 -->

<br>
<script language="javascript">
<?php if($action == 1)
{?>
	if(confirm('登録しますか？'))
	{
		document.getElementById('action').value = '2';
		document.forms['frm'].submit();
	}
	else
	{
		document.getElementById('action').value = '0';
	}
<?php } ?>

function hopeDetail(id){
	url = "hopedetail.php?pid=" + id;	
	popup = window.open(url,"_blank","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=1500,height=900");
}

function newHope(id){
	url = "hopedetail.php?memberInfoPid=" + id;	
	popup = window.open(url,"_blank","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=1500,height=900");
}

function OpenZip(zip)
{
	var url = "./Popup/searchZip.php?zip=" + zip;
	window.open(url,"zip","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=580,height=360");
}
function GetAddress(address)
{
	vals = address.split('|');
	$('#txtAddress1').val(vals[0]);
	$('#txtAddress2').val(vals[1]);
	$('#txtAddress3').val(vals[2]);
}


function openCity(evt, cityName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    $('#' + cityName).show();
    if(evt.currentTarget != undefined){
    	evt.currentTarget.className += " active";
    }
}
$('#hrefHope').trigger('click');
$('#hrefHope').addClass('active');

</script>

<br/>
<a href="menu.php" id="menuLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" > 《 管理メニュートップへ</a>
<br/><br/>

<?php include 'footer.php'; ?>