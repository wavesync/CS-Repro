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
function searchBukken($searchInfo, &$countItem){
	$query = ORM::for_table("Bukken");
	
	$where = ' WHERE 1 = 1';
	
	if(!isNull($searchInfo->memberFlg)) $where .= " AND memberFlg = '$searchInfo->memberFlg' ";
	if(!isNull($searchInfo->publishFlg)) $where .= " AND publishFlg = '$searchInfo->publishFlg' ";
	if(!isNull($searchInfo->objectCode)) $where .= " AND objectCode = '$searchInfo->objectCode' ";
	if(!isNull($searchInfo->objectName)) $where .= " AND objectName LIKE '%$searchInfo->objectName%' ";

	if(!isNull($searchInfo->address)){
		$areas = explode(',', $searchInfo->address);
		$con = array();
		foreach($areas as $area){
			$con[] = "address like '%".$area."%'";
		}
		$whereArea = ' AND ('.implode(' OR ', $con).')';
		$where .= $whereArea;
	}
	
	//専有面積
	if(!isNull($searchInfo->senyuAreaFrom) && is_numeric($searchInfo->senyuAreaFrom)){
		$where .= ' AND senyuArea >= '.$searchInfo->senyuAreaFrom;
	}
	if(!isNull($searchInfo->senyuAreaTo) && is_numeric($searchInfo->senyuAreaTo)){
		$where .= ' AND senyuArea <= '.$searchInfo->senyuAreaTo;		
	}
	
	//専有面積
	if(!isNull($searchInfo->priceFrom) && is_numeric($searchInfo->priceFrom)){
		$pr = $searchInfo->priceFrom * 10000;
		$where .= ' AND price >= '.$pr;
	}
	if(!isNull($searchInfo->priceTo) && is_numeric($searchInfo->priceTo)){
		$pt = $searchInfo->priceTo*10000;
		$where .= ' AND price <= '.$pt;		
	}
	
	if(!isNull($searchInfo->madori)){
		$madories = explode(',', $searchInfo->madori);
		$md = implode("','", $madories);
		$where .= " AND madori IN ('$md')";		
	}
	
	$count = ORM::for_table('Bukken')->raw_query('SELECT COUNT(*) as pid FROM Bukken '.$where)->find_one();
	$countItem = $count->pid;
	
	if(!isset($searchInfo->sortField) || $searchInfo->sortField == '') $searchInfo->sortField = 'objectCode';
	if(!isset($searchInfo->sortOrder) || $searchInfo->sortOrder == '') $searchInfo->sortOrder = 'ASC';
	
	$order = 'ORDER BY '.$searchInfo->sortField.' '.$searchInfo->sortOrder;
	$select = 'SELECT ROW_NUMBER() OVER('.$order.') rowNum, * FROM Bukken '.$where;

	$start = $searchInfo->pageSize*($searchInfo->pageIndex - 1) + 1;
	$end = $start + $searchInfo->pageSize;
	
	$select = 'SELECT pid, objectCode, objectName, objectCodeReins, route1Name 
					  station1Name, address, madori, syozaiKai, senyuArea, price		
			   FROM ('.$select.') t WHERE rowNum BETWEEN '.$start.' AND '.$end;

	return ORM::for_table('Bukken')->raw_query($select)->find_many();
	//検索
	
// 	if(!isNull($searchInfo->memberFlg)){
// 		$query = $query->where('memberFlg', $searchInfo->memberFlg);
// 	}
// 	if(!isNull($searchInfo->publishFlg)){
// 		$query = $query->where('publishFlg', $searchInfo->publishFlg);
// 	}
// 	if(!isNull($searchInfo->objectName)){
// 		$query = $query->where_like('objectName', '%'.$searchInfo->objectName.'%');
// 	}
	
// 	if(!isNull($searchInfo->address)){
// 		$areas = explode(',', $searchInfo->address);
// 		$con = array();
// 		foreach($areas as $area){
// 			$con[] = "address like '%".$area."%'";
// 		}
// 		$whereArea = '('.implode(' OR ', $con).')';
// 		$query = $query->where_raw($whereArea);
// 	}
	
// 	//専有面積
// 	if(!isNull($searchInfo->senyuAreaFrom) && is_numeric($searchInfo->senyuAreaFrom)){
// 		$query = $query->where_gte('senyuArea', $searchInfo->senyuAreaFrom);
// 	}
// 	if(!isNull($searchInfo->senyuAreaTo) && is_numeric($searchInfo->senyuAreaTo)){
// 		$query = $query->where_lte('senyuArea', $searchInfo->senyuAreaTo);
// 	}
	
// 	//専有面積
// 	if(!isNull($searchInfo->priceFrom) && is_numeric($searchInfo->priceFrom)){
// 		$query = $query->where_gte('price', $searchInfo->priceFrom * 10000);
// 	}
// 	if(!isNull($searchInfo->priceTo) && is_numeric($searchInfo->priceTo)){
// 		$query = $query->where_lte('price', $searchInfo->priceTo*10000);
// 	}
// 	if(!isNull($searchInfo->madori)){
// 		$query = $query->where_in('madori', explode(',', $searchInfo->madori));
// 	}
	
// 	$countQuery = clone $query;
// 	$countItem = $countQuery->count();
	
// 	$offset = $searchInfo->pageSize*($searchInfo->pageIndex - 1);	
// 	return $query->order_by_desc('updateDateTime')->order_by_asc('objectCode')->limit($searchInfo->pageSize)->find_many();
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
 * 万円単位で表示
 * @param unknown $price
 */
function displayPrice($price){
	$man = $price / 10000;
	if($man >= 10000){
		$odd = $man % 10000;
		$oku = ($man - $odd)/10000;
		return $oku.'億'.number_format($odd).'万円';
	}
	return number_format($man).'万円';
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