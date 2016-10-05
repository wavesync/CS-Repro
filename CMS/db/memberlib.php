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
	$member->save();
}

/**
 * POSTから会員情報取得
 * @param unknown $job
 */
function bindMember($member){
	$columns = array('memberNo','memberName','memberNameKana','zipCode','address1','address2','address3','address4','address5','tel','fax','email',
					 'connectMethod','connectTime','family','age','income','ownMoney','password','registerFlg','note');
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
	return '';
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
	$columns = array('hopeArea','hopeAreaOther','hopePriceFrom','hopePriceTo','hopeSquareFrom','hopeSquareTo','hopeWalk','hopeLine','hopeStation','hopeYear');
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
}
/**
 * 会員保存
 * @param unknown $member
 */
function saveHope($hope){
	$hope->save();
}
?>