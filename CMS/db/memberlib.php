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
	$columns = array('memberNo','companyName','memberName','memberNameKana','zipCode','address','tel','fax','email',
					 'memberType','memberKind','password','registerDate','updateDate','registerFlg','postName','positionName','mobileTel');
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

?>