<?php


/**
 * ログイン
 * @param unknown $userId
 * @param unknown $pwd
 */
function login($userId, $pwd){

	#$lst = ORM::for_table('UserMst')->find_one();
	#var_dump($lst);
	$staff = null;
	try {
		$staff = ORM::for_table('UserMst')->where(array(
				'userId'=>$userId,
				'password'=>$pwd
		))->find_one();		
	} catch (Exception $e) {
		echo '捕捉した例外: ',  $e->getMessage(), "\n";
	}
	if(isset($staff) && $staff != null){
		$_SESSION['USER'] = serialize($staff);
		return true;
	}

	return false;
}

function getUser($userId){
	if(isset($userId) && $userId != ''){
		$user = ORM::for_table('UserMst')->find_one($userId);
	}
	else{
		$user = ORM::for_table('UserMst')->create();
	}
	return $user;
}

?>