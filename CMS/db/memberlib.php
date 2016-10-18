<?php

/**
 * 会員情報すべて取得
 */
function getAllMember(){
	$members = ORM::for_table('MemberInfo')->find_many();
	return $members;
}

/**
 * 会員情報取得もしく作成
 * @param unknown $pid
 */
function getMember($pid){
	$member = null;
	if(isset($pid)){
		$member = ORM::for_table('MemberInfo')->find_one($pid);
	}
	else{		
		$member = ORM::for_table('MemberInfo')->create();
	}
	return $member;
}

/**
 * 会員保存
 * @param unknown $member
 */
function saveMember($member){
	
	if(!isset($member->pid) || $member->pid <= 0){
		$member->registerDate = date('Y-m-d H:i:s');
		$member->memberNo = MakeMemberCode();
	}
	$member->updateDate = date('Y-m-d H:i:s');
	
	$member->save();
}

/**
 * 会員情報検索
 * @param unknown $searchInfo
 * @return IdiormResultSet
 */
function searchMember($searchInfo){
	$query = ORM::for_table('MemberInfo')
				->table_alias('mb')
				->distinct()->select('mb.pid,mb.memberNo, mb.memberName, mb.tel,mb.email')
				->join('HopeInfo', 'mb.pid = hope.memberInfoPid', 'hope');

	if(!isNull($searchInfo->memberNo)) $query = $query->where('memberNo', $searchInfo->memberNo);
	if(!isNull($searchInfo->memberName)) $query = $query->where_like('memberName', '%'.$searchInfo->memberName.'%');
	if(!isNull($searchInfo->tel)) $query = $query->where_like('tel', '%'.$searchInfo->tel.'%');
	if(!isNull($searchInfo->hopeArea)){
		$areas = explode(',', $searchInfo->hopeArea);
		$con = array();
		foreach($areas as $area){
			$con[] = "hope.hopeArea like '%".$area."%'";
		}
		$whereArea = '('.implode(' OR ', $con).')';
		$query = $query->where_raw($whereArea);
	}
	
	if(!isNull($searchInfo->hopePriceFrom)) $query = $query->where_gte('hope.hopePriceFrom', $searchInfo->hopePriceFrom);
	if(!isNull($searchInfo->hopePriceTo)) $query = $query->where_raw('(hope.hopePriceTo IS NULL OR hope.hopePriceTo = 0 OR hope.hopePriceTo >= ?)', array($searchInfo->hopePriceTo));
	
	if(!isNull($searchInfo->hopeSquareFrom)) $query = $query->where_gte('hope.hopeSquareFrom', $searchInfo->hopeSquareFrom);
	if(!isNull($searchInfo->hopeSquareTo)) $query = $query->where_raw('(hope.hopeSquareTo IS NULL OR hope.hopeSquareTo = 0 OR hope.hopeSquareTo >= ?)', array($searchInfo->hopeSquareTo));
	
	if($searchInfo->hopeYear > 0) $query = $query->where_lte('hope.hopeYear', $searchInfo->hopeYear);
	
	return $query->find_many();
}

function MakeMemberCode()
{

	$val = '';
	$query = ORM::for_table("MemberInfo")->where('deleteFlg', '00')->order_by_desc('memberNo')->select_many('memberNo')->find_one();
	if(isset($query) && $query != null){
		$val = $query->memberNo;
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

function isNull($val){
	if(!isset($val) || $val == '') return true;
	return false;
}

function isInteger($input){
	return(ctype_digit(strval($input)));
}

function showDate($date, $fm){
	if(!isset($date) || $date == null) return '';
	return date($fm, strtotime($date));
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
 * POSTから会員情報取得
 * @param unknown $job
 */
function bindMember($member){
	$columns = array('memberNo','memberName','memberNameKana','zipCode','address1','address2','address3','address4','address5','tel','fax','email',
					 'connectMethod','connectTime','family','age','income','priceFrom','priceTo','ownMoney','password','registerFlg','note');
	foreach($_POST as $key => $value){
		if($key == 'pid') continue;
		if(!in_array($key, $columns)) continue;
		//マルチチェックボックス
		if(is_array($value)){
			$member->$key = implode(',', $value);
		}
		else {
			$member->$key = $value;
		}
	}
}

function validMember($member){
	$error = array();
	if(isNull($member->memberName)) $error[] = '<li>氏名は必須です。</li>';
	if(isNull($member->zipCode)) $error[] = '<li>郵便番号は必須です。</li>';
	if(isNull($member->address1)) $error[] = '<li>都道府県は必須です。</li>';
	if(isNull($member->address2)) $error[] = '<li>市区町村は必須です。</li>';
	if(isNull($member->address3)) $error[] = '<li>町丁目は必須です。</li>';
	if(isNull($member->tel)) $error[] = '<li>電話番号は必須です。</li>';
	if(isNull($member->email)) $error[] = '<li>メールアドレスは必須です。</li>';
	if(isNull($member->password)) $error[] = '<li>パスワードは必須です。</li>';
	if(isNull($member->connectMethod)) $error[] = '<li>当社からの連絡方法は必須です。</li>';
	if(isNull($member->connectTime)) $error[] = '<li>連絡希望時間は必須です。</li>';
	if(isNull($member->priceFrom) && isNull($member->priceTo)) $error[] = '<li>ご予算は必須です。</li>';
	return implode('<br>', $error);
}

/*希望情報*/
/**
 * 会員情報取得もしく作成
 * @param unknown $pid
 */
function getHope($pid){
	$hopes = ORM::for_table('HopeInfo')->where('memberInfoPid',$pid)->find_many();

	return $hopes;
}

/**
 * 希望情報1件取得
 * @param unknown $pid
 * @return boolean|ORM
 */
function getHopeDetail($pid){
	if(isset($pid)){
		$hope = ORM::for_table('HopeInfo')->find_one($pid);
	}
	else {
		$hope = ORM::for_table('HopeInfo')->create();
	}
	return $hope;
}

/**
 * 気に入れる物件
 * @param unknown $memberPid
 */
function getCareBukken($memberPid){
	$results = ORM::for_table('CareBukken')->join('Bukken', array('CareBukken.bukkenPid', '=', 'Bukken.pid'))
				->where('CareBukken.memberInfoPid', $memberPid)
				->select_many(array('pid' => 'Bukken.pid', 'objectCode', 'objectName', 'address', 'price'))->find_many();
		
	return $results;
}

/**
 * から～までの表示
 * @param unknown $from
 * @param unknown $to
 * @param unknown $device
 * @param unknown $unit
 */
function displayFromTo($from, $to, $device, $unit){
	$hasVal = false;
	if($from > 0){
		$hasVal = true;
		$from = $from/$device;
	}
	if($to > 0){
		$hasVal = true;
		$to = $to/$device;
	}
	if($hasVal){
		return ($from > 0 ? $from.$unit : '') . '～'.($to > 0 ? $to.$unit : '');
	}
	return '';
}

#会員連絡時間帯
function MakeComboConnectTime($hasDefault, $val)
{
	MakeCodeMstCombo("0020",$hasDefault, $val);

}

#会員連絡時間帯
function MakeComboFamily($hasDefault, $val)
{
	MakeCodeMstCombo("0022",$hasDefault, $val);

}

#会員連絡時間帯
function MakeComboAge($hasDefault, $val)
{
	MakeCodeMstCombo("0021",$hasDefault, $val);

}

#会員連絡時間帯
function MakeComboIncome($hasDefault, $val)
{
	MakeCodeMstCombo("0023",$hasDefault, $val);

}


/**
 * POSTから会員情報取得
 * @param unknown $job
 */
function bindHope($hope){
	$columns = array('memberInfoPid','hopeArea','hopeAreaOther','hopePriceFrom','hopePriceTo','hopeSquareFrom','hopeSquareTo','hopeWalk','hopeLine','hopeStation','hopeYear');
	foreach($_POST as $key => $value){
		if($key == 'pid') continue;
		if(!in_array($key, $columns)) continue;
		//マルチチェックボックス
		
		if(is_array($value)){
			$hope->$key = implode(',', $value);			
		}
		else {
			$hope->$key = $value;
		}
	}
	
	if(!isset($_POST['hopeArea'])) $hope->hopeArea = null;
	if(!isset($_POST['hopeLine'])) $hope->hopeLine = null;
	if(!isset($_POST['hopeStation'])) $hope->hopeStation = null;
	
}

function validateHope($hope){
	$error = array();
	if(isNull($hope->hopeArea) && isNull($hope->hopeLine)) $error[] = '<li>希望エリアもしく希望路線は必須です。</li>';
	//if(isNull($hope->hopePriceFrom) && isNull($hope->hopePriceTo)) $error[] = '<li>予算は必須です。</li>';
	return implode('<br>', $error);
}


/**
 * 会員保存
 * @param unknown $member
 */
function saveHope($hope){
	if($hope->pid <= 0){
		$hope->insertDateTime = date('Y-m-d H:i:s');
	}
	$hope->updateDateTime = date('Y-m-d H:i:s');
	
	$hope->save();
}
?>