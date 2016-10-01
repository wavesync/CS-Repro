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
	return 	ORM::for_table("Bukken")->find_many();
}


/**
 * POSTから物件情報取得
 * @param unknown $job
 */
function bindBukken($bukken){
	$columns = array('pid','objectCode','objectCodeReins','objectName','publishFlg','memberFlg','introductionFlg','topFlg','topKind',
					 'torihiki','finishFlg','saleStopFlg','opComment','catch','sikiti','youto1','youto2','youto3','seigen','jiki','jikiMonth','nyuKyoDay',
					 'interestFlg','registTime','limitTime','souKosu','syozaiKai','roomNo','roomHoui','madori','parkingPrice','sekouCompany','kanriCompany',
			  		 'kanriKind','kanriPrice','syuzenPrice',
					 'tidaiPrice','rbArea','rbPrice','niwaArea','niwaPrice','trArea','trPrice','balArea','porArea','alcArea','sbArea',
					 'route1Name','station1Name','station1Walk','traffic1Note','route2Name','station2Name','station2Walk','route3Name','station3Name',
					 'station3Walk','bus','senyuArea','price','zipCode','address','address1','address2','address3','address4','feature','objectType',
					 'structure','structureNote','totalFloor','currentFloor','floorNote','tikuYear','parking','parkingKind','equip','genkyo','note',
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
 * 物件情報保存
 * @param unknown $job
 */
function saveBukken($bukken){
	if(!isset($bukken->pid) || $bukken->pid <= 0){
		$job->insertDateTime = date('Y-m-d H:i:s');
	}
	$bukken->save();
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