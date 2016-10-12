<?php
include('lib/idiorm.php');
include('db/define.php');
include('db/userlib.php');
include 'db/codelib.php';
include 'db/memberlib.php';

$hope = null;

if($_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_GET['pid']))
	{
		$hope = getHopeDetail($_GET["pid"]);
	}
	else {
		$hope = getHopeDetail(null);
		$hope->memberInfoPid = $_GET['memberInfoPid'];
	}
}
else if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if($_POST['pid'] > 0)
		{
			$hope = getHopeDetail($_POST['pid']);
		}
		else {
			$hope = getHopeDetail(null);
		}
		
		bindHope($hope); //POSTから会員情報を取得
		
		$action = $_POST['action'];
		$error = validateHope($hope);	
		
		if($error == '')
		{			
			if($action == 0)
			{
				$action = 1;
			}
			else if($action == 2)
			{				
				saveHope($hope);
?>
			<script language = 'javascript'>
				window.opener.location.reload(false);
				window.close();
			</script>
<?php 
			}
		}
		else
		{
			$action = 0;
		}		
		
	}

?>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<META http-equiv="Content-Style-Type" content="text/css">
<title>郵便番号検索</title>
<link href="css/default.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/String.js"></script>
<script type="text/javascript" src="js/line.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	lines = '<?php echo $hope->hopeLine?>'.split(',');
	$('[name="hopeLine[]"]').each(function(){
		if($.inArray($(this).val(), lines) >= 0){
			//$(this).prop('checked', true);
			$(this).trigger('click');
		}
	})
	
	stationArray = {};
	stations = '<?php echo $hope->hopeStation?>'.split('|');
	for(i = 0 ; i < stations.length ; i++){
		if(stations[i] == '') break;
		vals = stations[i].split('-');
		lst = vals[1];
		stationArray[vals[0]] = lst.split(',');
	}

	$('.cont02 input[id^=lineGroup][id$=-0]').each(function(){
		id = $(this).attr('id');
		stationId = id.replace('-0', '-');
		sts = stationArray[$(this).val()];
		if(sts == undefined || sts.length == 0) return;

		//路線の駅
		all = true;
		$(String.format('input[id^={0}]', stationId)).each(function(){
			if($(this).attr('id') == id) return;
			if($.inArray($(this).val(), sts) >=0){
				$(this).prop('checked', true);
			}
			else {
				all = false;
			}
		});

		//選択駅あり	
		if(all){
			$(this).prop('checked', true);
		}
		
	});

});
</script>
</head>
<body>
<div id="content" style="width:1400px !important">
<br>
<div id="pageTitle" style="text-align:left">
	会員情報≫ 会員情報詳細
</div>
<div align="center"> 
	<a href="javascript:window.close()"><img src="images/global/demobtn_list.gif" alt="一覧へ" border="0" ></a>
	<a href="#" onclick="javascript:submit()">
		<img src="images/global/demobtn_confirm.gif" alt="確認" border="0" />
	</a> 		
</div>
<br>
<font color="red">
	<ul>
		<?php echo $error ?>
	</ul>
</font>
<br>
<form id="frm" method="post" action="" ENCTYPE="multipart/form-data" >
<input type="hidden" name="pid" value="<?php echo $hope->pid ?>" id="pid">
<input type="hidden" name="memberInfoPid" value="<?php echo $hope->memberInfoPid ?>" id="memberInfoPid">
<input type="hidden" name="action" value="<?php echo $action ?>" id="action">
<input type="hidden" name="hopeStation" id="hidHopeStation">
<table class="dataTbl">
	<tr>
		<th style="width:15%">希望地域&nbsp;(<span class="hissu">*</span>)</th>
		<td>
			<?php MakeCodeMstMultiCheckbox('0028', 'hopeArea', $hope->hopeArea, 6)?>
		</td>
	</tr>
	<tr>
			<th>希望路線&nbsp;(<span class="hissu">*</span>)</th>
			<td>
			<div id="mypage">
				<div class="cont01">
                    <div class="list-check-03-wrap" id="line-checkbox">
                    
                    <h4 class="ttl-h4-03"><span>東京メトロ・都営地下鉄</span></h4>
                    <ul class="list-check-03">
                    <!--▼路線▼-->
                    <!--東京メトロ・都営地下鉄-->
                    <li>
                    <input id="lineGroup1-1" name="hopeLine[]" value="東京メトロ丸ノ内線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-1">東京メトロ丸ノ内線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-2" name="hopeLine[]" value="東京メトロ千代田線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-2">東京メトロ千代田線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-3" name="hopeLine[]" value="東京メトロ半蔵門線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-3">東京メトロ半蔵門線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-4" name="hopeLine[]" value="東京メトロ南北線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-4">東京メトロ南北線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-5" name="hopeLine[]" value="東京メトロ日比谷線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-5">東京メトロ日比谷線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-6" name="hopeLine[]" value="東京メトロ有楽町線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-6">東京メトロ有楽町線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-7" name="hopeLine[]" value="東京メトロ東西線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-7">東京メトロ東西線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-8" name="hopeLine[]" value="東京メトロ銀座線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-8">東京メトロ銀座線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-9" name="hopeLine[]" value="東京メトロ副都心線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-9">東京メトロ副都心線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-10" name="hopeLine[]" value="都営大江戸線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-10">都営大江戸線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-11" name="hopeLine[]" value="都営浅草線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-11">都営浅草線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-12" name="hopeLine[]" value="都営三田線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-12">都営三田線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-13" name="hopeLine[]" value="都営新宿線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-13">都営新宿線</label>
                    </li>

                    <li>
                    <input id="lineGroup1-14" name="hopeLine[]" value="都電荒川線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup1-14">都電荒川線</label>
                    </li>
                    <!--/東京メトロ・都営地下鉄-->
                    </ul>

                    <h4 class="ttl-h4-03"><span>JR線</span></h4>
                    <ul class="list-check-03">
                    <!--JR-->
                    <li>
                    <input id="lineGroup2-1" name="hopeLine[]" value="JR東海道本線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-1">JR東海道本線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-2" name="hopeLine[]" value="JR山手線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-2">JR山手線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-3" name="hopeLine[]" value="JR南武線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-3">JR南武線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-4" name="hopeLine[]" value="JR武蔵野線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-4">JR武蔵野線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-5" name="hopeLine[]" value="JR横浜線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-5">JR横浜線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-6" name="hopeLine[]" value="JR横須賀線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-6">JR横須賀線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-7" name="hopeLine[]" value="JR中央本線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-7">JR中央本線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-8" name="hopeLine[]" value="JR中央線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-8">JR中央線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-9" name="hopeLine[]" value="JR中央・総武線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-9">JR中央・総武線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-10" name="hopeLine[]" value="JR総武本線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-10">JR総武本線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-11" name="hopeLine[]" value="JR青梅線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-11">JR青梅線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-12" name="hopeLine[]" value="JR五日市線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-12">JR五日市線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-13" name="hopeLine[]" value="JR八高線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-13">JR八高線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-14" name="hopeLine[]" value="宇都宮線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-14">宇都宮線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-15" name="hopeLine[]" value="JR常磐線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-15">JR常磐線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-16" name="hopeLine[]" value="JR埼京線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-16">JR埼京線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-17" name="hopeLine[]" value="JR高崎線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-17">JR高崎線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-18" name="hopeLine[]" value="JR京葉線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-18">JR京葉線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-19" name="hopeLine[]" value="JR成田エクスプレス" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-19">JR成田エクスプレス</label>
                    </li>

                    <li>
                    <input id="lineGroup2-20" name="hopeLine[]" value="JR京浜東北線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-20">JR京浜東北線</label>
                    </li>

                    <li>
                    <input id="lineGroup2-21" name="hopeLine[]" value="JR湘南新宿ライン" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup2-21">JR湘南新宿ライン</label>
                    </li>
                    <!--/JR-->
	                    </ul>

                    <h4 class="ttl-h4-03"><span>東急・京王・小田急線・その他</span></h4>
                    <ul class="list-check-03">
                    <!--東急・京王-->
                    <li>
                    <input id="lineGroup3-1" name="hopeLine[]" value="東急東横線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-1">東急東横線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-2" name="hopeLine[]" value="東急目黒線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-2">東急目黒線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-3" name="hopeLine[]" value="東急田園都市線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-3">東急田園都市線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-4" name="hopeLine[]" value="東急大井町線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-4">東急大井町線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-5" name="hopeLine[]" value="東急池上線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-5">東急池上線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-6" name="hopeLine[]" value="東急多摩川線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-6">東急多摩川線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-7" name="hopeLine[]" value="東急世田谷線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-7">東急世田谷線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-8" name="hopeLine[]" value="京王線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-8">京王線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-9" name="hopeLine[]" value="京王相模原線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-9">京王相模原線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-10" name="hopeLine[]" value="京王高尾線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-10">京王高尾線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-11" name="hopeLine[]" value="京王競馬場線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-11">京王競馬場線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-12" name="hopeLine[]" value="京王動物園線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-12">京王動物園線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-13" name="hopeLine[]" value="京王井の頭線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-13">京王井の頭線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-14" name="hopeLine[]" value="小田急線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-14">小田急線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-15" name="hopeLine[]" value="小田急多摩線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-15">小田急多摩線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-16" name="hopeLine[]" value="東武東上線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-16">東武東上線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-17" name="hopeLine[]" value="東武伊勢崎線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-17">東武伊勢崎線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-18" name="hopeLine[]" value="東武亀戸線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-18">東武亀戸線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-19" name="hopeLine[]" value="東武大師線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-19">東武大師線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-20" name="hopeLine[]" value="西武池袋線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-20">西武池袋線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-21" name="hopeLine[]" value="西武有楽町線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-21">西武有楽町線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-22" name="hopeLine[]" value="西武豊島線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-22">西武豊島線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-23" name="hopeLine[]" value="レオライナー" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-23">レオライナー</label>
                    </li>

                    <li>
                    <input id="lineGroup3-24" name="hopeLine[]" value="西武新宿線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-24">西武新宿線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-25" name="hopeLine[]" value="西武拝島線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-25">西武拝島線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-26" name="hopeLine[]" value="西武西武園線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-26">西武西武園線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-27" name="hopeLine[]" value="西武国分寺線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-27">西武国分寺線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-28" name="hopeLine[]" value="西武多摩湖線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-28">西武多摩湖線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-29" name="hopeLine[]" value="西武多摩川線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-29">西武多摩川線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-30" name="hopeLine[]" value="京成本線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-30">京成本線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-31" name="hopeLine[]" value="京成押上線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-31">京成押上線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-32" name="hopeLine[]" value="京成金町線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-32">京成金町線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-33" name="hopeLine[]" value="成田スカイアクセス" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-33">成田スカイアクセス</label>
                    </li>

                    <li>
                    <input id="lineGroup3-34" name="hopeLine[]" value="京急本線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-34">京急本線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-35" name="hopeLine[]" value="京急空港線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-35">京急空港線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-36" name="hopeLine[]" value="埼玉高速鉄道線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-36">埼玉高速鉄道線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-37" name="hopeLine[]" value="つくばエクスプレス" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-37">つくばエクスプレス</label>
                    </li>

                    <li>
                    <input id="lineGroup3-38" name="hopeLine[]" value="ゆりかもめ" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-38">ゆりかもめ</label>
                    </li>

                    <li>
                    <input id="lineGroup3-39" name="hopeLine[]" value="多摩モノレール" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-39">多摩モノレール</label>
                    </li>

                    <li>
                    <input id="lineGroup3-40" name="hopeLine[]" value="東京モノレール" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-40">東京モノレール</label>
                    </li>

                    <li>
                    <input id="lineGroup3-41" name="hopeLine[]" value="りんかい線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-41">りんかい線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-42" name="hopeLine[]" value="北総鉄道北総線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-42">北総鉄道北総線</label>
                    </li>

                    <li>
                    <input id="lineGroup3-43" name="hopeLine[]" value="日暮里・舎人ライナー" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-43">日暮里・舎人ライナー</label>
                    </li>

                    <li>
                    <input id="lineGroup3-44" name="hopeLine[]" value="その他の路線" onclick="ClickMe(this.id);" type="checkbox">
                    <label for="lineGroup3-44">その他の路線</label>
                    </li>
                    <!--/東急・京王-->
                    </ul>

                    </div>
             	</div>
            </div>
			</td>
	</tr>
	
	<tr>
			<th>希望駅</th>
			<td>
			<div id="mypage">
				<div class="cont02">
                    <div class="list-check-03-wrap" id="station-checkbox">
                    <!--▼駅▼-->
                    <!-- 東京 -->
                    <div id="lineGroup1-1-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ丸ノ内線" id="lineGroup1-1-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-1-0">東京メトロ丸ノ内線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-1-1"  value="池袋" type="checkbox">
                    <label for="lineGroup1-1-1">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-2"  value="新大塚" type="checkbox">
                    <label for="lineGroup1-1-2">新大塚</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-3"  value="茗荷谷" type="checkbox">
                    <label for="lineGroup1-1-3">茗荷谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-4"  value="後楽園" type="checkbox">
                    <label for="lineGroup1-1-4">後楽園</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-5"  value="本郷三丁目" type="checkbox">
                    <label for="lineGroup1-1-5">本郷三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-6"  value="御茶ノ水" type="checkbox">
                    <label for="lineGroup1-1-6">御茶ノ水</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-7"  value="淡路町" type="checkbox">
                    <label for="lineGroup1-1-7">淡路町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-8"  value="大手町" type="checkbox">
                    <label for="lineGroup1-1-8">大手町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="東京" type="checkbox">
                    <label for="lineGroup1-1-9">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="銀座" type="checkbox">
                    <label for="lineGroup1-1-9">銀座</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="霞ケ関" type="checkbox">
                    <label for="lineGroup1-1-9">霞ケ関</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="国会議事堂前" type="checkbox">
                    <label for="lineGroup1-1-9">国会議事堂前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="赤坂見附" type="checkbox">
                    <label for="lineGroup1-1-9">赤坂見附</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="四ツ谷" type="checkbox">
                    <label for="lineGroup1-1-9">四ツ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="四谷三丁目" type="checkbox">
                    <label for="lineGroup1-1-9">四谷三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="新宿御苑前" type="checkbox">
                    <label for="lineGroup1-1-9">新宿御苑前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="新宿三丁目" type="checkbox">
                    <label for="lineGroup1-1-9">新宿三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="新宿" type="checkbox">
                    <label for="lineGroup1-1-9">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="西新宿" type="checkbox">
                    <label for="lineGroup1-1-9">西新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="中野坂上" type="checkbox">
                    <label for="lineGroup1-1-9">中野坂上</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="新中野" type="checkbox">
                    <label for="lineGroup1-1-9">新中野</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="東高円寺" type="checkbox">
                    <label for="lineGroup1-1-9">東高円寺</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="新高円寺" type="checkbox">
                    <label for="lineGroup1-1-9">新高円寺</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="南阿佐ケ谷" type="checkbox">
                    <label for="lineGroup1-1-9">南阿佐ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="荻窪" type="checkbox">
                    <label for="lineGroup1-1-9">荻窪</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="中野新橋" type="checkbox">
                    <label for="lineGroup1-1-9">中野新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="中野富士見町" type="checkbox">
                    <label for="lineGroup1-1-9">中野富士見町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-1-9"  value="方南町" type="checkbox">
                    <label for="lineGroup1-1-9">方南町</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-2-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ千代田線" id="lineGroup1-2-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-2-0">東京メトロ千代田線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-2-1"  value="北綾瀬" type="checkbox">
                    <label for="lineGroup1-2-1">北綾瀬</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-2"  value="綾瀬" type="checkbox">
                    <label for="lineGroup1-2-2">綾瀬</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-3"  value="北千住" type="checkbox">
                    <label for="lineGroup1-2-3">北千住</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-4"  value="町屋" type="checkbox">
                    <label for="lineGroup1-2-4">町屋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-5"  value="西日暮里" type="checkbox">
                    <label for="lineGroup1-2-5">西日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-6"  value="千駄木" type="checkbox">
                    <label for="lineGroup1-2-6">千駄木</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-7"  value="根津" type="checkbox">
                    <label for="lineGroup1-2-7">根津</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-8"  value="湯島" type="checkbox">
                    <label for="lineGroup1-2-8">湯島</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="新御茶ノ水" type="checkbox">
                    <label for="lineGroup1-2-9">新御茶ノ水</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="大手町" type="checkbox">
                    <label for="lineGroup1-2-9">大手町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="二重橋前" type="checkbox">
                    <label for="lineGroup1-2-9">二重橋前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="日比谷" type="checkbox">
                    <label for="lineGroup1-2-9">日比谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="霞ケ関" type="checkbox">
                    <label for="lineGroup1-2-9">霞ケ関</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="国会議事堂前" type="checkbox">
                    <label for="lineGroup1-2-9">国会議事堂前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="赤坂" type="checkbox">
                    <label for="lineGroup1-2-9">赤坂</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="乃木坂" type="checkbox">
                    <label for="lineGroup1-2-9">乃木坂</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="表参道" type="checkbox">
                    <label for="lineGroup1-2-9">表参道</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="明治神宮前〈原宿〉" type="checkbox">
                    <label for="lineGroup1-2-9">明治神宮前〈原宿〉</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="代々木公園" type="checkbox">
                    <label for="lineGroup1-2-9">代々木公園</label>
                    </li>
                    <li>
                    <input id="lineGroup1-2-9"  value="代々木上原" type="checkbox">
                    <label for="lineGroup1-2-9">代々木上原</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-3-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ半蔵門線" id="lineGroup1-3-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-3-0">東京メトロ半蔵門線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-3-1"  value="渋谷" type="checkbox">
                    <label for="lineGroup1-3-1">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-2"  value="表参道" type="checkbox">
                    <label for="lineGroup1-3-2">表参道</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-3"  value="青山一丁目" type="checkbox">
                    <label for="lineGroup1-3-3">青山一丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-4"  value="永田町" type="checkbox">
                    <label for="lineGroup1-3-4">永田町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-5"  value="半蔵門" type="checkbox">
                    <label for="lineGroup1-3-5">半蔵門</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-6"  value="九段下" type="checkbox">
                    <label for="lineGroup1-3-6">九段下</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="神保町" type="checkbox">
                    <label for="lineGroup1-3-7">神保町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="大手町" type="checkbox">
                    <label for="lineGroup1-3-7">大手町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="三越前" type="checkbox">
                    <label for="lineGroup1-3-7">三越前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="水天宮前" type="checkbox">
                    <label for="lineGroup1-3-7">水天宮前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="清澄白河" type="checkbox">
                    <label for="lineGroup1-3-7">清澄白河</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="住吉" type="checkbox">
                    <label for="lineGroup1-3-7">住吉</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="錦糸町" type="checkbox">
                    <label for="lineGroup1-3-7">錦糸町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-3-7"  value="押上〈スカイツリー前〉" type="checkbox">
                    <label for="lineGroup1-3-7">押上〈スカイツリー前〉</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-4-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ南北線" id="lineGroup1-4-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-4-0">東京メトロ南北線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-4-1"  value="赤羽岩淵" type="checkbox">
                    <label for="lineGroup1-4-1">赤羽岩淵</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-2"  value="志茂" type="checkbox">
                    <label for="lineGroup1-4-2">志茂</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-3"  value="王子神谷" type="checkbox">
                    <label for="lineGroup1-4-3">王子神谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-4"  value="王子" type="checkbox">
                    <label for="lineGroup1-4-4">王子</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-5"  value="西ケ原" type="checkbox">
                    <label for="lineGroup1-4-5">西ケ原</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-6"  value="駒込" type="checkbox">
                    <label for="lineGroup1-4-6">駒込</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-7"  value="本駒込" type="checkbox">
                    <label for="lineGroup1-4-7">本駒込</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-8"  value="東大前" type="checkbox">
                    <label for="lineGroup1-4-8">東大前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-9"  value="後楽園" type="checkbox">
                    <label for="lineGroup1-4-9">後楽園</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-10"  value="飯田橋" type="checkbox">
                    <label for="lineGroup1-4-10">飯田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-11"  value="市ケ谷" type="checkbox">
                    <label for="lineGroup1-4-11">市ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="四ツ谷" type="checkbox">
                    <label for="lineGroup1-4-12">四ツ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="永田町" type="checkbox">
                    <label for="lineGroup1-4-12">永田町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="溜池山王" type="checkbox">
                    <label for="lineGroup1-4-12">溜池山王</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="六本木一丁目" type="checkbox">
                    <label for="lineGroup1-4-12">六本木一丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="麻布十番" type="checkbox">
                    <label for="lineGroup1-4-12">麻布十番</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="白金高輪" type="checkbox">
                    <label for="lineGroup1-4-12">白金高輪</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="白金台" type="checkbox">
                    <label for="lineGroup1-4-12">白金台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-4-12"  value="目黒" type="checkbox">
                    <label for="lineGroup1-4-12">目黒</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-5-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ日比谷線" id="lineGroup1-5-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-5-0">東京メトロ日比谷線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-5-1"  value="北千住" type="checkbox">
                    <label for="lineGroup1-5-1">北千住</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-2"  value="南千住" type="checkbox">
                    <label for="lineGroup1-5-2">南千住</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-3"  value="三ノ輪" type="checkbox">
                    <label for="lineGroup1-5-3">三ノ輪</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-4"  value="入谷" type="checkbox">
                    <label for="lineGroup1-5-4">入谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-5"  value="上野" type="checkbox">
                    <label for="lineGroup1-5-5">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-6"  value="仲御徒町" type="checkbox">
                    <label for="lineGroup1-5-6">仲御徒町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-7"  value="秋葉原" type="checkbox">
                    <label for="lineGroup1-5-7">秋葉原</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-8"  value="小伝馬町" type="checkbox">
                    <label for="lineGroup1-5-8">小伝馬町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-9"  value="人形町" type="checkbox">
                    <label for="lineGroup1-5-9">人形町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-10"  value="茅場町" type="checkbox">
                    <label for="lineGroup1-5-10">茅場町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="八丁堀" type="checkbox">
                    <label for="lineGroup1-5-11">八丁堀</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="築地" type="checkbox">
                    <label for="lineGroup1-5-11">築地</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="東銀座" type="checkbox">
                    <label for="lineGroup1-5-11">東銀座</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="銀座" type="checkbox">
                    <label for="lineGroup1-5-11">銀座</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="日比谷" type="checkbox">
                    <label for="lineGroup1-5-11">日比谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="霞ケ関" type="checkbox">
                    <label for="lineGroup1-5-11">霞ケ関</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="神谷町" type="checkbox">
                    <label for="lineGroup1-5-11">神谷町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="六本木" type="checkbox">
                    <label for="lineGroup1-5-11">六本木</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="広尾" type="checkbox">
                    <label for="lineGroup1-5-11">広尾</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="恵比寿" type="checkbox">
                    <label for="lineGroup1-5-11">恵比寿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-5-11"  value="中目黒" type="checkbox">
                    <label for="lineGroup1-5-11">中目黒</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-6-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ有楽町線" id="lineGroup1-6-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-6-0">東京メトロ有楽町線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-6-1"  value="地下鉄成増" type="checkbox">
                    <label for="lineGroup1-6-1">地下鉄成増</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-2"  value="地下鉄赤塚" type="checkbox">
                    <label for="lineGroup1-6-2">地下鉄赤塚</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-3"  value="平和台" type="checkbox">
                    <label for="lineGroup1-6-3">平和台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-4"  value="氷川台" type="checkbox">
                    <label for="lineGroup1-6-4">氷川台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-5"  value="小竹向原" type="checkbox">
                    <label for="lineGroup1-6-5">小竹向原</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="千川" type="checkbox">
                    <label for="lineGroup1-6-6">千川</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="要町" type="checkbox">
                    <label for="lineGroup1-6-6">要町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="池袋" type="checkbox">
                    <label for="lineGroup1-6-6">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="東池袋" type="checkbox">
                    <label for="lineGroup1-6-6">東池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="護国寺" type="checkbox">
                    <label for="lineGroup1-6-6">護国寺</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="江戸川橋" type="checkbox">
                    <label for="lineGroup1-6-6">江戸川橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="飯田橋" type="checkbox">
                    <label for="lineGroup1-6-6">飯田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="市ケ谷" type="checkbox">
                    <label for="lineGroup1-6-6">市ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="麹町" type="checkbox">
                    <label for="lineGroup1-6-6">麹町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="永田町" type="checkbox">
                    <label for="lineGroup1-6-6">永田町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="桜田門" type="checkbox">
                    <label for="lineGroup1-6-6">桜田門</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="有楽町" type="checkbox">
                    <label for="lineGroup1-6-6">有楽町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="銀座一丁目" type="checkbox">
                    <label for="lineGroup1-6-6">銀座一丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="新富町" type="checkbox">
                    <label for="lineGroup1-6-6">新富町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="月島" type="checkbox">
                    <label for="lineGroup1-6-6">月島</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="豊洲" type="checkbox">
                    <label for="lineGroup1-6-6">豊洲</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="辰巳" type="checkbox">
                    <label for="lineGroup1-6-6">辰巳</label>
                    </li>
                    <li>
                    <input id="lineGroup1-6-6"  value="新木場" type="checkbox">
                    <label for="lineGroup1-6-6">新木場</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-7-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ東西線" id="lineGroup1-7-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-7-0">東京メトロ東西線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-7-1"  value="中野" type="checkbox">
                    <label for="lineGroup1-7-1">中野</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-2"  value="落合" type="checkbox">
                    <label for="lineGroup1-7-2">落合</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="高田馬場" type="checkbox">
                    <label for="lineGroup1-7-3">高田馬場</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="早稲田" type="checkbox">
                    <label for="lineGroup1-7-3">早稲田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="神楽坂" type="checkbox">
                    <label for="lineGroup1-7-3">神楽坂</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="飯田橋" type="checkbox">
                    <label for="lineGroup1-7-3">飯田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="九段下" type="checkbox">
                    <label for="lineGroup1-7-3">九段下</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="竹橋" type="checkbox">
                    <label for="lineGroup1-7-3">竹橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="大手町" type="checkbox">
                    <label for="lineGroup1-7-3">大手町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="日本橋" type="checkbox">
                    <label for="lineGroup1-7-3">日本橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="茅場町" type="checkbox">
                    <label for="lineGroup1-7-3">茅場町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="門前仲町" type="checkbox">
                    <label for="lineGroup1-7-3">門前仲町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="木場" type="checkbox">
                    <label for="lineGroup1-7-3">木場</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="東陽町" type="checkbox">
                    <label for="lineGroup1-7-3">東陽町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="南砂町" type="checkbox">
                    <label for="lineGroup1-7-3">南砂町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="西葛西" type="checkbox">
                    <label for="lineGroup1-7-3">西葛西</label>
                    </li>
                    <li>
                    <input id="lineGroup1-7-3"  value="葛西" type="checkbox">
                    <label for="lineGroup1-7-3">葛西</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-8-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ銀座線" id="lineGroup1-8-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-8-0">東京メトロ銀座線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-8-1"  value="浅草" type="checkbox">
                    <label for="lineGroup1-8-1">浅草</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-2"  value="田原町" type="checkbox">
                    <label for="lineGroup1-8-2">田原町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-3"  value="稲荷町" type="checkbox">
                    <label for="lineGroup1-8-3">稲荷町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-4"  value="上野" type="checkbox">
                    <label for="lineGroup1-8-4">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-5"  value="上野広小路" type="checkbox">
                    <label for="lineGroup1-8-5">上野広小路</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-6"  value="末広町" type="checkbox">
                    <label for="lineGroup1-8-6">末広町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-7"  value="神田" type="checkbox">
                    <label for="lineGroup1-8-7">神田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="三越前" type="checkbox">
                    <label for="lineGroup1-8-8">三越前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="日本橋" type="checkbox">
                    <label for="lineGroup1-8-8">日本橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="京橋" type="checkbox">
                    <label for="lineGroup1-8-8">京橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="銀座" type="checkbox">
                    <label for="lineGroup1-8-8">銀座</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="新橋" type="checkbox">
                    <label for="lineGroup1-8-8">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="虎ノ門" type="checkbox">
                    <label for="lineGroup1-8-8">虎ノ門</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="溜池山王" type="checkbox">
                    <label for="lineGroup1-8-8">溜池山王</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="赤坂見附" type="checkbox">
                    <label for="lineGroup1-8-8">赤坂見附</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="青山一丁目" type="checkbox">
                    <label for="lineGroup1-8-8">青山一丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="外苑前" type="checkbox">
                    <label for="lineGroup1-8-8">外苑前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="表参道" type="checkbox">
                    <label for="lineGroup1-8-8">表参道</label>
                    </li>
                    <li>
                    <input id="lineGroup1-8-8"  value="渋谷" type="checkbox">
                    <label for="lineGroup1-8-8">渋谷</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-9-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京メトロ副都心線" id="lineGroup1-9-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-9-0">東京メトロ副都心線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-9-1"  value="地下鉄成増" type="checkbox">
                    <label for="lineGroup1-9-1">地下鉄成増</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-2"  value="地下鉄赤塚" type="checkbox">
                    <label for="lineGroup1-9-2">地下鉄赤塚</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-3"  value="平和台" type="checkbox">
                    <label for="lineGroup1-9-3">平和台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-4"  value="氷川台" type="checkbox">
                    <label for="lineGroup1-9-4">氷川台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-5"  value="小竹向原" type="checkbox">
                    <label for="lineGroup1-9-5">小竹向原</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-6"  value="千川" type="checkbox">
                    <label for="lineGroup1-9-6">千川</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-7"  value="要町" type="checkbox">
                    <label for="lineGroup1-9-7">要町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="池袋" type="checkbox">
                    <label for="lineGroup1-9-8">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="雑司が谷" type="checkbox">
                    <label for="lineGroup1-9-8">雑司が谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="西早稲田" type="checkbox">
                    <label for="lineGroup1-9-8">西早稲田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="東新宿" type="checkbox">
                    <label for="lineGroup1-9-8">東新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="新宿三丁目" type="checkbox">
                    <label for="lineGroup1-9-8">新宿三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="北参道" type="checkbox">
                    <label for="lineGroup1-9-8">北参道</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="明治神宮前〈原宿〉" type="checkbox">
                    <label for="lineGroup1-9-8">明治神宮前〈原宿〉</label>
                    </li>
                    <li>
                    <input id="lineGroup1-9-8"  value="渋谷" type="checkbox">
                    <label for="lineGroup1-9-8">渋谷</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-10-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="都営大江戸線" id="lineGroup1-10-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-10-0">都営大江戸線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-10-1"  value="都庁前" type="checkbox">
                    <label for="lineGroup1-10-1">都庁前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="新宿西口" type="checkbox">
                    <label for="lineGroup1-10-1">新宿西口</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="東新宿" type="checkbox">
                    <label for="lineGroup1-10-1">東新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="若松河田" type="checkbox">
                    <label for="lineGroup1-10-1">若松河田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="牛込柳町" type="checkbox">
                    <label for="lineGroup1-10-1">牛込柳町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="牛込神楽坂" type="checkbox">
                    <label for="lineGroup1-10-1">牛込神楽坂</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="飯田橋" type="checkbox">
                    <label for="lineGroup1-10-1">飯田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="春日" type="checkbox">
                    <label for="lineGroup1-10-1">春日</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="本郷三丁目" type="checkbox">
                    <label for="lineGroup1-10-1">本郷三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="上野御徒町" type="checkbox">
                    <label for="lineGroup1-10-1">上野御徒町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="新御徒町" type="checkbox">
                    <label for="lineGroup1-10-1">新御徒町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="蔵前" type="checkbox">
                    <label for="lineGroup1-10-1">蔵前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="両国" type="checkbox">
                    <label for="lineGroup1-10-1">両国</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="森下" type="checkbox">
                    <label for="lineGroup1-10-1">森下</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="清澄白河" type="checkbox">
                    <label for="lineGroup1-10-1">清澄白河</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="門前仲町" type="checkbox">
                    <label for="lineGroup1-10-1">門前仲町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="月島" type="checkbox">
                    <label for="lineGroup1-10-1">月島</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="勝どき" type="checkbox">
                    <label for="lineGroup1-10-1">勝どき</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="築地市場" type="checkbox">
                    <label for="lineGroup1-10-1">築地市場</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="汐留" type="checkbox">
                    <label for="lineGroup1-10-1">汐留</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="大門" type="checkbox">
                    <label for="lineGroup1-10-1">大門</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="赤羽橋" type="checkbox">
                    <label for="lineGroup1-10-1">赤羽橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="麻布十番" type="checkbox">
                    <label for="lineGroup1-10-1">麻布十番</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="六本木" type="checkbox">
                    <label for="lineGroup1-10-1">六本木</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="青山一丁目" type="checkbox">
                    <label for="lineGroup1-10-1">青山一丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="国立競技場" type="checkbox">
                    <label for="lineGroup1-10-1">国立競技場</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="代々木" type="checkbox">
                    <label for="lineGroup1-10-1">代々木</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="新宿" type="checkbox">
                    <label for="lineGroup1-10-1">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="西新宿五丁目" type="checkbox">
                    <label for="lineGroup1-10-1">西新宿五丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="中野坂上" type="checkbox">
                    <label for="lineGroup1-10-1">中野坂上</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="東中野" type="checkbox">
                    <label for="lineGroup1-10-1">東中野</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="中井" type="checkbox">
                    <label for="lineGroup1-10-1">中井</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="落合南長崎" type="checkbox">
                    <label for="lineGroup1-10-1">落合南長崎</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="新江古田" type="checkbox">
                    <label for="lineGroup1-10-1">新江古田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="練馬" type="checkbox">
                    <label for="lineGroup1-10-1">練馬</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="豊島園" type="checkbox">
                    <label for="lineGroup1-10-1">豊島園</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="練馬春日町" type="checkbox">
                    <label for="lineGroup1-10-1">練馬春日町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-10-1"  value="光が丘" type="checkbox">
                    <label for="lineGroup1-10-1">光が丘</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-11-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="都営浅草線" id="lineGroup1-11-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-11-0">都営浅草線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-11-1"  value="西馬込" type="checkbox">
                    <label for="lineGroup1-11-1">西馬込</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-2"  value="馬込" type="checkbox">
                    <label for="lineGroup1-11-2">馬込</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="中延" type="checkbox">
                    <label for="lineGroup1-11-3">中延</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="戸越" type="checkbox">
                    <label for="lineGroup1-11-3">戸越</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="五反田" type="checkbox">
                    <label for="lineGroup1-11-3">五反田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="高輪台" type="checkbox">
                    <label for="lineGroup1-11-3">高輪台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="泉岳寺" type="checkbox">
                    <label for="lineGroup1-11-3">泉岳寺</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="三田" type="checkbox">
                    <label for="lineGroup1-11-3">三田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="大門" type="checkbox">
                    <label for="lineGroup1-11-3">大門</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="新橋" type="checkbox">
                    <label for="lineGroup1-11-3">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="東銀座" type="checkbox">
                    <label for="lineGroup1-11-3">東銀座</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="宝町" type="checkbox">
                    <label for="lineGroup1-11-3">宝町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="日本橋" type="checkbox">
                    <label for="lineGroup1-11-3">日本橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="人形町" type="checkbox">
                    <label for="lineGroup1-11-3">人形町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="東日本橋" type="checkbox">
                    <label for="lineGroup1-11-3">東日本橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="浅草橋" type="checkbox">
                    <label for="lineGroup1-11-3">浅草橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="蔵前" type="checkbox">
                    <label for="lineGroup1-11-3">蔵前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="浅草" type="checkbox">
                    <label for="lineGroup1-11-3">浅草</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="本所吾妻橋" type="checkbox">
                    <label for="lineGroup1-11-3">本所吾妻橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-11-3"  value="押上（スカイツリー前）" type="checkbox">
                    <label for="lineGroup1-11-3">押上（スカイツリー前）</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-12-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="都営三田線" id="lineGroup1-12-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-12-0">都営三田線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-12-1"  value="目黒" type="checkbox">
                    <label for="lineGroup1-12-1">目黒</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-2"  value="白金台" type="checkbox">
                    <label for="lineGroup1-12-2">白金台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-3"  value="白金高輪" type="checkbox">
                    <label for="lineGroup1-12-3">白金高輪</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-4"  value="三田" type="checkbox">
                    <label for="lineGroup1-12-4">三田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-5"  value="芝公園" type="checkbox">
                    <label for="lineGroup1-12-5">芝公園</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-6"  value="御成門" type="checkbox">
                    <label for="lineGroup1-12-6">御成門</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-7"  value="内幸町" type="checkbox">
                    <label for="lineGroup1-12-7">内幸町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-8"  value="日比谷" type="checkbox">
                    <label for="lineGroup1-12-8">日比谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-9"  value="大手町" type="checkbox">
                    <label for="lineGroup1-12-9">大手町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-10"  value="神保町" type="checkbox">
                    <label for="lineGroup1-12-10">神保町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-11"  value="水道橋" type="checkbox">
                    <label for="lineGroup1-12-11">水道橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-12"  value="春日" type="checkbox">
                    <label for="lineGroup1-12-12">春日</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-13"  value="白山" type="checkbox">
                    <label for="lineGroup1-12-13">白山</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-14"  value="千石" type="checkbox">
                    <label for="lineGroup1-12-14">千石</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-15"  value="巣鴨" type="checkbox">
                    <label for="lineGroup1-12-15">巣鴨</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-16"  value="西巣鴨" type="checkbox">
                    <label for="lineGroup1-12-16">西巣鴨</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-17"  value="新板橋" type="checkbox">
                    <label for="lineGroup1-12-17">新板橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-18"  value="板橋区役所前" type="checkbox">
                    <label for="lineGroup1-12-18">板橋区役所前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="板橋本町" type="checkbox">
                    <label for="lineGroup1-12-19">板橋本町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="本蓮沼" type="checkbox">
                    <label for="lineGroup1-12-19">本蓮沼</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="志村坂上" type="checkbox">
                    <label for="lineGroup1-12-19">志村坂上</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="志村三丁目" type="checkbox">
                    <label for="lineGroup1-12-19">志村三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="蓮根" type="checkbox">
                    <label for="lineGroup1-12-19">蓮根</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="西台" type="checkbox">
                    <label for="lineGroup1-12-19">西台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="高島平" type="checkbox">
                    <label for="lineGroup1-12-19">高島平</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="新高島平" type="checkbox">
                    <label for="lineGroup1-12-19">新高島平</label>
                    </li>
                    <li>
                    <input id="lineGroup1-12-19"  value="西高島平" type="checkbox">
                    <label for="lineGroup1-12-19">西高島平</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-13-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="都営新宿線" id="lineGroup1-13-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-13-0">都営新宿線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-13-1"  value="新宿" type="checkbox">
                    <label for="lineGroup1-13-1">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-2"  value="新宿三丁目" type="checkbox">
                    <label for="lineGroup1-13-2">新宿三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="曙橋" type="checkbox">
                    <label for="lineGroup1-13-3">曙橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="市ヶ谷" type="checkbox">
                    <label for="lineGroup1-13-3">市ヶ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="九段下" type="checkbox">
                    <label for="lineGroup1-13-3">九段下</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="神保町" type="checkbox">
                    <label for="lineGroup1-13-3">神保町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="小川町" type="checkbox">
                    <label for="lineGroup1-13-3">小川町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="岩本町" type="checkbox">
                    <label for="lineGroup1-13-3">岩本町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="馬喰横山" type="checkbox">
                    <label for="lineGroup1-13-3">馬喰横山</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="浜町" type="checkbox">
                    <label for="lineGroup1-13-3">浜町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="森下" type="checkbox">
                    <label for="lineGroup1-13-3">森下</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="菊川" type="checkbox">
                    <label for="lineGroup1-13-3">菊川</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="住吉" type="checkbox">
                    <label for="lineGroup1-13-3">住吉</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="西大島" type="checkbox">
                    <label for="lineGroup1-13-3">西大島</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="大島" type="checkbox">
                    <label for="lineGroup1-13-3">大島</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="東大島" type="checkbox">
                    <label for="lineGroup1-13-3">東大島</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="船堀" type="checkbox">
                    <label for="lineGroup1-13-3">船堀</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="一之江" type="checkbox">
                    <label for="lineGroup1-13-3">一之江</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="瑞江" type="checkbox">
                    <label for="lineGroup1-13-3">瑞江</label>
                    </li>
                    <li>
                    <input id="lineGroup1-13-3"  value="篠崎" type="checkbox">
                    <label for="lineGroup1-13-3">篠崎</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup1-14-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="都電荒川線" id="lineGroup1-14-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup1-14-0">都電荒川線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup1-14-1"  value="三ノ輪橋" type="checkbox">
                    <label for="lineGroup1-14-1">三ノ輪橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-2"  value="荒川一中前" type="checkbox">
                    <label for="lineGroup1-14-2">荒川一中前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-3"  value="荒川区役所前" type="checkbox">
                    <label for="lineGroup1-14-3">荒川区役所前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-4"  value="荒川二丁目" type="checkbox">
                    <label for="lineGroup1-14-4">荒川二丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-5"  value="荒川七丁目" type="checkbox">
                    <label for="lineGroup1-14-5">荒川七丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-6"  value="町屋駅前" type="checkbox">
                    <label for="lineGroup1-14-6">町屋駅前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="町屋二丁目" type="checkbox">
                    <label for="lineGroup1-14-7">町屋二丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="東尾久三丁目" type="checkbox">
                    <label for="lineGroup1-14-7">東尾久三丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="熊野前" type="checkbox">
                    <label for="lineGroup1-14-7">熊野前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="宮ノ前" type="checkbox">
                    <label for="lineGroup1-14-7">宮ノ前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="小台" type="checkbox">
                    <label for="lineGroup1-14-7">小台</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="荒川遊園地前" type="checkbox">
                    <label for="lineGroup1-14-7">荒川遊園地前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="荒川車庫前" type="checkbox">
                    <label for="lineGroup1-14-7">荒川車庫前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="梶原" type="checkbox">
                    <label for="lineGroup1-14-7">梶原</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="栄町" type="checkbox">
                    <label for="lineGroup1-14-7">栄町</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="王子駅前" type="checkbox">
                    <label for="lineGroup1-14-7">王子駅前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="飛鳥山" type="checkbox">
                    <label for="lineGroup1-14-7">飛鳥山</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="滝野川一丁目" type="checkbox">
                    <label for="lineGroup1-14-7">滝野川一丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="西ヶ原四丁目" type="checkbox">
                    <label for="lineGroup1-14-7">西ヶ原四丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="新庚申塚" type="checkbox">
                    <label for="lineGroup1-14-7">新庚申塚</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="庚申塚" type="checkbox">
                    <label for="lineGroup1-14-7">庚申塚</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="巣鴨新田" type="checkbox">
                    <label for="lineGroup1-14-7">巣鴨新田</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="大塚駅前" type="checkbox">
                    <label for="lineGroup1-14-7">大塚駅前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="向原" type="checkbox">
                    <label for="lineGroup1-14-7">向原</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="東池袋四丁目" type="checkbox">
                    <label for="lineGroup1-14-7">東池袋四丁目</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="都電雑司ヶ谷" type="checkbox">
                    <label for="lineGroup1-14-7">都電雑司ヶ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="鬼子母神前" type="checkbox">
                    <label for="lineGroup1-14-7">鬼子母神前</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="学習院下" type="checkbox">
                    <label for="lineGroup1-14-7">学習院下</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="面影橋" type="checkbox">
                    <label for="lineGroup1-14-7">面影橋</label>
                    </li>
                    <li>
                    <input id="lineGroup1-14-7"  value="早稲田" type="checkbox">
                    <label for="lineGroup1-14-7">早稲田</label>
                    </li>
                    </ul>
                    </div>
                    <!-- /東京 -->

                    <!-- JR -->
                    <div id="lineGroup2-1-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR東海道本線" id="lineGroup2-1-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-1-0">JR東海道本線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-1-1"  value="東京" type="checkbox">
                    <label for="lineGroup2-1-1">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-1-1"  value="新橋" type="checkbox">
                    <label for="lineGroup2-1-1">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-1-1"  value="品川" type="checkbox">
                    <label for="lineGroup2-1-1">品川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-2-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR山手線" id="lineGroup2-2-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-2-0">JR山手線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-2-1"  value="大崎" type="checkbox">
                    <label for="lineGroup2-2-1">大崎</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="五反田" type="checkbox">
                    <label for="lineGroup2-2-2">五反田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="目黒" type="checkbox">
                    <label for="lineGroup2-2-2">目黒</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="恵比寿" type="checkbox">
                    <label for="lineGroup2-2-2">恵比寿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="渋谷" type="checkbox">
                    <label for="lineGroup2-2-2">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="原宿" type="checkbox">
                    <label for="lineGroup2-2-2">原宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="代々木" type="checkbox">
                    <label for="lineGroup2-2-2">代々木</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-2-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="新大久保" type="checkbox">
                    <label for="lineGroup2-2-2">新大久保</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="高田馬場" type="checkbox">
                    <label for="lineGroup2-2-2">高田馬場</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="目白" type="checkbox">
                    <label for="lineGroup2-2-2">目白</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="池袋" type="checkbox">
                    <label for="lineGroup2-2-2">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="大塚" type="checkbox">
                    <label for="lineGroup2-2-2">大塚</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="巣鴨" type="checkbox">
                    <label for="lineGroup2-2-2">巣鴨</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="駒込" type="checkbox">
                    <label for="lineGroup2-2-2">駒込</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="田端" type="checkbox">
                    <label for="lineGroup2-2-2">田端</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="西日暮里" type="checkbox">
                    <label for="lineGroup2-2-2">西日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="日暮里" type="checkbox">
                    <label for="lineGroup2-2-2">日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="鶯谷" type="checkbox">
                    <label for="lineGroup2-2-2">鶯谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="上野" type="checkbox">
                    <label for="lineGroup2-2-2">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="御徒町" type="checkbox">
                    <label for="lineGroup2-2-2">御徒町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="秋葉原" type="checkbox">
                    <label for="lineGroup2-2-2">秋葉原</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="神田" type="checkbox">
                    <label for="lineGroup2-2-2">神田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="東京" type="checkbox">
                    <label for="lineGroup2-2-2">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="有楽町" type="checkbox">
                    <label for="lineGroup2-2-2">有楽町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="新橋" type="checkbox">
                    <label for="lineGroup2-2-2">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="浜松町" type="checkbox">
                    <label for="lineGroup2-2-2">浜松町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="田町" type="checkbox">
                    <label for="lineGroup2-2-2">田町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-2-2"  value="品川" type="checkbox">
                    <label for="lineGroup2-2-2">品川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-3-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR南武線" id="lineGroup2-3-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-3-0">JR南武線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-3-1"  value="矢野口" type="checkbox">
                    <label for="lineGroup2-3-1">矢野口</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-2"  value="稲城長沼" type="checkbox">
                    <label for="lineGroup2-3-2">稲城長沼</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-3"  value="南多摩" type="checkbox">
                    <label for="lineGroup2-3-3">南多摩</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-4"  value="府中本町" type="checkbox">
                    <label for="lineGroup2-3-4">府中本町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-5"  value="分倍河原" type="checkbox">
                    <label for="lineGroup2-3-5">分倍河原</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-6"  value="西府" type="checkbox">
                    <label for="lineGroup2-3-6">西府</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-7"  value="谷保" type="checkbox">
                    <label for="lineGroup2-3-7">谷保</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-7"  value="矢川" type="checkbox">
                    <label for="lineGroup2-3-7">矢川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-7"  value="西国立" type="checkbox">
                    <label for="lineGroup2-3-7">西国立</label>
                    </li>
                    <li>
                    <input id="lineGroup2-3-7"  value="立川" type="checkbox">
                    <label for="lineGroup2-3-7">立川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-4-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR武蔵野線" id="lineGroup2-4-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-4-0">JR武蔵野線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-4-1"  value="府中本町" type="checkbox">
                    <label for="lineGroup2-4-1">府中本町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-4-2"  value="北府中" type="checkbox">
                    <label for="lineGroup2-4-2">北府中</label>
                    </li>
                    <li>
                    <input id="lineGroup2-4-2"  value="西国分寺" type="checkbox">
                    <label for="lineGroup2-4-2">西国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-4-2"  value="新小平" type="checkbox">
                    <label for="lineGroup2-4-2">新小平</label>
                    </li>
                    <li>
                    <input id="lineGroup2-4-2"  value="新秋津" type="checkbox">
                    <label for="lineGroup2-4-2">新秋津</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-5-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR横浜線" id="lineGroup2-5-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-5-0">JR横浜線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-5-1"  value="成瀬" type="checkbox">
                    <label for="lineGroup2-5-1">成瀬</label>
                    </li>
                    <li>
                    <input id="lineGroup2-5-2"  value="町田" type="checkbox">
                    <label for="lineGroup2-5-2">町田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-5-2"  value="相原" type="checkbox">
                    <label for="lineGroup2-5-2">相原</label>
                    </li>
                    <li>
                    <input id="lineGroup2-5-2"  value="八王子みなみ野" type="checkbox">
                    <label for="lineGroup2-5-2">八王子みなみ野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-5-2"  value="片倉" type="checkbox">
                    <label for="lineGroup2-5-2">片倉</label>
                    </li>
                    <li>
                    <input id="lineGroup2-5-2"  value="八王子" type="checkbox">
                    <label for="lineGroup2-5-2">八王子</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-6-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR横須賀線" id="lineGroup2-6-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-6-0">JR横須賀線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-6-1"  value="東京" type="checkbox">
                    <label for="lineGroup2-6-1">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-6-2"  value="新橋" type="checkbox">
                    <label for="lineGroup2-6-2">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-6-2"  value="品川" type="checkbox">
                    <label for="lineGroup2-6-2">品川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-6-2"  value="西大井" type="checkbox">
                    <label for="lineGroup2-6-2">西大井</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-7-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR中央本線" id="lineGroup2-7-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-7-0">JR中央本線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-7-1"  value="東京" type="checkbox">
                    <label for="lineGroup2-7-1">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="四ツ谷" type="checkbox">
                    <label for="lineGroup2-7-2">四ツ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-7-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="吉祥寺" type="checkbox">
                    <label for="lineGroup2-7-2">吉祥寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="三鷹" type="checkbox">
                    <label for="lineGroup2-7-2">三鷹</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="国分寺" type="checkbox">
                    <label for="lineGroup2-7-2">国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="立川" type="checkbox">
                    <label for="lineGroup2-7-2">立川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="日野" type="checkbox">
                    <label for="lineGroup2-7-2">日野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="豊田" type="checkbox">
                    <label for="lineGroup2-7-2">豊田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="八王子" type="checkbox">
                    <label for="lineGroup2-7-2">八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="西八王子" type="checkbox">
                    <label for="lineGroup2-7-2">西八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-7-2"  value="高尾" type="checkbox">
                    <label for="lineGroup2-7-2">高尾</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-8-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR中央線" id="lineGroup2-8-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-8-0">JR中央線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-8-1"  value="東京" type="checkbox">
                    <label for="lineGroup2-8-1">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="神田" type="checkbox">
                    <label for="lineGroup2-8-2">神田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="御茶ノ水" type="checkbox">
                    <label for="lineGroup2-8-2">御茶ノ水</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="水道橋" type="checkbox">
                    <label for="lineGroup2-8-2">水道橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="飯田橋" type="checkbox">
                    <label for="lineGroup2-8-2">飯田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="市ケ谷" type="checkbox">
                    <label for="lineGroup2-8-2">市ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="四ツ谷" type="checkbox">
                    <label for="lineGroup2-8-2">四ツ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="信濃町" type="checkbox">
                    <label for="lineGroup2-8-2">信濃町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="千駄ケ谷" type="checkbox">
                    <label for="lineGroup2-8-2">千駄ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="代々木" type="checkbox">
                    <label for="lineGroup2-8-2">代々木</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-8-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="大久保" type="checkbox">
                    <label for="lineGroup2-8-2">大久保</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="東中野" type="checkbox">
                    <label for="lineGroup2-8-2">東中野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="中野" type="checkbox">
                    <label for="lineGroup2-8-2">中野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="高円寺" type="checkbox">
                    <label for="lineGroup2-8-2">高円寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="阿佐ケ谷" type="checkbox">
                    <label for="lineGroup2-8-2">阿佐ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="荻窪" type="checkbox">
                    <label for="lineGroup2-8-2">荻窪</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="西荻窪" type="checkbox">
                    <label for="lineGroup2-8-2">西荻窪</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="吉祥寺" type="checkbox">
                    <label for="lineGroup2-8-2">吉祥寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="三鷹" type="checkbox">
                    <label for="lineGroup2-8-2">三鷹</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="武蔵境" type="checkbox">
                    <label for="lineGroup2-8-2">武蔵境</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="東小金井" type="checkbox">
                    <label for="lineGroup2-8-2">東小金井</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="武蔵小金井" type="checkbox">
                    <label for="lineGroup2-8-2">武蔵小金井</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="国分寺" type="checkbox">
                    <label for="lineGroup2-8-2">国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="西国分寺" type="checkbox">
                    <label for="lineGroup2-8-2">西国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="国立" type="checkbox">
                    <label for="lineGroup2-8-2">国立</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="立川" type="checkbox">
                    <label for="lineGroup2-8-2">立川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="日野" type="checkbox">
                    <label for="lineGroup2-8-2">日野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="豊田" type="checkbox">
                    <label for="lineGroup2-8-2">豊田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="八王子" type="checkbox">
                    <label for="lineGroup2-8-2">八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="西八王子" type="checkbox">
                    <label for="lineGroup2-8-2">西八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-8-2"  value="高尾" type="checkbox">
                    <label for="lineGroup2-8-2">高尾</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-9-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR中央・総武線" id="lineGroup2-9-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-9-0">JR中央・総武線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-9-1"  value="三鷹" type="checkbox">
                    <label for="lineGroup2-9-1">三鷹</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="吉祥寺" type="checkbox">
                    <label for="lineGroup2-9-2">吉祥寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="西荻窪" type="checkbox">
                    <label for="lineGroup2-9-2">西荻窪</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="荻窪" type="checkbox">
                    <label for="lineGroup2-9-2">荻窪</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="阿佐ケ谷" type="checkbox">
                    <label for="lineGroup2-9-2">阿佐ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="高円寺" type="checkbox">
                    <label for="lineGroup2-9-2">高円寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="中野" type="checkbox">
                    <label for="lineGroup2-9-2">中野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="東中野" type="checkbox">
                    <label for="lineGroup2-9-2">東中野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="大久保" type="checkbox">
                    <label for="lineGroup2-9-2">大久保</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-9-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="代々木" type="checkbox">
                    <label for="lineGroup2-9-2">代々木</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="千駄ケ谷" type="checkbox">
                    <label for="lineGroup2-9-2">千駄ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="信濃町" type="checkbox">
                    <label for="lineGroup2-9-2">信濃町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="四ツ谷" type="checkbox">
                    <label for="lineGroup2-9-2">四ツ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="市ケ谷" type="checkbox">
                    <label for="lineGroup2-9-2">市ケ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="飯田橋" type="checkbox">
                    <label for="lineGroup2-9-2">飯田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="水道橋" type="checkbox">
                    <label for="lineGroup2-9-2">水道橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="御茶ノ水" type="checkbox">
                    <label for="lineGroup2-9-2">御茶ノ水</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="秋葉原" type="checkbox">
                    <label for="lineGroup2-9-2">秋葉原</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="浅草橋" type="checkbox">
                    <label for="lineGroup2-9-2">浅草橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="両国" type="checkbox">
                    <label for="lineGroup2-9-2">両国</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="錦糸町" type="checkbox">
                    <label for="lineGroup2-9-2">錦糸町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="亀戸" type="checkbox">
                    <label for="lineGroup2-9-2">亀戸</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="平井" type="checkbox">
                    <label for="lineGroup2-9-2">平井</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="新小岩" type="checkbox">
                    <label for="lineGroup2-9-2">新小岩</label>
                    </li>
                    <li>
                    <input id="lineGroup2-9-2"  value="小岩" type="checkbox">
                    <label for="lineGroup2-9-2">小岩</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-10-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR総武本線" id="lineGroup2-10-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-10-0">JR総武本線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-10-1"  value="東京" type="checkbox">
                    <label for="lineGroup2-10-1">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-10-2"  value="新日本橋" type="checkbox">
                    <label for="lineGroup2-10-2">新日本橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-10-2"  value="馬喰町" type="checkbox">
                    <label for="lineGroup2-10-2">馬喰町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-10-2"  value="錦糸町" type="checkbox">
                    <label for="lineGroup2-10-2">錦糸町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-10-2"  value="新小岩" type="checkbox">
                    <label for="lineGroup2-10-2">新小岩</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-11-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR青梅線" id="lineGroup2-11-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-11-0">JR青梅線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-11-1"  value="立川" type="checkbox">
                    <label for="lineGroup2-11-1">立川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="西立川" type="checkbox">
                    <label for="lineGroup2-11-2">西立川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="東中神" type="checkbox">
                    <label for="lineGroup2-11-2">東中神</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="中神" type="checkbox">
                    <label for="lineGroup2-11-2">中神</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="昭島" type="checkbox">
                    <label for="lineGroup2-11-2">昭島</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="拝島" type="checkbox">
                    <label for="lineGroup2-11-2">拝島</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="牛浜" type="checkbox">
                    <label for="lineGroup2-11-2">牛浜</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="福生" type="checkbox">
                    <label for="lineGroup2-11-2">福生</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="羽村" type="checkbox">
                    <label for="lineGroup2-11-2">羽村</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="小作" type="checkbox">
                    <label for="lineGroup2-11-2">小作</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="河辺" type="checkbox">
                    <label for="lineGroup2-11-2">河辺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="東青梅" type="checkbox">
                    <label for="lineGroup2-11-2">東青梅</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="青梅" type="checkbox">
                    <label for="lineGroup2-11-2">青梅</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="宮ノ平" type="checkbox">
                    <label for="lineGroup2-11-2">宮ノ平</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="日向和田" type="checkbox">
                    <label for="lineGroup2-11-2">日向和田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="石神前" type="checkbox">
                    <label for="lineGroup2-11-2">石神前</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="二俣尾" type="checkbox">
                    <label for="lineGroup2-11-2">二俣尾</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="軍畑" type="checkbox">
                    <label for="lineGroup2-11-2">軍畑</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="沢井" type="checkbox">
                    <label for="lineGroup2-11-2">沢井</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="御嶽" type="checkbox">
                    <label for="lineGroup2-11-2">御嶽</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="川井" type="checkbox">
                    <label for="lineGroup2-11-2">川井</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="古里" type="checkbox">
                    <label for="lineGroup2-11-2">古里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="鳩ノ巣" type="checkbox">
                    <label for="lineGroup2-11-2">鳩ノ巣</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="白丸" type="checkbox">
                    <label for="lineGroup2-11-2">白丸</label>
                    </li>
                    <li>
                    <input id="lineGroup2-11-2"  value="奥多摩" type="checkbox">
                    <label for="lineGroup2-11-2">奥多摩</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-12-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR五日市線" id="lineGroup2-12-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-12-0">JR五日市線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-12-1"  value="拝島" type="checkbox">
                    <label for="lineGroup2-12-1">拝島</label>
                    </li>
                    <li>
                    <input id="lineGroup2-12-2"  value="熊川" type="checkbox">
                    <label for="lineGroup2-12-2">熊川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-12-2"  value="東秋留" type="checkbox">
                    <label for="lineGroup2-12-2">東秋留</label>
                    </li>
                    <li>
                    <input id="lineGroup2-12-2"  value="秋川" type="checkbox">
                    <label for="lineGroup2-12-2">秋川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-12-2"  value="武蔵引田" type="checkbox">
                    <label for="lineGroup2-12-2">武蔵引田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-12-2"  value="武蔵増戸" type="checkbox">
                    <label for="lineGroup2-12-2">武蔵増戸</label>
                    </li>
                    <li>
                    <input id="lineGroup2-12-2"  value="武蔵五日市" type="checkbox">
                    <label for="lineGroup2-12-2">武蔵五日市</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-13-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR八高線" id="lineGroup2-13-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-13-0">JR八高線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-13-1"  value="八王子" type="checkbox">
                    <label for="lineGroup2-13-1">八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-13-2"  value="北八王子" type="checkbox">
                    <label for="lineGroup2-13-2">北八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-13-2"  value="小宮" type="checkbox">
                    <label for="lineGroup2-13-2">小宮</label>
                    </li>
                    <li>
                    <input id="lineGroup2-13-2"  value="拝島" type="checkbox">
                    <label for="lineGroup2-13-2">拝島</label>
                    </li>
                    <li>
                    <input id="lineGroup2-13-2"  value="東福生" type="checkbox">
                    <label for="lineGroup2-13-2">東福生</label>
                    </li>
                    <li>
                    <input id="lineGroup2-13-2"  value="箱根ケ崎" type="checkbox">
                    <label for="lineGroup2-13-2">箱根ケ崎</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-14-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="宇都宮線" id="lineGroup2-14-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-14-0">宇都宮線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-14-1"  value="上野" type="checkbox">
                    <label for="lineGroup2-14-1">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-14-2"  value="尾久" type="checkbox">
                    <label for="lineGroup2-14-2">尾久</label>
                    </li>
                    <li>
                    <input id="lineGroup2-14-2"  value="赤羽" type="checkbox">
                    <label for="lineGroup2-14-2">赤羽</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-15-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR常磐線" id="lineGroup2-15-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-15-0">JR常磐線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-15-1"  value="上野" type="checkbox">
                    <label for="lineGroup2-15-1">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="日暮里" type="checkbox">
                    <label for="lineGroup2-15-2">日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="三河島" type="checkbox">
                    <label for="lineGroup2-15-2">三河島</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="南千住" type="checkbox">
                    <label for="lineGroup2-15-2">南千住</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="北千住" type="checkbox">
                    <label for="lineGroup2-15-2">北千住</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="綾瀬" type="checkbox">
                    <label for="lineGroup2-15-2">綾瀬</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="亀有" type="checkbox">
                    <label for="lineGroup2-15-2">亀有</label>
                    </li>
                    <li>
                    <input id="lineGroup2-15-2"  value="金町" type="checkbox">
                    <label for="lineGroup2-15-2">金町</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-16-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR埼京線" id="lineGroup2-16-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-16-0">JR埼京線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-16-1"  value="大崎" type="checkbox">
                    <label for="lineGroup2-16-1">大崎</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="恵比寿" type="checkbox">
                    <label for="lineGroup2-16-2">恵比寿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="渋谷" type="checkbox">
                    <label for="lineGroup2-16-2">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-16-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="池袋" type="checkbox">
                    <label for="lineGroup2-16-2">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="板橋" type="checkbox">
                    <label for="lineGroup2-16-2">板橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="十条" type="checkbox">
                    <label for="lineGroup2-16-2">十条</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="赤羽" type="checkbox">
                    <label for="lineGroup2-16-2">赤羽</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="北赤羽" type="checkbox">
                    <label for="lineGroup2-16-2">北赤羽</label>
                    </li>
                    <li>
                    <input id="lineGroup2-16-2"  value="浮間舟渡" type="checkbox">
                    <label for="lineGroup2-16-2">浮間舟渡</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-17-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR高崎線" id="lineGroup2-17-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-17-0">JR高崎線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-17-1"  value="上野" type="checkbox">
                    <label for="lineGroup2-17-1">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-17-2"  value="尾久" type="checkbox">
                    <label for="lineGroup2-17-2">尾久</label>
                    </li>
                    <li>
                    <input id="lineGroup2-17-2"  value="赤羽" type="checkbox">
                    <label for="lineGroup2-17-2">赤羽</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-18-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR京葉線" id="lineGroup2-18-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-18-0">JR京葉線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-18-1"  value="東京" type="checkbox">
                    <label for="lineGroup2-18-1">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-18-2"  value="八丁堀" type="checkbox">
                    <label for="lineGroup2-18-2">八丁堀</label>
                    </li>
                    <li>
                    <input id="lineGroup2-18-2"  value="越中島" type="checkbox">
                    <label for="lineGroup2-18-2">越中島</label>
                    </li>
                    <li>
                    <input id="lineGroup2-18-2"  value="潮見" type="checkbox">
                    <label for="lineGroup2-18-2">潮見</label>
                    </li>
                    <li>
                    <input id="lineGroup2-18-2"  value="新木場" type="checkbox">
                    <label for="lineGroup2-18-2">新木場</label>
                    </li>
                    <li>
                    <input id="lineGroup2-18-2"  value="葛西臨海公園" type="checkbox">
                    <label for="lineGroup2-18-2">葛西臨海公園</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-19-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR成田エクスプレス" id="lineGroup2-19-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-19-0">JR成田エクスプレス<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-19-1"  value="池袋" type="checkbox">
                    <label for="lineGroup2-19-1">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-19-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="高尾" type="checkbox">
                    <label for="lineGroup2-19-2">高尾</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="八王子" type="checkbox">
                    <label for="lineGroup2-19-2">八王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="立川" type="checkbox">
                    <label for="lineGroup2-19-2">立川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="国分寺" type="checkbox">
                    <label for="lineGroup2-19-2">国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="三鷹" type="checkbox">
                    <label for="lineGroup2-19-2">三鷹</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="吉祥寺" type="checkbox">
                    <label for="lineGroup2-19-2">吉祥寺</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="渋谷" type="checkbox">
                    <label for="lineGroup2-19-2">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="品川" type="checkbox">
                    <label for="lineGroup2-19-2">品川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-19-2"  value="東京" type="checkbox">
                    <label for="lineGroup2-19-2">東京</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-20-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR京浜東北線" id="lineGroup2-20-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-20-0">JR京浜東北線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-20-1"  value="赤羽" type="checkbox">
                    <label for="lineGroup2-20-1">赤羽</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="東十条" type="checkbox">
                    <label for="lineGroup2-20-2">東十条</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="王子" type="checkbox">
                    <label for="lineGroup2-20-2">王子</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="上中里" type="checkbox">
                    <label for="lineGroup2-20-2">上中里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="田端" type="checkbox">
                    <label for="lineGroup2-20-2">田端</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="西日暮里" type="checkbox">
                    <label for="lineGroup2-20-2">西日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="日暮里" type="checkbox">
                    <label for="lineGroup2-20-2">日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="鶯谷" type="checkbox">
                    <label for="lineGroup2-20-2">鶯谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="上野" type="checkbox">
                    <label for="lineGroup2-20-2">上野</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="御徒町" type="checkbox">
                    <label for="lineGroup2-20-2">御徒町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="秋葉原" type="checkbox">
                    <label for="lineGroup2-20-2">秋葉原</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="神田" type="checkbox">
                    <label for="lineGroup2-20-2">神田</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="東京" type="checkbox">
                    <label for="lineGroup2-20-2">東京</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="有楽町" type="checkbox">
                    <label for="lineGroup2-20-2">有楽町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="新橋" type="checkbox">
                    <label for="lineGroup2-20-2">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="浜松町" type="checkbox">
                    <label for="lineGroup2-20-2">浜松町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="田町" type="checkbox">
                    <label for="lineGroup2-20-2">田町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="品川" type="checkbox">
                    <label for="lineGroup2-20-2">品川</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="大井町" type="checkbox">
                    <label for="lineGroup2-20-2">大井町</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="大森" type="checkbox">
                    <label for="lineGroup2-20-2">大森</label>
                    </li>
                    <li>
                    <input id="lineGroup2-20-2"  value="蒲田" type="checkbox">
                    <label for="lineGroup2-20-2">蒲田</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup2-21-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="JR湘南新宿ライン" id="lineGroup2-21-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup2-21-0">JR湘南新宿ライン<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup2-21-1"  value="赤羽" type="checkbox">
                    <label for="lineGroup2-21-1">赤羽</label>
                    </li>
                    <li>
                    <input id="lineGroup2-21-2"  value="池袋" type="checkbox">
                    <label for="lineGroup2-21-2">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup2-21-2"  value="新宿" type="checkbox">
                    <label for="lineGroup2-21-2">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-21-2"  value="渋谷" type="checkbox">
                    <label for="lineGroup2-21-2">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup2-21-2"  value="恵比寿" type="checkbox">
                    <label for="lineGroup2-21-2">恵比寿</label>
                    </li>
                    <li>
                    <input id="lineGroup2-21-2"  value="大崎" type="checkbox">
                    <label for="lineGroup2-21-2">大崎</label>
                    </li>
                    <li>
                    <input id="lineGroup2-21-2"  value="西大井" type="checkbox">
                    <label for="lineGroup2-21-2">西大井</label>
                    </li>
                    </ul>
                    </div>
                    <!-- /JR -->

                    <!-- 東急・京王 -->
                    <div id="lineGroup3-1-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急東横線" id="lineGroup3-1-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-1-0">東急東横線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-1-1"  value="渋谷" type="checkbox">
                    <label for="lineGroup3-1-1">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="代官山" type="checkbox">
                    <label for="lineGroup3-1-1">代官山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="中目黒" type="checkbox">
                    <label for="lineGroup3-1-1">中目黒</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="祐天寺" type="checkbox">
                    <label for="lineGroup3-1-1">祐天寺</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="学芸大学" type="checkbox">
                    <label for="lineGroup3-1-1">学芸大学</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="都立大学" type="checkbox">
                    <label for="lineGroup3-1-1">都立大学</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="自由が丘" type="checkbox">
                    <label for="lineGroup3-1-1">自由が丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="田園調布" type="checkbox">
                    <label for="lineGroup3-1-1">田園調布</label>
                    </li>
                    <li>
                    <input id="lineGroup3-1-1"  value="多摩川" type="checkbox">
                    <label for="lineGroup3-1-1">多摩川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-2-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急目黒線" id="lineGroup3-2-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-2-0">東急目黒線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-2-1"  value="目黒" type="checkbox">
                    <label for="lineGroup3-2-1">目黒</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="不動前" type="checkbox">
                    <label for="lineGroup3-2-1">不動前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="武蔵小山" type="checkbox">
                    <label for="lineGroup3-2-1">武蔵小山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="西小山" type="checkbox">
                    <label for="lineGroup3-2-1">西小山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="洗足" type="checkbox">
                    <label for="lineGroup3-2-1">洗足</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="大岡山" type="checkbox">
                    <label for="lineGroup3-2-1">大岡山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="奥沢" type="checkbox">
                    <label for="lineGroup3-2-1">奥沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="田園調布" type="checkbox">
                    <label for="lineGroup3-2-1">田園調布</label>
                    </li>
                    <li>
                    <input id="lineGroup3-2-1"  value="多摩川" type="checkbox">
                    <label for="lineGroup3-2-1">多摩川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-3-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急田園都市線" id="lineGroup3-3-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-3-0">東急田園都市線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-3-1"  value="渋谷" type="checkbox">
                    <label for="lineGroup3-3-1">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="池尻大橋" type="checkbox">
                    <label for="lineGroup3-3-1">池尻大橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="三軒茶屋" type="checkbox">
                    <label for="lineGroup3-3-1">三軒茶屋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="駒沢大学" type="checkbox">
                    <label for="lineGroup3-3-1">駒沢大学</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="桜新町" type="checkbox">
                    <label for="lineGroup3-3-1">桜新町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="用賀" type="checkbox">
                    <label for="lineGroup3-3-1">用賀</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="二子玉川" type="checkbox">
                    <label for="lineGroup3-3-1">二子玉川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="つくし野" type="checkbox">
                    <label for="lineGroup3-3-1">つくし野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="すずかけ台" type="checkbox">
                    <label for="lineGroup3-3-1">すずかけ台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-3-1"  value="南町田" type="checkbox">
                    <label for="lineGroup3-3-1">南町田</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-4-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急大井町線" id="lineGroup3-4-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-4-0">東急大井町線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-4-1"  value="大井町" type="checkbox">
                    <label for="lineGroup3-4-1">大井町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="下神明" type="checkbox">
                    <label for="lineGroup3-4-1">下神明</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="戸越公園" type="checkbox">
                    <label for="lineGroup3-4-1">戸越公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="中延" type="checkbox">
                    <label for="lineGroup3-4-1">中延</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="荏原町" type="checkbox">
                    <label for="lineGroup3-4-1">荏原町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="旗の台" type="checkbox">
                    <label for="lineGroup3-4-1">旗の台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="北千束" type="checkbox">
                    <label for="lineGroup3-4-1">北千束</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="大岡山" type="checkbox">
                    <label for="lineGroup3-4-1">大岡山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="緑が丘" type="checkbox">
                    <label for="lineGroup3-4-1">緑が丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="自由が丘" type="checkbox">
                    <label for="lineGroup3-4-1">自由が丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="九品仏" type="checkbox">
                    <label for="lineGroup3-4-1">九品仏</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="尾山台" type="checkbox">
                    <label for="lineGroup3-4-1">尾山台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="等々力" type="checkbox">
                    <label for="lineGroup3-4-1">等々力</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="上野毛" type="checkbox">
                    <label for="lineGroup3-4-1">上野毛</label>
                    </li>
                    <li>
                    <input id="lineGroup3-4-1"  value="二子玉川" type="checkbox">
                    <label for="lineGroup3-4-1">二子玉川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-5-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急池上線" id="lineGroup3-5-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-5-0">東急池上線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-5-1"  value="五反田" type="checkbox">
                    <label for="lineGroup3-5-1">五反田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="大崎広小路" type="checkbox">
                    <label for="lineGroup3-5-1">大崎広小路</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="戸越銀座" type="checkbox">
                    <label for="lineGroup3-5-1">戸越銀座</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="荏原中延" type="checkbox">
                    <label for="lineGroup3-5-1">荏原中延</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="旗の台" type="checkbox">
                    <label for="lineGroup3-5-1">旗の台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="長原" type="checkbox">
                    <label for="lineGroup3-5-1">長原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="洗足池" type="checkbox">
                    <label for="lineGroup3-5-1">洗足池</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="石川台" type="checkbox">
                    <label for="lineGroup3-5-1">石川台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="雪が谷大塚" type="checkbox">
                    <label for="lineGroup3-5-1">雪が谷大塚</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="御嶽山" type="checkbox">
                    <label for="lineGroup3-5-1">御嶽山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="久が原" type="checkbox">
                    <label for="lineGroup3-5-1">久が原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="千鳥町" type="checkbox">
                    <label for="lineGroup3-5-1">千鳥町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="池上" type="checkbox">
                    <label for="lineGroup3-5-1">池上</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="蓮沼" type="checkbox">
                    <label for="lineGroup3-5-1">蓮沼</label>
                    </li>
                    <li>
                    <input id="lineGroup3-5-1"  value="蒲田" type="checkbox">
                    <label for="lineGroup3-5-1">蒲田</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-6-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急多摩川線" id="lineGroup3-6-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-6-0">東急多摩川線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-6-1"  value="多摩川" type="checkbox">
                    <label for="lineGroup3-6-1">多摩川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-6-1"  value="沼部" type="checkbox">
                    <label for="lineGroup3-6-1">沼部</label>
                    </li>
                    <li>
                    <input id="lineGroup3-6-1"  value="鵜の木" type="checkbox">
                    <label for="lineGroup3-6-1">鵜の木</label>
                    </li>
                    <li>
                    <input id="lineGroup3-6-1"  value="下丸子" type="checkbox">
                    <label for="lineGroup3-6-1">下丸子</label>
                    </li>
                    <li>
                    <input id="lineGroup3-6-1"  value="武蔵新田" type="checkbox">
                    <label for="lineGroup3-6-1">武蔵新田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-6-1"  value="矢口渡" type="checkbox">
                    <label for="lineGroup3-6-1">矢口渡</label>
                    </li>
                    <li>
                    <input id="lineGroup3-6-1"  value="蒲田" type="checkbox">
                    <label for="lineGroup3-6-1">蒲田</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-7-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東急世田谷線" id="lineGroup3-7-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-7-0">東急世田谷線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-7-1"  value="三軒茶屋" type="checkbox">
                    <label for="lineGroup3-7-1">三軒茶屋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="西太子堂" type="checkbox">
                    <label for="lineGroup3-7-1">西太子堂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="若林" type="checkbox">
                    <label for="lineGroup3-7-1">若林</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="松陰神社前" type="checkbox">
                    <label for="lineGroup3-7-1">松陰神社前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="世田谷" type="checkbox">
                    <label for="lineGroup3-7-1">世田谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="上町" type="checkbox">
                    <label for="lineGroup3-7-1">上町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="宮の坂" type="checkbox">
                    <label for="lineGroup3-7-1">宮の坂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="山下" type="checkbox">
                    <label for="lineGroup3-7-1">山下</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="松原" type="checkbox">
                    <label for="lineGroup3-7-1">松原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-7-1"  value="下高井戸" type="checkbox">
                    <label for="lineGroup3-7-1">下高井戸</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-8-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京王線" id="lineGroup3-8-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-8-0">京王線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-8-1"  value="新宿" type="checkbox">
                    <label for="lineGroup3-8-1">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="初台" type="checkbox">
                    <label for="lineGroup3-8-1">初台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="幡ヶ谷" type="checkbox">
                    <label for="lineGroup3-8-1">幡ヶ谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="笹塚" type="checkbox">
                    <label for="lineGroup3-8-1">笹塚</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="代田橋" type="checkbox">
                    <label for="lineGroup3-8-1">代田橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="明大前" type="checkbox">
                    <label for="lineGroup3-8-1">明大前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="下高井戸" type="checkbox">
                    <label for="lineGroup3-8-1">下高井戸</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="桜上水" type="checkbox">
                    <label for="lineGroup3-8-1">桜上水</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="上北沢" type="checkbox">
                    <label for="lineGroup3-8-1">上北沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="八幡山" type="checkbox">
                    <label for="lineGroup3-8-1">八幡山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="芦花公園" type="checkbox">
                    <label for="lineGroup3-8-1">芦花公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="千歳烏山" type="checkbox">
                    <label for="lineGroup3-8-1">千歳烏山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="仙川" type="checkbox">
                    <label for="lineGroup3-8-1">仙川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="つつじヶ丘" type="checkbox">
                    <label for="lineGroup3-8-1">つつじヶ丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="柴崎" type="checkbox">
                    <label for="lineGroup3-8-1">柴崎</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="国領" type="checkbox">
                    <label for="lineGroup3-8-1">国領</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="布田" type="checkbox">
                    <label for="lineGroup3-8-1">布田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="調布" type="checkbox">
                    <label for="lineGroup3-8-1">調布</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="西調布" type="checkbox">
                    <label for="lineGroup3-8-1">西調布</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="飛田給" type="checkbox">
                    <label for="lineGroup3-8-1">飛田給</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="武蔵野台" type="checkbox">
                    <label for="lineGroup3-8-1">武蔵野台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="多磨霊園" type="checkbox">
                    <label for="lineGroup3-8-1">多磨霊園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="東府中" type="checkbox">
                    <label for="lineGroup3-8-1">東府中</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="府中" type="checkbox">
                    <label for="lineGroup3-8-1">府中</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="分倍河原" type="checkbox">
                    <label for="lineGroup3-8-1">分倍河原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="中河原" type="checkbox">
                    <label for="lineGroup3-8-1">中河原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="聖蹟桜ヶ丘" type="checkbox">
                    <label for="lineGroup3-8-1">聖蹟桜ヶ丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="百草園" type="checkbox">
                    <label for="lineGroup3-8-1">百草園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="高幡不動" type="checkbox">
                    <label for="lineGroup3-8-1">高幡不動</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="南平" type="checkbox">
                    <label for="lineGroup3-8-1">南平</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="平山城址公園" type="checkbox">
                    <label for="lineGroup3-8-1">平山城址公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="長沼" type="checkbox">
                    <label for="lineGroup3-8-1">長沼</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="北野" type="checkbox">
                    <label for="lineGroup3-8-1">北野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-8-1"  value="京王八王子" type="checkbox">
                    <label for="lineGroup3-8-1">京王八王子</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-9-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京王相模原線" id="lineGroup3-9-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-9-0">京王相模原線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-9-1"  value="調布" type="checkbox">
                    <label for="lineGroup3-9-1">調布</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="京王多摩川" type="checkbox">
                    <label for="lineGroup3-9-1">京王多摩川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="京王よみうりランド" type="checkbox">
                    <label for="lineGroup3-9-1">京王よみうりランド</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="稲城" type="checkbox">
                    <label for="lineGroup3-9-1">稲城</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="京王永山" type="checkbox">
                    <label for="lineGroup3-9-1">京王永山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="京王多摩センター" type="checkbox">
                    <label for="lineGroup3-9-1">京王多摩センター</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="京王堀之内" type="checkbox">
                    <label for="lineGroup3-9-1">京王堀之内</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="南大沢" type="checkbox">
                    <label for="lineGroup3-9-1">南大沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-9-1"  value="多摩境" type="checkbox">
                    <label for="lineGroup3-9-1">多摩境</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-10-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京王高尾線" id="lineGroup3-10-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-10-0">京王高尾線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-10-1"  value="北野" type="checkbox">
                    <label for="lineGroup3-10-1">北野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-10-1"  value="京王片倉" type="checkbox">
                    <label for="lineGroup3-10-1">京王片倉</label>
                    </li>
                    <li>
                    <input id="lineGroup3-10-1"  value="山田" type="checkbox">
                    <label for="lineGroup3-10-1">山田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-10-1"  value="めじろ台" type="checkbox">
                    <label for="lineGroup3-10-1">めじろ台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-10-1"  value="狭間" type="checkbox">
                    <label for="lineGroup3-10-1">狭間</label>
                    </li>
                    <li>
                    <input id="lineGroup3-10-1"  value="高尾" type="checkbox">
                    <label for="lineGroup3-10-1">高尾</label>
                    </li>
                    <li>
                    <input id="lineGroup3-10-1"  value="高尾山口" type="checkbox">
                    <label for="lineGroup3-10-1">高尾山口</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-11-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京王競馬場線" id="lineGroup3-11-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-11-0">京王競馬場線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-11-1"  value="東府中" type="checkbox">
                    <label for="lineGroup3-11-1">東府中</label>
                    </li>
                    <li>
                    <input id="lineGroup3-11-1"  value="府中競馬正門前" type="checkbox">
                    <label for="lineGroup3-11-1">府中競馬正門前</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-12-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京王動物園線" id="lineGroup3-12-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-12-0">京王動物園線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-12-1"  value="高幡不動" type="checkbox">
                    <label for="lineGroup3-12-1">高幡不動</label>
                    </li>
                    <li>
                    <input id="lineGroup3-12-1"  value="多摩動物公園" type="checkbox">
                    <label for="lineGroup3-12-1">多摩動物公園</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-13-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京王井の頭線" id="lineGroup3-13-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-13-0">京王井の頭線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-13-1"  value="渋谷" type="checkbox">
                    <label for="lineGroup3-13-1">渋谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="神泉" type="checkbox">
                    <label for="lineGroup3-13-1">神泉</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="駒場東大前" type="checkbox">
                    <label for="lineGroup3-13-1">駒場東大前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="池ノ上" type="checkbox">
                    <label for="lineGroup3-13-1">池ノ上</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="下北沢" type="checkbox">
                    <label for="lineGroup3-13-1">下北沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="新代田" type="checkbox">
                    <label for="lineGroup3-13-1">新代田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="東松原" type="checkbox">
                    <label for="lineGroup3-13-1">東松原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="明大前" type="checkbox">
                    <label for="lineGroup3-13-1">明大前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="永福町" type="checkbox">
                    <label for="lineGroup3-13-1">永福町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="西永福" type="checkbox">
                    <label for="lineGroup3-13-1">西永福</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="浜田山" type="checkbox">
                    <label for="lineGroup3-13-1">浜田山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="高井戸" type="checkbox">
                    <label for="lineGroup3-13-1">高井戸</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="富士見ヶ丘" type="checkbox">
                    <label for="lineGroup3-13-1">富士見ヶ丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="久我山" type="checkbox">
                    <label for="lineGroup3-13-1">久我山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="三鷹台" type="checkbox">
                    <label for="lineGroup3-13-1">三鷹台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="井の頭公園" type="checkbox">
                    <label for="lineGroup3-13-1">井の頭公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-13-1"  value="吉祥寺" type="checkbox">
                    <label for="lineGroup3-13-1">吉祥寺</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-14-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="小田急線" id="lineGroup3-14-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-14-0">小田急線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-14-1"  value="新宿" type="checkbox">
                    <label for="lineGroup3-14-1">新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="南新宿" type="checkbox">
                    <label for="lineGroup3-14-1">南新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="参宮橋" type="checkbox">
                    <label for="lineGroup3-14-1">参宮橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="代々木八幡" type="checkbox">
                    <label for="lineGroup3-14-1">代々木八幡</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="代々木上原" type="checkbox">
                    <label for="lineGroup3-14-1">代々木上原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="東北沢" type="checkbox">
                    <label for="lineGroup3-14-1">東北沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="下北沢" type="checkbox">
                    <label for="lineGroup3-14-1">下北沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="世田谷代田" type="checkbox">
                    <label for="lineGroup3-14-1">世田谷代田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="梅ヶ丘" type="checkbox">
                    <label for="lineGroup3-14-1">梅ヶ丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="豪徳寺" type="checkbox">
                    <label for="lineGroup3-14-1">豪徳寺</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="経堂" type="checkbox">
                    <label for="lineGroup3-14-1">経堂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="千歳船橋" type="checkbox">
                    <label for="lineGroup3-14-1">千歳船橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="祖師ヶ谷大蔵" type="checkbox">
                    <label for="lineGroup3-14-1">祖師ヶ谷大蔵</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="成城学園前" type="checkbox">
                    <label for="lineGroup3-14-1">成城学園前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="喜多見" type="checkbox">
                    <label for="lineGroup3-14-1">喜多見</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="狛江" type="checkbox">
                    <label for="lineGroup3-14-1">狛江</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="和泉多摩川" type="checkbox">
                    <label for="lineGroup3-14-1">和泉多摩川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="鶴川" type="checkbox">
                    <label for="lineGroup3-14-1">鶴川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="玉川学園前" type="checkbox">
                    <label for="lineGroup3-14-1">玉川学園前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-14-1"  value="町田" type="checkbox">
                    <label for="lineGroup3-14-1">町田</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-15-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="小田急多摩線" id="lineGroup3-15-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-15-0">小田急多摩線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-15-1"  value="小田急永山" type="checkbox">
                    <label for="lineGroup3-15-1">小田急永山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-15-1"  value="小田急多摩センター" type="checkbox">
                    <label for="lineGroup3-15-1">小田急多摩センター</label>
                    </li>
                    <li>
                    <input id="lineGroup3-15-1"  value="唐木田" type="checkbox">
                    <label for="lineGroup3-15-1">唐木田</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-16-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東武東上線" id="lineGroup3-16-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-16-0">東武東上線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-16-1"  value="池袋" type="checkbox">
                    <label for="lineGroup3-16-1">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="北池袋" type="checkbox">
                    <label for="lineGroup3-16-1">北池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="下板橋" type="checkbox">
                    <label for="lineGroup3-16-1">下板橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="大山" type="checkbox">
                    <label for="lineGroup3-16-1">大山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="中板橋" type="checkbox">
                    <label for="lineGroup3-16-1">中板橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="ときわ台" type="checkbox">
                    <label for="lineGroup3-16-1">ときわ台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="上板橋" type="checkbox">
                    <label for="lineGroup3-16-1">上板橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="東武練馬" type="checkbox">
                    <label for="lineGroup3-16-1">東武練馬</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="下赤塚" type="checkbox">
                    <label for="lineGroup3-16-1">下赤塚</label>
                    </li>
                    <li>
                    <input id="lineGroup3-16-1"  value="成増" type="checkbox">
                    <label for="lineGroup3-16-1">成増</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-17-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東武伊勢崎線" id="lineGroup3-17-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-17-0">東武伊勢崎線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-17-1"  value="浅草" type="checkbox">
                    <label for="lineGroup3-17-1">浅草</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="とうきょうスカイツリー" type="checkbox">
                    <label for="lineGroup3-17-1">とうきょうスカイツリー</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="押上〈スカイツリー前〉" type="checkbox">
                    <label for="lineGroup3-17-1">押上〈スカイツリー前〉</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="曳舟" type="checkbox">
                    <label for="lineGroup3-17-1">曳舟</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="東向島" type="checkbox">
                    <label for="lineGroup3-17-1">東向島</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="鐘ヶ淵" type="checkbox">
                    <label for="lineGroup3-17-1">鐘ヶ淵</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="堀切" type="checkbox">
                    <label for="lineGroup3-17-1">堀切</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="牛田" type="checkbox">
                    <label for="lineGroup3-17-1">牛田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="北千住" type="checkbox">
                    <label for="lineGroup3-17-1">北千住</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="小菅" type="checkbox">
                    <label for="lineGroup3-17-1">小菅</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="五反野" type="checkbox">
                    <label for="lineGroup3-17-1">五反野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="梅島" type="checkbox">
                    <label for="lineGroup3-17-1">梅島</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="西新井" type="checkbox">
                    <label for="lineGroup3-17-1">西新井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-17-1"  value="竹ノ塚" type="checkbox">
                    <label for="lineGroup3-17-1">竹ノ塚</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-18-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東武亀戸線" id="lineGroup3-18-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-18-0">東武亀戸線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-18-1"  value="曳舟" type="checkbox">
                    <label for="lineGroup3-18-1">曳舟</label>
                    </li>
                    <li>
                    <input id="lineGroup3-18-1"  value="小村井" type="checkbox">
                    <label for="lineGroup3-18-1">小村井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-18-1"  value="東あずま" type="checkbox">
                    <label for="lineGroup3-18-1">東あずま</label>
                    </li>
                    <li>
                    <input id="lineGroup3-18-1"  value="亀戸水神" type="checkbox">
                    <label for="lineGroup3-18-1">亀戸水神</label>
                    </li>
                    <li>
                    <input id="lineGroup3-18-1"  value="亀戸" type="checkbox">
                    <label for="lineGroup3-18-1">亀戸</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-19-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東武大師線" id="lineGroup3-19-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-19-0">東武大師線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-19-1"  value="西新井" type="checkbox">
                    <label for="lineGroup3-19-1">西新井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-19-1"  value="大師前" type="checkbox">
                    <label for="lineGroup3-19-1">大師前</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-20-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武池袋線" id="lineGroup3-20-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-20-0">西武池袋線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-20-1"  value="池袋" type="checkbox">
                    <label for="lineGroup3-20-1">池袋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="椎名町" type="checkbox">
                    <label for="lineGroup3-20-1">椎名町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="東長崎" type="checkbox">
                    <label for="lineGroup3-20-1">東長崎</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="江古田" type="checkbox">
                    <label for="lineGroup3-20-1">江古田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="桜台" type="checkbox">
                    <label for="lineGroup3-20-1">桜台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="練馬" type="checkbox">
                    <label for="lineGroup3-20-1">練馬</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="中村橋" type="checkbox">
                    <label for="lineGroup3-20-1">中村橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="富士見台" type="checkbox">
                    <label for="lineGroup3-20-1">富士見台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="練馬高野台" type="checkbox">
                    <label for="lineGroup3-20-1">練馬高野台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="石神井公園" type="checkbox">
                    <label for="lineGroup3-20-1">石神井公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="大泉学園" type="checkbox">
                    <label for="lineGroup3-20-1">大泉学園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="保谷" type="checkbox">
                    <label for="lineGroup3-20-1">保谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="ひばりヶ丘" type="checkbox">
                    <label for="lineGroup3-20-1">ひばりヶ丘</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="東久留米" type="checkbox">
                    <label for="lineGroup3-20-1">東久留米</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="清瀬" type="checkbox">
                    <label for="lineGroup3-20-1">清瀬</label>
                    </li>
                    <li>
                    <input id="lineGroup3-20-1"  value="秋津" type="checkbox">
                    <label for="lineGroup3-20-1">秋津</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-21-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武有楽町線" id="lineGroup3-21-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-21-0">西武有楽町線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-21-1"  value="小竹向原" type="checkbox">
                    <label for="lineGroup3-21-1">小竹向原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-21-1"  value="新桜台" type="checkbox">
                    <label for="lineGroup3-21-1">新桜台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-21-1"  value="練馬" type="checkbox">
                    <label for="lineGroup3-21-1">練馬</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-22-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武豊島線" id="lineGroup3-22-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-22-0">西武豊島線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-22-1"  value="練馬" type="checkbox">
                    <label for="lineGroup3-22-1">練馬</label>
                    </li>
                    <li>
                    <input id="lineGroup3-22-1"  value="豊島園" type="checkbox">
                    <label for="lineGroup3-22-1">豊島園</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-23-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="レオライナー" id="lineGroup3-23-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-23-0">レオライナー<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-23-1"  value="西武遊園地" type="checkbox">
                    <label for="lineGroup3-23-1">西武遊園地</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-24-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武新宿線" id="lineGroup3-24-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-24-0">西武新宿線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-24-1"  value="西武新宿" type="checkbox">
                    <label for="lineGroup3-24-1">西武新宿</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="高田馬場" type="checkbox">
                    <label for="lineGroup3-24-1">高田馬場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="下落合" type="checkbox">
                    <label for="lineGroup3-24-1">下落合</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="中井" type="checkbox">
                    <label for="lineGroup3-24-1">中井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="新井薬師前" type="checkbox">
                    <label for="lineGroup3-24-1">新井薬師前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="沼袋" type="checkbox">
                    <label for="lineGroup3-24-1">沼袋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="野方" type="checkbox">
                    <label for="lineGroup3-24-1">野方</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="都立家政" type="checkbox">
                    <label for="lineGroup3-24-1">都立家政</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="鷺ノ宮" type="checkbox">
                    <label for="lineGroup3-24-1">鷺ノ宮</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="下井草" type="checkbox">
                    <label for="lineGroup3-24-1">下井草</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="井荻" type="checkbox">
                    <label for="lineGroup3-24-1">井荻</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="上井草" type="checkbox">
                    <label for="lineGroup3-24-1">上井草</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="上石神井" type="checkbox">
                    <label for="lineGroup3-24-1">上石神井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="武蔵関" type="checkbox">
                    <label for="lineGroup3-24-1">武蔵関</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="東伏見" type="checkbox">
                    <label for="lineGroup3-24-1">東伏見</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="西武柳沢" type="checkbox">
                    <label for="lineGroup3-24-1">西武柳沢</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="田無" type="checkbox">
                    <label for="lineGroup3-24-1">田無</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="花小金井" type="checkbox">
                    <label for="lineGroup3-24-1">花小金井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="小平" type="checkbox">
                    <label for="lineGroup3-24-1">小平</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="久米川" type="checkbox">
                    <label for="lineGroup3-24-1">久米川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-24-1"  value="東村山" type="checkbox">
                    <label for="lineGroup3-24-1">東村山</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-25-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武拝島線" id="lineGroup3-25-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-25-0">西武拝島線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-25-1"  value="小平" type="checkbox">
                    <label for="lineGroup3-25-1">小平</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="萩山" type="checkbox">
                    <label for="lineGroup3-25-1">萩山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="小川" type="checkbox">
                    <label for="lineGroup3-25-1">小川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="東大和市" type="checkbox">
                    <label for="lineGroup3-25-1">東大和市</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="玉川上水" type="checkbox">
                    <label for="lineGroup3-25-1">玉川上水</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="武蔵砂川" type="checkbox">
                    <label for="lineGroup3-25-1">武蔵砂川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="西武立川" type="checkbox">
                    <label for="lineGroup3-25-1">西武立川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-25-1"  value="拝島" type="checkbox">
                    <label for="lineGroup3-25-1">拝島</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-26-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武西武園線" id="lineGroup3-26-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-26-0">西武西武園線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-26-1"  value="東村山" type="checkbox">
                    <label for="lineGroup3-26-1">東村山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-26-1"  value="西武園" type="checkbox">
                    <label for="lineGroup3-26-1">西武園</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-27-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武国分寺線" id="lineGroup3-27-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-27-0">西武国分寺線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-27-1"  value="国分寺" type="checkbox">
                    <label for="lineGroup3-27-1">国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup3-27-1"  value="恋ヶ窪" type="checkbox">
                    <label for="lineGroup3-27-1">恋ヶ窪</label>
                    </li>
                    <li>
                    <input id="lineGroup3-27-1"  value="鷹の台" type="checkbox">
                    <label for="lineGroup3-27-1">鷹の台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-27-1"  value="小川" type="checkbox">
                    <label for="lineGroup3-27-1">小川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-27-1"  value="東村山" type="checkbox">
                    <label for="lineGroup3-27-1">東村山</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-28-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武多摩湖線" id="lineGroup3-28-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-28-0">西武多摩湖線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-28-1"  value="国分寺" type="checkbox">
                    <label for="lineGroup3-28-1">国分寺</label>
                    </li>
                    <li>
                    <input id="lineGroup3-28-1"  value="一橋学園" type="checkbox">
                    <label for="lineGroup3-28-1">一橋学園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-28-1"  value="青梅街道" type="checkbox">
                    <label for="lineGroup3-28-1">青梅街道</label>
                    </li>
                    <li>
                    <input id="lineGroup3-28-1"  value="萩山" type="checkbox">
                    <label for="lineGroup3-28-1">萩山</label>
                    </li>
                    <li>
                    <input id="lineGroup3-28-1"  value="八坂" type="checkbox">
                    <label for="lineGroup3-28-1">八坂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-28-1"  value="武蔵大和" type="checkbox">
                    <label for="lineGroup3-28-1">武蔵大和</label>
                    </li>
                    <li>
                    <input id="lineGroup3-28-1"  value="西武遊園地" type="checkbox">
                    <label for="lineGroup3-28-1">西武遊園地</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-29-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="西武多摩川線" id="lineGroup3-29-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-29-0">西武多摩川線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-29-1"  value="武蔵境" type="checkbox">
                    <label for="lineGroup3-29-1">武蔵境</label>
                    </li>
                    <li>
                    <input id="lineGroup3-29-1"  value="新小金井" type="checkbox">
                    <label for="lineGroup3-29-1">新小金井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-29-1"  value="多磨" type="checkbox">
                    <label for="lineGroup3-29-1">多磨</label>
                    </li>
                    <li>
                    <input id="lineGroup3-29-1"  value="白糸台" type="checkbox">
                    <label for="lineGroup3-29-1">白糸台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-29-1"  value="競艇場前" type="checkbox">
                    <label for="lineGroup3-29-1">競艇場前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-29-1"  value="是政" type="checkbox">
                    <label for="lineGroup3-29-1">是政</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-30-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京成本線" id="lineGroup3-30-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-30-0">京成本線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-30-1"  value="京成上野" type="checkbox">
                    <label for="lineGroup3-30-1">京成上野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="日暮里" type="checkbox">
                    <label for="lineGroup3-30-1">日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="新三河島" type="checkbox">
                    <label for="lineGroup3-30-1">新三河島</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="町屋" type="checkbox">
                    <label for="lineGroup3-30-1">町屋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="千住大橋" type="checkbox">
                    <label for="lineGroup3-30-1">千住大橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="京成関屋" type="checkbox">
                    <label for="lineGroup3-30-1">京成関屋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="堀切菖蒲園" type="checkbox">
                    <label for="lineGroup3-30-1">堀切菖蒲園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="お花茶屋" type="checkbox">
                    <label for="lineGroup3-30-1">お花茶屋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="青砥" type="checkbox">
                    <label for="lineGroup3-30-1">青砥</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="京成高砂" type="checkbox">
                    <label for="lineGroup3-30-1">京成高砂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="京成小岩" type="checkbox">
                    <label for="lineGroup3-30-1">京成小岩</label>
                    </li>
                    <li>
                    <input id="lineGroup3-30-1"  value="江戸川" type="checkbox">
                    <label for="lineGroup3-30-1">江戸川</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-31-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京成押上線" id="lineGroup3-31-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-31-0">京成押上線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-31-1"  value="押上（スカイツリー前）" type="checkbox">
                    <label for="lineGroup3-31-1">押上（スカイツリー前）</label>
                    </li>
                    <li>
                    <input id="lineGroup3-31-1"  value="京成曳舟" type="checkbox">
                    <label for="lineGroup3-31-1">京成曳舟</label>
                    </li>
                    <li>
                    <input id="lineGroup3-31-1"  value="八広" type="checkbox">
                    <label for="lineGroup3-31-1">八広</label>
                    </li>
                    <li>
                    <input id="lineGroup3-31-1"  value="四ツ木" type="checkbox">
                    <label for="lineGroup3-31-1">四ツ木</label>
                    </li>
                    <li>
                    <input id="lineGroup3-31-1"  value="京成立石" type="checkbox">
                    <label for="lineGroup3-31-1">京成立石</label>
                    </li>
                    <li>
                    <input id="lineGroup3-31-1"  value="青砥" type="checkbox">
                    <label for="lineGroup3-31-1">青砥</label>
                    </li>
                    <li>
                    <input id="lineGroup3-31-1"  value="京成高砂" type="checkbox">
                    <label for="lineGroup3-31-1">京成高砂</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-32-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京成金町線" id="lineGroup3-32-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-32-0">京成金町線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-32-1"  value="京成高砂" type="checkbox">
                    <label for="lineGroup3-32-1">京成高砂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-32-1"  value="柴又" type="checkbox">
                    <label for="lineGroup3-32-1">柴又</label>
                    </li>
                    <li>
                    <input id="lineGroup3-32-1"  value="京成金町" type="checkbox">
                    <label for="lineGroup3-32-1">京成金町</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-33-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="成田スカイアクセス" id="lineGroup3-33-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-33-0">成田スカイアクセス<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-33-1"  value="京成上野" type="checkbox">
                    <label for="lineGroup3-33-1">京成上野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-33-1"  value="日暮里" type="checkbox">
                    <label for="lineGroup3-33-1">日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup3-33-1"  value="青砥" type="checkbox">
                    <label for="lineGroup3-33-1">青砥</label>
                    </li>
                    <li>
                    <input id="lineGroup3-33-1"  value="京成高砂" type="checkbox">
                    <label for="lineGroup3-33-1">京成高砂</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-34-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京急本線" id="lineGroup3-34-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-34-0">京急本線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-34-1"  value="泉岳寺" type="checkbox">
                    <label for="lineGroup3-34-1">泉岳寺</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="品川" type="checkbox">
                    <label for="lineGroup3-34-1">品川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="北品川" type="checkbox">
                    <label for="lineGroup3-34-1">北品川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="新馬場" type="checkbox">
                    <label for="lineGroup3-34-1">新馬場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="青物横丁" type="checkbox">
                    <label for="lineGroup3-34-1">青物横丁</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="鮫洲" type="checkbox">
                    <label for="lineGroup3-34-1">鮫洲</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="立会川" type="checkbox">
                    <label for="lineGroup3-34-1">立会川</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="大森海岸" type="checkbox">
                    <label for="lineGroup3-34-1">大森海岸</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="平和島" type="checkbox">
                    <label for="lineGroup3-34-1">平和島</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="大森町" type="checkbox">
                    <label for="lineGroup3-34-1">大森町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="梅屋敷" type="checkbox">
                    <label for="lineGroup3-34-1">梅屋敷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="京急蒲田" type="checkbox">
                    <label for="lineGroup3-34-1">京急蒲田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="雑色" type="checkbox">
                    <label for="lineGroup3-34-1">雑色</label>
                    </li>
                    <li>
                    <input id="lineGroup3-34-1"  value="六郷土手" type="checkbox">
                    <label for="lineGroup3-34-1">六郷土手</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-35-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="京急空港線" id="lineGroup3-35-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-35-0">京急空港線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-35-1"  value="京急蒲田" type="checkbox">
                    <label for="lineGroup3-35-1">京急蒲田</label>
                    </li>
                    <li>
                    <input id="lineGroup3-35-1"  value="糀谷" type="checkbox">
                    <label for="lineGroup3-35-1">糀谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-35-1"  value="大鳥居" type="checkbox">
                    <label for="lineGroup3-35-1">大鳥居</label>
                    </li>
                    <li>
                    <input id="lineGroup3-35-1"  value="穴守稲荷" type="checkbox">
                    <label for="lineGroup3-35-1">穴守稲荷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-35-1"  value="天空橋" type="checkbox">
                    <label for="lineGroup3-35-1">天空橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-35-1"  value="羽田空港国際線ターミナル" type="checkbox">
                    <label for="lineGroup3-35-1">羽田空港国際線ターミナル</label>
                    </li>
                    <li>
                    <input id="lineGroup3-35-1"  value="羽田空港" type="checkbox">
                    <label for="lineGroup3-35-1">羽田空港</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-36-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="埼玉高速鉄道線" id="lineGroup3-36-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-36-0">埼玉高速鉄道線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-36-1"  value="赤羽岩淵" type="checkbox">
                    <label for="lineGroup3-36-1">赤羽岩淵</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-37-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="つくばエクスプレス" id="lineGroup3-37-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-37-0">つくばエクスプレス<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-37-1"  value="秋葉原" type="checkbox">
                    <label for="lineGroup3-37-1">秋葉原</label>
                    </li>
                    <li>
                    <input id="lineGroup3-37-1"  value="新御徒町" type="checkbox">
                    <label for="lineGroup3-37-1">新御徒町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-37-1"  value="浅草" type="checkbox">
                    <label for="lineGroup3-37-1">浅草</label>
                    </li>
                    <li>
                    <input id="lineGroup3-37-1"  value="南千住" type="checkbox">
                    <label for="lineGroup3-37-1">南千住</label>
                    </li>
                    <li>
                    <input id="lineGroup3-37-1"  value="北千住" type="checkbox">
                    <label for="lineGroup3-37-1">北千住</label>
                    </li>
                    <li>
                    <input id="lineGroup3-37-1"  value="青井" type="checkbox">
                    <label for="lineGroup3-37-1">青井</label>
                    </li>
                    <li>
                    <input id="lineGroup3-37-1"  value="六町" type="checkbox">
                    <label for="lineGroup3-37-1">六町</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-38-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="ゆりかもめ" id="lineGroup3-38-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-38-0">ゆりかもめ<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-38-1"  value="新橋" type="checkbox">
                    <label for="lineGroup3-38-1">新橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="汐留" type="checkbox">
                    <label for="lineGroup3-38-1">汐留</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="竹芝" type="checkbox">
                    <label for="lineGroup3-38-1">竹芝</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="日の出" type="checkbox">
                    <label for="lineGroup3-38-1">日の出</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="芝浦ふ頭" type="checkbox">
                    <label for="lineGroup3-38-1">芝浦ふ頭</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="お台場海浜公園" type="checkbox">
                    <label for="lineGroup3-38-1">お台場海浜公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="台場" type="checkbox">
                    <label for="lineGroup3-38-1">台場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="船の科学館" type="checkbox">
                    <label for="lineGroup3-38-1">船の科学館</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="テレコムセンター" type="checkbox">
                    <label for="lineGroup3-38-1">テレコムセンター</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="青海" type="checkbox">
                    <label for="lineGroup3-38-1">青海</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="国際展示場正門" type="checkbox">
                    <label for="lineGroup3-38-1">国際展示場正門</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="有明" type="checkbox">
                    <label for="lineGroup3-38-1">有明</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="有明テニスの森" type="checkbox">
                    <label for="lineGroup3-38-1">有明テニスの森</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="市場前" type="checkbox">
                    <label for="lineGroup3-38-1">市場前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="新豊洲" type="checkbox">
                    <label for="lineGroup3-38-1">新豊洲</label>
                    </li>
                    <li>
                    <input id="lineGroup3-38-1"  value="豊洲" type="checkbox">
                    <label for="lineGroup3-38-1">豊洲</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-39-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="多摩モノレール" id="lineGroup3-39-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-39-0">多摩モノレール<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-39-1"  value="多摩センター" type="checkbox">
                    <label for="lineGroup3-39-1">多摩センター</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="松が谷" type="checkbox">
                    <label for="lineGroup3-39-1">松が谷</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="大塚・帝京大学" type="checkbox">
                    <label for="lineGroup3-39-1">大塚・帝京大学</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="中央大学・明星大学" type="checkbox">
                    <label for="lineGroup3-39-1">中央大学・明星大学</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="多摩動物公園" type="checkbox">
                    <label for="lineGroup3-39-1">多摩動物公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="程久保" type="checkbox">
                    <label for="lineGroup3-39-1">程久保</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="高幡不動" type="checkbox">
                    <label for="lineGroup3-39-1">高幡不動</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="万願寺" type="checkbox">
                    <label for="lineGroup3-39-1">万願寺</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="甲州街道" type="checkbox">
                    <label for="lineGroup3-39-1">甲州街道</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="柴崎体育館" type="checkbox">
                    <label for="lineGroup3-39-1">柴崎体育館</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="立川南" type="checkbox">
                    <label for="lineGroup3-39-1">立川南</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="立川北" type="checkbox">
                    <label for="lineGroup3-39-1">立川北</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="高松" type="checkbox">
                    <label for="lineGroup3-39-1">高松</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="立飛" type="checkbox">
                    <label for="lineGroup3-39-1">立飛</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="泉体育館" type="checkbox">
                    <label for="lineGroup3-39-1">泉体育館</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="砂川七番" type="checkbox">
                    <label for="lineGroup3-39-1">砂川七番</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="玉川上水" type="checkbox">
                    <label for="lineGroup3-39-1">玉川上水</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="桜街道" type="checkbox">
                    <label for="lineGroup3-39-1">桜街道</label>
                    </li>
                    <li>
                    <input id="lineGroup3-39-1"  value="上北台" type="checkbox">
                    <label for="lineGroup3-39-1">上北台</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-40-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="東京モノレール" id="lineGroup3-40-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-40-0">東京モノレール<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-40-1"  value="浜松町" type="checkbox">
                    <label for="lineGroup3-40-1">浜松町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="天王洲アイル" type="checkbox">
                    <label for="lineGroup3-40-1">天王洲アイル</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="大井競馬場前" type="checkbox">
                    <label for="lineGroup3-40-1">大井競馬場前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="流通センター" type="checkbox">
                    <label for="lineGroup3-40-1">流通センター</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="昭和島" type="checkbox">
                    <label for="lineGroup3-40-1">昭和島</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="整備場" type="checkbox">
                    <label for="lineGroup3-40-1">整備場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="天空橋" type="checkbox">
                    <label for="lineGroup3-40-1">天空橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="羽田空港国際線ビル" type="checkbox">
                    <label for="lineGroup3-40-1">羽田空港国際線ビル</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="新整備場" type="checkbox">
                    <label for="lineGroup3-40-1">新整備場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="羽田空港第１ビル" type="checkbox">
                    <label for="lineGroup3-40-1">羽田空港第１ビル</label>
                    </li>
                    <li>
                    <input id="lineGroup3-40-1"  value="羽田空港第２ビル" type="checkbox">
                    <label for="lineGroup3-40-1">羽田空港第２ビル</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-41-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="りんかい線" id="lineGroup3-41-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-41-0">りんかい線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-41-1"  value="新木場" type="checkbox">
                    <label for="lineGroup3-41-1">新木場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="東雲" type="checkbox">
                    <label for="lineGroup3-41-1">東雲</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="国際展示場" type="checkbox">
                    <label for="lineGroup3-41-1">国際展示場</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="東京テレポート" type="checkbox">
                    <label for="lineGroup3-41-1">東京テレポート</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="天王洲アイル" type="checkbox">
                    <label for="lineGroup3-41-1">天王洲アイル</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="品川シーサイド" type="checkbox">
                    <label for="lineGroup3-41-1">品川シーサイド</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="大井町" type="checkbox">
                    <label for="lineGroup3-41-1">大井町</label>
                    </li>
                    <li>
                    <input id="lineGroup3-41-1"  value="大崎" type="checkbox">
                    <label for="lineGroup3-41-1">大崎</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-42-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="北総鉄道北総線" id="lineGroup3-42-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-42-0">北総鉄道北総線<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-42-1"  value="京成高砂" type="checkbox">
                    <label for="lineGroup3-42-1">京成高砂</label>
                    </li>
                    <li>
                    <input id="lineGroup3-42-1"  value="新柴又" type="checkbox">
                    <label for="lineGroup3-42-1">新柴又</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-43-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>
                    <input value="日暮里・舎人ライナー" id="lineGroup3-43-0" onclick="ClickMe2(this.id)" type="checkbox">&nbsp;
                    <label for="lineGroup3-43-0">日暮里・舎人ライナー<span>すべて選択</span></label></span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-43-1"  value="日暮里" type="checkbox">
                    <label for="lineGroup3-43-1">日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="西日暮里" type="checkbox">
                    <label for="lineGroup3-43-1">西日暮里</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="赤土小学校前" type="checkbox">
                    <label for="lineGroup3-43-1">赤土小学校前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="熊野前" type="checkbox">
                    <label for="lineGroup3-43-1">熊野前</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="足立小台" type="checkbox">
                    <label for="lineGroup3-43-1">足立小台</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="扇大橋" type="checkbox">
                    <label for="lineGroup3-43-1">扇大橋</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="高野" type="checkbox">
                    <label for="lineGroup3-43-1">高野</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="江北" type="checkbox">
                    <label for="lineGroup3-43-1">江北</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="西新井大師西" type="checkbox">
                    <label for="lineGroup3-43-1">西新井大師西</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="谷在家" type="checkbox">
                    <label for="lineGroup3-43-1">谷在家</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="舎人公園" type="checkbox">
                    <label for="lineGroup3-43-1">舎人公園</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="舎人" type="checkbox">
                    <label for="lineGroup3-43-1">舎人</label>
                    </li>
                    <li>
                    <input id="lineGroup3-43-1"  value="見沼代親水公園" type="checkbox">
                    <label for="lineGroup3-43-1">見沼代親水公園</label>
                    </li>
                    </ul>
                    </div>

                    <div id="lineGroup3-44-div" class="clrfix" style="display: none;">
                    <h4 class="ttl-h4-03">
                    <span>その他</span>
                    </h4>
                    <ul class="list-check-03">
                    <li>
                    <input id="lineGroup3-44-1"  value="" type="text">
                    </li>
                    </ul>
                    </div>
                    <!-- /東急・京王 -->

                    </div>
                    </div>
                    </div>
			</td>
	</tr>
	
	<tr>
		<th>駅徒歩</th>
		<td>
			<?php MakeCodeMstRadio('0024', 'hopeWalk', $hope->hopeWalk, null)?>
		</td>
	</tr>
	<tr>
		<th>ご予算&nbsp;(<span class="hissu">*</span>)</th>
		<td>
			<select name="hopePriceFrom" id="lstHopePriceFrom" >
				<option value="">下限なし</option>
				<?php MakeCodeMstCombo('0027', false, $hope->hopePriceFrom)?>
			</select>&nbsp;～
			<select name="hopePriceTo" id="lstHopePriceTo" >
				<?php MakeCodeMstCombo('0027', false, $hope->hopePriceTo)?>
				<option value="" <?php if(isNull($hope->hopePriceTo) || $hope->hopePriceTo == 0) echo 'selected="selected"' ?>>上限なし</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>専有面積</th>
		<td>
			<select name="hopeSquareFrom" id="lstHopeSquareFrom" >
				<option value="">下限なし</option>
				<?php MakeCodeMstCombo('0026', false, $hope->hopeSquareFrom)?>
			</select>&nbsp;～
			<select name="hopeSquareTo" id="lstHopeSquareTo" >
				<?php MakeCodeMstCombo('0026', false, $hope->hopeSquareTo)?>
				<option value="" <?php if(isNull($hope->hopeSquareTo) || $hope->hopeSquareTo == 0) echo 'selected="selected"' ?>>上限なし</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>築年数</th>
		<td>
			<?php MakeCodeMstRadio('0025', 'hopeYear', $hope->hopeYear, null)?>
		</td>
	</tr>
	
</table>
<br>
<br>
</form>

<div align="center"> 
	<a href="javascript:window.close()"><img src="images/global/demobtn_list.gif" alt="一覧へ" border="0" ></a>
	<a href="#" onclick="javascript:submit()">
		<img src="images/global/demobtn_confirm.gif" alt="確認" border="0" />
	</a> 		
</div>
<br>
</div>
<script language="javascript">
$(document).ready(function(){
	<?php if($action == 1)
	{?>
		if(confirm('登録しますか？'))
		{
			document.getElementById('action').value = '2';
			submit();
		}
		else
		{
			document.getElementById('action').value = '0';
		}
	<?php } ?>
});

function submit(){
	convertStation();
	document.forms['frm'].submit();
}

function convertStation(){
	allStation = [];

	//客路線の駅ループ
	$('.cont02 input[id^=lineGroup][id$=-0]').each(function(){
		id = $(this).attr('id');
		stationId = id.replace('-0', '-');
		stations = [];

		//路線の駅
		$(String.format('input[id^={0}]:checked', stationId)).each(function(){
			if($(this).attr('id') == id) return;
			stations.push($(this).val());
		});

		//選択駅あり	
		if(stations.length > 0){
			allStation.push(String.format('{0}-{1}', $(this).val(), stations.join(',')));
		}
		
	});

	$('#hidHopeStation').val(allStation.join('|'));				
}

</script>
</body>
</html>