<?php include('header.php');?>
<?php include('db/codelib.php');?>
<?php include('db/bukkenlib.php');?>

<?php 	

	$bukken = getBukken(null);
	$error = '';
	$action = 0;

	if($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_GET['pid']))
		{			
			$bukken = getBukken($_GET['pid']);
		}
		else {
			$bukken = getBukken();
			$bukken->publishFlg = '00';
		}
	}	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{			
		if(isset($_POST['pid']))
		{
			$bukken = getBukken($_POST['pid']);	
		}	
		else {
			$bukken = getBukken();
		}
		
		bindBukken($bukken); //POSTから求人情報を取得
		saveBukken($bukken); //求人保存
		
		header('Location:bukkenlist.php?back');
	}	

	function CleanNumber($num)
	{
		if(isset($num) && $num != '')
		{
			if ($num==0) {return 0;}
			$pos = strpos($num, '.');
			if($pos !== false)
				return rtrim(rtrim($num, '0'), '.');
			else
				return $num;
		} 
		else
		{
			return '';
		}
	}
	
	#GetEquipTitle($bukken->getEquip());
?>


<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=AIzaSyCx1u5V1w_NHCC-AXcqBfKftzd87IHopQA" charset="utf-8"> 
</script>
<!-- 
 <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=AIzaSyAF7oEetuQF9y96FUi7LtURuZfeLVCqSQA" type="text/javascript" charset="utf-8"></script>
-->
  
<SCRIPT src="./js/jquery-latest.js"></SCRIPT>
<SCRIPT src="./js/gmap1.js"></SCRIPT>
<SCRIPT src="./js/gmap2.js"></SCRIPT>

<div id="hd2">
	<h1>
		<a href="menu.php" id="menuTopLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" >
			管理メニュートップ</a>≫
		<a href="bukkenlist.php" id="menuTopLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" >
			&nbsp;【売買物件】新規登録・登録情報検索 </a>≫
	<font style="font-weight: 800">&nbsp;【売買物件】物件情報詳細</font></h1>
	
</div>
<div id="pageTitle">
	【売買物件】物件情報詳細
</div>

<font color="red">
	<ul>
		<?php echo $error ?>
	</ul>
</font>
<br>
<div align="center"> 
	<a href="bukkenlist.php">
		<img src="images/global/demobtn_list.gif" alt="一覧へ" border="0" onmouseover="ImageMouse(this, 'demobtn_list_o.gif')" onmouseout="ImageMouse(this, 'demobtn_list.gif')"></a>	
	<a href="#" onclick="submitData();">
		<img src="images/global/demobtn_confirm.gif" alt="確認" border="0" onmouseover="ImageMouse(this, 'demobtn_confirm_o.gif')" onmouseout="ImageMouse(this, 'demobtn_confirm.gif')"/></a>	 	
</div>
<br>
<form id="frm" ENCTYPE="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="pid" id="pid" value="<?php echo $bukken->pid ?>">
<input type="hidden" name="action" id="action" value="<?php echo $action ?>">

<table class="dataTbl">
	<tr>
		<th colspan="2">自社・他社</th>
		<td>
			<input type="radio"  name="memberFlg" value="00" <?php if($bukken->memberFlg == "00"){echo 'checked';}?> />自社
			<input type="radio"  name="memberFlg" value="01" <?php if($bukken->memberFlg == "01"){echo 'checked';}  ?>/>他社
			<input type=radio  name="memberFlg" value="02" <?php if($bukken->memberFlg == "02"){echo 'checked';}  ?>/>東日本レインズ
		</td>
		<th>レインズ物件番号</th>
		<td><input type="text" name="objectCodeReins" readonly="readonly" maxlength="30" size="40" value="<?php echo $bukken->objectCodeReins ?>" /> </td>
	</tr>
	<tr>
		<th colspan="2" nowrap style="width:116px" >物件番号</th>
		<td style="width:280px">			
			<input type="text" name="objectCode" maxlength="30" size="15" readonly="readonly" value="<?php echo $bukken->objectCode ?>" />
			<?php if($bukken->objectCode !== null && $bukken->objectCode !== ''){?>
			<a href="javascript:BrowseFile('<?php echo $bukken->pid?>');">
				<img alt="写真ライブラリー" src='images/global/demobtn_s_register.gif' border="0" align="absmiddle" />
			</a> 	
			<?php }?>		
		</td>
		<th nowrap style="width:109px">物件名(<span class="hissu">*</span>)</th>
		<td>			
			<input type="text" name="objectName" style="ime-mode:active" maxlength="30" size="40" value="<?php echo $bukken->objectName ?>" /> 			
		</td>
	</tr>
	
	<tr>
		<th colspan="2">物件種別</th>
		<td><input type="text" style="ime-mode:active" maxlength="15" size="25" value="マンション専有" readonly="readonly" />
		</td>
		<th>取引態様(<span class="hissu">*</span>)</th>
		<td>			
			<select name="torihiki" id="slTorihiki">
				<?php 					
					MakeComboTorihiki(true, $bukken->torihiki);
				?>
			</select>
		</td>
	</tr>	
	<tr>
		<th colspan="2">インターネット(<span class="hissu">*</span>)</th>
		<td>
			<input type="radio" name="publishFlg" value="01" <?php if($bukken->publishFlg == "01"){echo 'checked';}  ?> ><span class="spanH">公開</span>&ensp;（			
			<input type="checkbox" name="topFlg" value="01" <?php if($bukken->topFlg === '01') echo 'checked' ?> ></input>新着物件情報
			&ensp;&ensp;<input type="checkbox" name="topKind" value="01" <?php if($bukken->topKind === '01') echo 'checked' ?> ></input>おすすめ物件）			
			&ensp;&ensp;<input type="radio" name="publishFlg" value="00" <?php if($bukken->publishFlg == "00"){echo 'checked';}  ?>><span class="spanH">非公開</span>			
		</td>
		<th>キャッチ</th>
		<td>			
			<input type="text" name="catch" style="ime-mode:active" maxlength="50" size="38" value="<?php echo $bukken->catch ?>" /> 
		</td>
	</tr>
	
	<tr>
		<th colspan="2">物件価格</th>
		<td>
			<input type="text" name="price" maxlength="12" style="ime-mode:disabled" size="15" value="<?php echo $bukken->price ?>" /> 円						
		</td>
		<th>成約済／期限切れ</th>
		<td>
			<input type="checkbox" name="finishFlg" value="01" <?php if($bukken->finishFlg === '01') echo 'checked' ?> />成約済
			<input type="checkbox" name="saleStopFlg" value="01" <?php if($bukken->saleStopFlg === '01') echo 'checked' ?> />期限切れ			
		</td>
	</tr>			
	<tr>
		<th colspan="2">敷地権利</th>
		<td>
			<select name="sikiti">
				<?php 					
					MakeComboSikiti(true, $bukken->sikiti);
				?>
			</select>
		</td>
		<th>用途地域</th>
		<td>
			<select name="youto1">
				<?php 					
					MakeComboYouto(true, $bukken->youto1);
				?>
			</select>
			<select name="youto2">
				<?php 					
					MakeComboYouto(true, $bukken->youto2);
				?>
			</select>
			<select name="youto3">
				<?php 					
					MakeComboYouto(true, $bukken->youto3);
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th colspan="2">引渡時期</th>
		<td colspan="3">			
			<select name="jiki" id="slJiki">
				<?php 					
					MakeComboJiki(true, $bukken->jiki);
				?>
			</select>
			<input type="text" name="jikiMonth" id="txtJikiMonth" style="ime-mode:active" maxlength="10" size="20" value="<?php echo $bukken->jikiMonth ?>" />
			<input type="text" name="nyuKyoDay" id="txtNyuKyoDay" style="ime-mode:active" maxlength="10" size="20" value="<?php echo $bukken->nyuKyoDay ?>" />
		</td>
	</tr>	
<!-- 	
</table>

<table cellspacing="1" cellpadding="0" class="dataTbl">
 -->
 
	<tr>
		<th rowspan="5" id="crossRow">交<br>通</th>
		<th class="noImg">所在地(<span class="hissu">*</span>)</th>
		<td colspan=3 >
			〒<input type="text" name="zipCode" size="8" maxlength="7" style="ime-mode:disabled" id="txtZip" value="<?php echo $bukken->zipCode ?>"/>
			<a href="javascript:OpenZip(document.getElementById('txtZip').value);">
				<img src='./images/global/btn2_search.png' alt="検索" border="0" style="margin-bottom: -5px;" /></a>
			<input type="text" name="address" size="80" maxlength="128" id="txtAddress" style="ime-mode:active" value="<?php echo $bukken->address ?>" />
		</td>
	</tr>
	<tr>
		<th class="noImg">最寄駅1(<span class="hissu">*</span>)</th>
		<td colspan="3">
			<span class="spanH">路線:</span><input type="text" name="route1Name" maxlength="15" size="25" id="txtRoute1Name"  style="ime-mode:active" value="<?php echo $bukken->route1Name ?>" />
			&nbsp;<span class="spanH">駅:</span><input type="text" name="station1Name" maxlength="15" id="txtStation1Name" size="25"   style="ime-mode:active" value="<?php echo $bukken->station1Name ?>" />
			<a href="javascript:OpenStation('1');">
				<img src='./images/global/btn2_search.png' alt="検索" border="0" style="margin-bottom: -5px;" /></a> &ensp;&ensp;&ensp;&ensp;
			駅徒歩:<input type="text" name="station1Walk" style="ime-mode:disabled" maxlength="3" size="5" id="txtStation1Walk"  style="ime-mode:active" value="<?php echo $bukken->station1Walk ?>" /> 分
			<div style="margin-top:5px">
				備考&nbsp;<input type="text" name="traffic1Note" maxlength="100" id="txttraffic1Note" size="50"   style="ime-mode:active" value="<?php echo $bukken->traffic1Note ?>" />
			</div>		
		</td>
	</tr>		
	<tr>
		<th class="noImg">最寄駅2</th>
		<td colspan="3">
			路線:<input type="text" name="route2Name" maxlength="15" size="25"  id="txtRoute2Name"  style="ime-mode:active" value="<?php echo $bukken->route2Name ?>" />
			&nbsp;駅:<input type="text" name="station2Name" maxlength="15" size="25"  id="txtStation2Name"  style="ime-mode:active" value="<?php echo $bukken->station2Name ?>" />
			<a href="javascript:OpenStation('2');">
				<img src='./images/global/btn2_search.png' alt="検索" style="margin-bottom: -5px;" border="0" /></a> &ensp;&ensp;&ensp;&ensp;				
			駅徒歩:<input type="text" name="station2Walk" style="ime-mode:disabled" maxlength="3" size="5" id="txtStation2Walk"  style="ime-mode:active" value="<?php echo $bukken->station2Walk ?>" /> 分
			
		</td>
	</tr>
	<tr>
		<th class="noImg">最寄駅3</th>
		<td colspan="3">
			路線:<input type="text" name="route3Name" maxlength="15" size="25"  id="txtRoute3Name"  style="ime-mode:active" value="<?php echo $bukken->route3Name ?>" />
			&nbsp;駅:<input type="text" name="station3Name" maxlength="15" size="25"  id="txtStation3Name"  style="ime-mode:active" value="<?php echo $bukken->station3Name ?>" />
			<a href="javascript:OpenStation('3');">
				<img src='./images/global/btn2_search.png' alt="検索" style="margin-bottom: -5px;" border="0" /></a> &ensp;&ensp;&ensp;&ensp;
			駅徒歩:<input type="text" name="station3Walk" style="ime-mode:disabled" maxlength="3" size="5" id="txtStation3Walk"  style="ime-mode:active" value="<?php echo $bukken->station3Walk ?>" /> 分
			
		</td>
	</tr>
	<tr>
		<th class="noImg">その他交通手段</th>
		<td colspan="3">
			<input type="text" name="bus" size="105" maxlength="20" style="ime-mode:active" value="<?php echo $bukken->bus ?>" />
		</td>
	</tr>
		
	<tr>
		<th colspan="2">情報登録日</th>
		<td>
			<input type="text" name="registTime" class="textright" id="txtRegistTime" style="ime-mode:disabled" maxlength="8" size="15" value="<?php echo $bukken->registTime ?>" />
			<input type=button  title="今日" value="今日" onclick="SetToday();">	
		</td>
		<th>情報有効期限</th>
		<td><input type="text" name="limitTime" class="textright" style="ime-mode:disabled" maxlength="8" size="15" value="<?php echo $bukken->limitTime ?>" /></td>
	</tr>	
<!-- </table>-->

<!-- 土地 -->
<!-- <table cellspacing="1" cellpadding="0" class="dataTbl"> -->

	<tr>
		<th colspan="2">現況</th>
		<td colspan="3">
			<select name="genkyo">	
				<?php MakeComboGenKyo(true, $bukken->genkyo);?> 
			</select>
		</td>
	</tr>

<!-- 建物 -->
	<tr>
		<th rowspan="3" id="crossRow">建<br/>物</th>
		<th class="noImg" style="width:13%" >築年月</th>
		<td style="width:37%" >
			<input type="text" name="tikuYear" id="txtTikuYear" class="textright" style="ime-mode:disabled" maxlength="20" size="20" value="<?php echo $bukken->tikuYear ?>" />			
		</td>
		<th style="width:13%" >戸数・階</th>
		<td>
			総戸数<input type="text" class="textright" name="souKosu" id="txtSouKosu" style="ime-mode:disabled" maxlength="3" size="5" value="<?php echo $bukken->souKosu ?>" />
			<label id="lblSyozaikai">所在階</label><input type="text" name="room1Kai" style="ime-mode:active" id="txtRoom1Kai" maxlength="10" size="15" value="<?php echo $bukken->room1Kai ?>" />
		</td>
	</tr>
	<tr>
		<th class="noImg">建物構造</th>
		<td>
			<select name="structure" id="lstStructure" >	
				<?php MakeComboStructure(true, $bukken->structure);?> 
			</select><br>
			<table>
				<tr>
					<th class="noborder">地上階層</th>
					<td class="noborder"><input type="text" name="chijouKai" style="ime-mode:active" id="txtChijouKai" maxlength="2" size="10" value="<?php echo $bukken->chijouKai ?>" />階</td>
					<th class="noborder">地下階層</th>
					<td class="noborder"><input type="text" name="chikaKai" style="ime-mode:active" id="txtChikaKai" maxlength="2" size="10" value="<?php echo $bukken->chikaKai ?>" />階</td>
				</tr>
				<tr>
					<th class="noborder">所在階</th>
					<td class="noborder" colspan="3"><input type="text" name="syozaiKai" style="ime-mode:active" id="txtSyozaiKai" maxlength="2" size="10" value="<?php echo $bukken->syozaiKai ?>" />階</td>
				</tr>
			</table>								
		</td>
		
		<th>専有面積</th>
		<td>
			<input type="text" class="textright" name="senyuArea" id="txtSenyuArea" style="ime-mode:disabled" maxlength="8" size="15" value="<?php echo CleanNumber($bukken->senyuArea) ?>" />&ensp;㎡				
		</td>
	</tr>
	<tr>
		<th class="noImg">部屋番号</th>
		<td>	
			<input type="text" name="roomNo" style="ime-mode:active" id="txtRoomNo" maxlength="20" size="35" value="<?php echo $bukken->roomNo ?>" />		
		</td>
		<th>方位・間取</th>
		<td>					
			間取<select name="madori" id="lstMadori" >	
					<?php MakeComboMadori(true, $bukken->madori);?> 
				</select>			
		</td>
	</tr>
<!-- 建物 -->

<!-- 駐車場 -->
	<tr>
		<th rowspan="2" id="crossRow">駐<br/>車<br/>場</th>
		<th class="noImg">駐車場有無</th>
		<td>
			<select name="parking" id="lstParking" >
				<?php MakeComboParking(true, $bukken->parking);?>
			</select>			
		</td>
		<th>駐車場形態</th>
		<td>
			<select name="parkingKind" id="lstParkingKind" >	
				<?php MakeComboParkingKind(true, $bukken->parkingKind);?> 
			</select>
		</td>
	</tr>
	<tr>
		<th class="noImg">駐車場費</th>
		<td colspan=3>	
			<input type="text" name="parkingPrice" id="txtParkingPrice" maxlength="128" size="35" value="<?php echo $bukken->parkingPrice ?>" />	
		</td>		
	</tr>
<!-- 駐車場 -->

<!-- 管理 -->
	<tr>
		<th rowspan="4" id="crossRow">管<br/>理</th>
		<th class="noImg">施工会社</th>
		<td colspan="3">
			<input type="text" name="sekouCompany" id="txtSekouCompany" style="ime-mode:active" maxlength="25" size="35" value="<?php echo $bukken->sekouCompany ?>" />
		</td>
	</tr>
	<tr>
		<th class="noImg">管理会社</th>
		<td>	
			<input type="text" name="kanriCompany" id="txtKanriCompany" style="ime-mode:active" maxlength="25" size="35" value="<?php echo $bukken->kanriCompany ?>" />	
		</td>
		<th>管理形態</th>
		<td>
			<select name="kanriKind" id="lstKanriKind" >	
				<?php MakeComboKanriKind(true, $bukken->kanriKind);?> 
			</select>			
		</td>
	</tr>
	<tr>
		<th class="noImg">管理費</th>
		<td>	
			<input type="text" class="textright" id="txtKanriPrice" name="kanriPrice" style="ime-mode:disabled" maxlength="7" size="15" value="<?php echo $bukken->kanriPrice ?>" />&ensp;円／月		
		</td>
		<th>修繕積立金</th>
		<td>
			<input type="text" class="textright" name="syuzenPrice" id="txtSyuzenPrice" style="ime-mode:disabled" maxlength="7" size="15" value="<?php echo $bukken->syuzenPrice ?>" />&ensp;円／月				
		</td>
	</tr>
	
	<tr>
		<th class="noImg">専用庭</th>
		<td>
			<input type="text" class="textright" name="niwaArea" id="txtNiwaArea" style="ime-mode:disabled" maxlength="8" size="15" value="<?php echo CleanNumber($bukken->niwaArea) ?>" />&ensp;㎡							
		</td>
		<th>バルコニー（テラス）面積</th>
		<td>
			<input type="text" class="textright" name="balArea" id="txtBalArea" style="ime-mode:disabled" maxlength="8" size="15" value="<?php echo CleanNumber($bukken->balArea) ?>" />&ensp;㎡						
		</td>
	</tr>
	
<!-- 管理 -->

<?php if($bukken->memberFlg == "02"){?>

    <tr>
        <th colspan="2" class="reinsTh">物件種目</th>
        <td colspan="3">
        	<?php echo $bukken->colBukkenShumoku?>
        </td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">商号</th>
        <td colspan="3"><?php echo $bukken->colShougo?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">代表電話番号</th>
        <td><?php echo $bukken->colDaihyouDenwa?></td>
        <th class="reinsTh">問合せ先電話番号</th>
        <td><?php echo $bukken->colToiawaseDenwa?></td>
    </tr>
     <tr>
        <th colspan="2" class="reinsTh">問合せ担当者１</th>
        <td><?php echo $bukken->colTantoushya1?></td>
        <th class="reinsTh">担当者電話番号１</th>
        <td><?php echo $bukken->colTantousyaDenwa1?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">Ｅメールアドレス１</th>
        <td colspan="3"><?php echo $bukken->colEmail1?></td>
    </tr>
     <tr>
        <th colspan="2" class="reinsTh">問合せ担当者2</th>
        <td><?php echo $bukken->colTantoushya2?></td>
        <th class="reinsTh">担当者電話番号2</th>
        <td><?php echo $bukken->colTantousyaDenwa2?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">Ｅメールアドレス2</th>
        <td colspan="3"><?php echo $bukken->colEmail2?></td>
    </tr>
    
    <tr>
        <th colspan="2" class="reinsTh">取引態様</th>
        <td colspan="3"><?php echo $bukken->colTorihikiTaijyou?></td>        
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">報酬形態</th>
        <td><?php echo $bukken->colHoushyuKeitai?></td>
        <th class="reinsTh">手数料割合率</th>
        <td><?php echo $bukken->colTeisuuryouWariai?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">手数料</th>
        <td colspan="3"><?php echo $bukken->colTeisuuryou?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">備考１</th>
        <td colspan="3"><?php echo $bukken->colBiko1?></td>        
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">備考２</th>
        <td colspan="3"><?php echo $bukken->colBiko2?></td>        
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">備考３</th>
        <td colspan="3"><?php echo $bukken->colBiko3?></td>        
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">備考４</th>
        <td colspan="3"><?php echo $bukken->colBiko4?></td>        
    </tr>
    
    <tr>
        <th colspan="2" class="reinsTh">名称又は商号</th>
        <td><?php echo $bukken->colMeishouMatahaShougo?></td>
        <th class="reinsTh">事務所所在地</th>
        <td><?php echo $bukken->colJimushoShozaichi?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">事務所電話番号</th>
        <td><?php echo $bukken->colJimushoDenwabango?></td>
        <th class="reinsTh">宅建業法による免許番号</th>
        <td><?php echo $bukken->colMenkyoBango?></td>
    </tr>
    <tr>
        <th colspan="2" class="reinsTh">自社管理欄</th>
        <td><?php echo $bukken->colJishaKanriRan?></td>
        <th class="reinsTh">広告転載区分</th>
        <td><?php echo $bukken->colKoukokuTensaiKubun?></td>
    </tr>
    <?php }?>
    
    <!-- マップブロック -->
    <tr>
		<th rowspan="2" id="crossRow">地<br/>図<br/>情<br/>報</th>
		<th class="noImg">GoogleMap</th>
		<td colspan="3">
			<input type="checkbox" name="gmapShowMap" id="chkGmapShowMap" value="01" <?php if($bukken->gmapShowMap === '01') echo 'checked' ?> >地図を表示&nbsp;
			<input type="checkbox" name="gmapShowView" id="chkGmapShowView" value="01" <?php if($bukken->gmapShowView === '01') echo 'checked' ?> >StreetViewを表示<br>
			
			<input type="radio" name="gmapAutoFlg" id="gmapAutoFlg" value="01" <?php if($bukken->gmapAutoFlg === '01') echo 'checked' ?> onclick="disableGMapCtl(1)">自動　所在地より緯度・経度を取得し、地図表示&nbsp;
			<input type=button  title="Acquision" name="gmAcquision" id="gmAcquision" value="取得" onclick="GetLatLng()";>&nbsp;&nbsp;
			緯度：<input type="text" name="gmapLat" id="txtGmapLat" value="<?php echo $bukken->gmapLat ?>" size="20" readonly="readonly" class="numField">&nbsp;&nbsp;&nbsp;
			経度：<input type="text" name="gmapLong" id="txtGmapLong" value="<?php echo $bukken->gmapLong ?>" size="20" readonly="readonly" class="numField">&nbsp;<br>
			<input type="radio" name="gmapAutoFlg" value="00" <?php if($bukken->gmapAutoFlg === '00') echo 'checked' ?> onclick="disableGMapCtl(2)">手動　タグを使用し、地図表示&nbsp;
			<input type=button  title="Confirmation" value="確認" name="gmConfirmation" id="gmConfirmation" onclick="sendGMapURLRequest();"><br>
			&nbsp;&nbsp;地図URL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" name="gmapMapUrl" id="gmapMapUrl" size="80"  value="<?php echo str_replace("\\", "", htmlspecialchars($bukken->gmapMapUrl)) ?>" >&nbsp;<br>
			&nbsp;&nbsp;StreetViewURL:<input type="text" name="gmapStreetUrl" id="gmapStreetUrl" size="80" value="<?php echo str_replace("\\", "", htmlspecialchars($bukken->gmapStreetUrl)) ?>" >&nbsp;
				
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<span id="googlemap_image">
			</span>
		</td>
	</tr>	
	<!-- マップブロック -->		
</table>	

</form>
<br>

<div align="center">
	<a href="bukkenList.php?PHPSESSID=<?php echo session_id()?>">
		<img src="images/global/demobtn_list.gif" alt="一覧へ" border="0" onmouseover="ImageMouse(this, 'demobtn_list_o.gif')" onmouseout="ImageMouse(this, 'demobtn_list.gif')"></a> 
	<a href="#" onclick="submitData();">
		<img src="images/global/demobtn_confirm.gif" alt="確認" border="0" onmouseover="ImageMouse(this, 'demobtn_confirm_o.gif')" onmouseout="ImageMouse(this, 'demobtn_confirm.gif')"/></a> 		
</div>
<br>

<script type="text/javascript">

function submitData(){
	
	document.forms['frm'].submit();
}

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

function BrowseFile(pid)
{
	url = "./Popup/browseFile.php?bukkenId=" + pid; 
	window.open(url,"file", "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=700,height=680");	
}

function OpenZip(zip)
{
	var url = "./Popup/searchZip.php?zip=" + zip;
	window.open(url,"zip","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=580,height=360");
}
function OpenStation(index)
{
	var url = "./Popup/searchStation.php?index=" + index;
	window.open(url,"zip","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=450,height=360");
}
function GetAddress(address)
{
	document.getElementById('txtAddress').value = address.replace(/|/g, "");
}
function GetStation(line, station, index)
{
	if(index == 1)
	{
		document.getElementById('txtRoute1Name').value = line;
		document.getElementById('txtStation1Name').value = station;
	}
	else if(index == 2)
	{
		document.getElementById('txtRoute2Name').value = line;
		document.getElementById('txtStation2Name').value = station;
	}
	else if(index == 3)
	{
		document.getElementById('txtRoute3Name').value = line;
		document.getElementById('txtStation3Name').value = station;
	}
}

function SetToday()
{
	var now = new Date();
	var month = now.getMonth() + 1;
	if(month < 10) month = "0" + String(month);
	var day = now.getDate();
	if(day < 10) day = "0" + String(day);
	document.getElementById('txtRegistTime').value = String(now.getFullYear()) + String(month) + String(day);
}

</script> 


<script type="text/javascript" charset="utf-8"> 
 
function sendGMapLatitudeRequest() {
	document.getElementById('googlemap_image').innerHTML = "";
	
	gmapShowMapStatus = document.getElementById('chkGmapShowMap').checked;
	gmapShowViewStatus = document.getElementById('chkGmapShowView').checked;
	if (gmapShowMapStatus == false && gmapShowViewStatus == false) {
		return;	
	}
	
	gmapLat = document.getElementById('txtGmapLat').value;
	gmapLong = document.getElementById('txtGmapLong').value;
	if (gmapLat.value == "" && gmapLong == "") {
		return;
	}
	
	div1 ="<div id='map' style='width: 49.6%; height: 320px; border: 1px solid #000; float: left;' visible=false></div> "; 
	div2 = "<div id='pano' style='width: 49.6%; height: 320px; border: 1px solid #000; float: right;'></div>";	
	htmltext = "<table border=1 width='100%'>";
	htmltext += div1;	
	htmltext += div2;	
	htmltext += "</table>"
	document.getElementById('googlemap_image').innerHTML=htmltext;
	viewMap(gmapLat, gmapLong, gmapShowMapStatus, gmapShowViewStatus);
	
}
 
function sendGMapURLRequest() {
	document.getElementById('googlemap_image').innerHTML = "";
	
	gmapShowMapStatus = document.getElementById('chkGmapShowMap').checked;
	gmapShowViewStatus = document.getElementById('chkGmapShowView').checked;
	if (gmapShowMapStatus == false && gmapShowViewStatus == false) {
		return;	
	}
	gmapMapUrl = document.getElementById('gmapMapUrl').value;
	gmapStreetUrl = document.getElementById('gmapStreetUrl').value;
	if (gmapMapUrl == "" && gmapStreetUrl == "") {
		return;	
	}
	
	viewURL(gmapMapUrl, gmapStreetUrl, gmapShowMapStatus, gmapShowViewStatus);	
}
 
function disableGMapCtl(type) {
	var oGMapMapUrl = document.getElementById('gmapMapUrl');
	var oGMmapStreetUrl = document.getElementById('gmapStreetUrl');
	var oGMAcquision = document.getElementById('gmAcquision');
	var oGMConfirmation = document.getElementById('gmConfirmation');
	
	var bDisabled = false;
	
	var mode = 'input';
	if (mode == "input") {
		if (type == 1) {
			bDisabled = false;
		} else {
			bDisabled = true;
		}

		oGMapMapUrl.disabled = !bDisabled;
		oGMmapStreetUrl.disabled = !bDisabled;
		oGMAcquision.disabled = bDisabled;
		oGMConfirmation.disabled = !bDisabled;
	
	} else {
		oGMAcquision.disabled = true;
		oGMConfirmation.disabled = true;				
	}
}
 
function loadGMap() {
	if (isLoaded == true) {
		return;
	}	
	var oGmapAutoFlg = document.getElementById('gmapAutoFlg');
	var type = 1;
	if(oGmapAutoFlg.checked == true) {
		type = 1;
		sendGMapLatitudeRequest();
	} else {
		type = 2;
		sendGMapURLRequest();
	}	

	disableGMapCtl(type);
	isLoaded = true;

}
 

function GetLatLng()
{
	document.getElementById('googlemap_image').innerHTML = "";	
	gmapShowMapStatus = document.getElementById('chkGmapShowMap').checked;
	gmapShowViewStatus = document.getElementById('chkGmapShowView').checked;
	
	if (gmapShowMapStatus == false && gmapShowViewStatus == false) {
		return;	
	}
	var txtAddress = document.getElementById('txtAddress');
	var txtGmapLat = document.getElementById('txtGmapLat');
	var txtGmapLong = document.getElementById('txtGmapLong');
	
	var geocoder = new GClientGeocoder();
	geocoder.getLatLng(txtAddress.value,
			function(point)
			{
				if(point)
				{
					txtGmapLat.value = point.lat();
					txtGmapLong.value = point.lng();
					sendGMapLatitudeRequest();
				}
			}
  	);
}


	var isLoaded = false;
	window.onload=loadGMap;
	window.onunload=GUnload;
</script>

<a href="menu.php" id="menuLink" onmouseover="Focus(this)" onmouseout="LostFocus(this)" > 《 管理メニュートップへ</a>
<br/><br/>
</div>

<?php include 'footer.php'; ?>