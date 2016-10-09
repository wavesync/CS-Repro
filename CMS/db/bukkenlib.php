<?php
/**
 * 物件情報取得
 * @param unknown $bukkenId
 */
function getBukken($bukkenId){
	//取得
	if(isset($bukkenId)){
		$bukken = ORM::for_table("Bukken")->find_one($bukkenId);
	}
	//作成
	else {
		$bukken = ORM::for_table("Bukken")->create();
	}
	return $bukken;
}

/**
 * 物件検索
 * @param unknown $searchInfo
 * @param unknown $flg
 */
function searchBukken($searchInfo, $flg){
	$query = ORM::for_table("Bukken");
	if(!isNull($searchInfo->memberFlg)){
		$query = $query->where('memberFlg', $searchInfo->memberFlg);
	}
	if(!isNull($searchInfo->publishFlg)){
		$query = $query->where('publishFlg', $searchInfo->publishFlg);
	}
	if(!isNull($searchInfo->objectName)){
		$query = $query->where_like('objectName', '%'.$searchInfo->objectName.'%');
	}
	return $query->order_by_desc('updateDateTime')->order_by_asc('objectCode')->find_many();
}


/**
 * POSTから物件情報取得
 * @param unknown $job
 */
function bindBukken($bukken){
	$columns = array('pid','objectCode','objectCodeReins','objectName','publishFlg','memberFlg','introductionFlg','topFlg','topKind',
					 'torihiki','finishFlg','saleStopFlg','opComment','catch','sikiti','youto1','youto2','youto3','seigen','jiki','jikiMonth','nyuKyoDay',
					 'interestFlg','registTime','limitTime','souKosu','room1Kai','roomNo','roomHoui','madori','parkingPrice','sekouCompany','kanriCompany',
			  		 'kanriKind','kanriPrice','syuzenPrice',
					 'tidaiPrice','rbArea','rbPrice','niwaArea','niwaPrice','trArea','trPrice','balArea','porArea','alcArea','sbArea',
					 'route1Name','station1Name','station1Walk','traffic1Note','route2Name','station2Name','station2Walk','route3Name','station3Name',
					 'station3Walk','bus','senyuArea','price','zipCode','address','address1','address2','address3','address4','feature','objectType',
					 'structure','chijouKai','chikaKai','syozaiKai','structureNote','tikuYear','parking','parkingKind','equip','genkyo','note',
					'gmapShowMap','gmapShowView','gmapAutoFlg','gmapLat','gmapLong','gmapMapUrl','gmapStreetUrl'
	);
	foreach($_POST as $key => $value){
		if($key == 'pid') continue;
		if(!in_array($key, $columns)) continue;
		//マルチチェックボックス
		if(is_array($value)){
			$bukken->$key = implode(',', $value);
		}
		else {
			$bukken->$key = $value;
		}
	}
}

/**
 * 物件バリデーション
 * @param unknown $bukken
 */
function validateBukken($bukken){
	$error = array();
	if(isNull($bukken->objectName)) $error[] = '<li>物件名は必須です。</li>';
	if(isNull($bukken->torihiki)) $error[] = '<li>取引態様は必須です。</li>';
	if(isNull($bukken->price)) $error[] = '<li>物件価格は必須です。</li>';
	else {
		if(isInteger($bukken->price) == false) $error[] = '<li>物件価格は不正です。</li>';
	}
	if(isNull($bukken->zipCode)) $error[] = '<li>郵便番号は必須です。</li>';
	if(isNull($bukken->address)) $error[] = '<li>住所は必須です。</li>';
	if(isNull($bukken->route1Name)) $error[] = '<li>路線1は必須です。</li>';
	if(isNull($bukken->station1Name)) $error[] = '<li>駅1は必須です。</li>';
	if(!isNull($bukken->station1Walk) && isInteger($bukken->station1Walk) == false) $error[] = '<li>駅徒歩1は不正です。</li>';
	if(!isNull($bukken->station2Walk) && isInteger($bukken->station2Walk) == false) $error[] = '<li>駅徒歩2は不正です。</li>';
	if(!isNull($bukken->station3Walk) && isInteger($bukken->station3Walk) == false) $error[] = '<li>駅徒歩3は不正です。</li>';
	
	if(!isNull($bukken->souKosu) && isInteger($bukken->souKosu) == false) $error[] = '<li>総戸数は不正です。</li>';
	if(!isNull($bukken->room1Kai) && isInteger($bukken->room1Kai) == false) $error[] = '<li>所在階は不正です。</li>';
	if(!isNull($bukken->chijouKai) && isInteger($bukken->chijouKai) == false) $error[] = '<li>地上階層は不正です。</li>';
	if(!isNull($bukken->chikaKai) && isInteger($bukken->chikaKai) == false) $error[] = '<li>地下階層は不正です。</li>';
	if(!isNull($bukken->syozaiKai) && isInteger($bukken->syozaiKai) == false) $error[] = '<li>所在階は不正です。</li>';
	if(!isNull($bukken->parkingPrice) && isInteger($bukken->parkingPrice) == false) $error[] = '<li>駐車場費は不正です。</li>';
	if(!isNull($bukken->kanriPrice) && isInteger($bukken->kanriPrice) == false) $error[] = '<li>管理費は不正です。</li>';
	if(!isNull($bukken->syuzenPrice) && isInteger($bukken->syuzenPrice) == false) $error[] = '<li>修繕積立金は不正です。</li>';
	
	if(!isNull($bukken->senyuArea) && is_numeric($bukken->senyuArea) == false) $error[] = '<li>専有面積は不正です。</li>';
	if(!isNull($bukken->niwaArea) && is_numeric($bukken->niwaArea) == false) $error[] = '<li>専用庭は不正です。</li>';
	if(!isNull($bukken->balArea) && is_numeric($bukken->balArea) == false) $error[] = '<li>バルコニー（テラス）面積は不正です。</li>';
	
	return implode('<br>', $error);
}

function isNull($val){
	if(!isset($val) || $val == '') return true;
	return false;
}

function isInteger($input){
	return(ctype_digit(strval($input)));
}

/**
 * 物件情報保存
 * @param unknown $job
 */
function saveBukken($bukken){
	if(!isset($bukken->pid) || $bukken->pid <= 0){
		$bukken->insertDateTime = date('Y-m-d H:i:s');
		$bukken->objectCode = MakeBukkenCode();
	}
	$bukken->updateDateTime = date('Y-m-d H:i:s');
	
	if(isNull($bukken->station1Walk)) $bukken->station1Walk = null;
	if(isNull($bukken->station2Walk)) $bukken->station2Walk = null;
	if(isNull($bukken->station3Walk)) $bukken->station3Walk = null;
	
	if(isNull($bukken->souKosu)) $bukken->souKosu = null;
	if(isNull($bukken->room1Kai)) $bukken->room1Kai = null;
	if(isNull($bukken->chijouKai)) $bukken->chijouKai = null;
	if(isNull($bukken->chikaKai)) $bukken->chikaKai = null;	
	if(isNull($bukken->syozaiKai)) $bukken->syozaiKai = null;
	if(isNull($bukken->parkingPrice)) $bukken->parkingPrice = null;
	if(isNull($bukken->kanriPrice)) $bukken->kanriPrice = null;
	if(isNull($bukken->syuzenPrice)) $bukken->syuzenPrice = null;
	
	if(isNull($bukken->senyuArea)) $bukken->senyuArea = null;
	if(isNull($bukken->niwaArea)) $bukken->niwaArea = null;
	if(isNull($bukken->balArea)) $bukken->balArea = null;
	$bukken->save();
	return $bukken->pid;
}


function MakeBukkenCode()
{

	$val = '';
	$query = ORM::for_table("Bukken")->where('deleteFlg', '00')->order_by_desc('objectCode')->select_many('objectCode')->find_one();
	if(isset($query) && $query != null){
		$val = $query->objectCode;
	}
	if($val === null || $val === '')
	{
		$val = '00001';
	}
	else
	{
		$intVal = (int)$val;
		$intVal += 1;
		$strVal = (string)$intVal;		
		$val = str_pad($strVal, 5, '0', STR_PAD_LEFT);
	}

	return $val;
}

#敷地権利
function MakeComboSikiti($hasDefault, $val)
{
	MakeCodeMstCombo("0003", $hasDefault, $val);
}

#敷地権利
function MakeComboYouto($hasDefault, $val)
{
	MakeCodeMstCombo("0004", $hasDefault, $val);
}

#取引
function MakeComboTorihiki($hasDefault, $val)
{
	MakeCodeMstCombo("0005", $hasDefault, $val);
}

#引渡時期
function MakeComboJiki($hasDefault, $val)
{
	MakeCodeMstCombo("0017", $hasDefault, $val);
}

#現況
function MakeComboGenKyo($hasDefault, $val)
{
	MakeCodeMstCombo("0008",$hasDefault, $val);
}

#地目
function MakeComboTimoku($hasDefault, $val)
{
	MakeCodeMstCombo("0009",$hasDefault, $val);
}

#方位
function MakeComboHoui($hasDefault, $val)
{
	MakeCodeMstCombo("0011",$hasDefault, $val);
}

#方位
function MakeComboStructure($hasDefault, $val)
{
	MakeCodeMstCombo("0010",$hasDefault, $val);
}

#間取り
function MakeComboMadori($hasDefault, $val)
{
	MakeCodeMstCombo("0015",$hasDefault, $val);
}

#駐車場
function MakeComboParking($hasDefault, $val)
{
	MakeCodeMstCombo("0016",$hasDefault, $val);
}

#駐車所
function MakeComboParkingKind($hasDefault, $val)
{
	MakeCodeMstCombo("0012",$hasDefault, $val);

}

#管理形態
function MakeComboKanriKind($hasDefault, $val)
{
	MakeCodeMstCombo("0013",$hasDefault, $val);

}

?>