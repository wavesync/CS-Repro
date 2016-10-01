<?php

/**
 * コードマスタ取得
 * @param unknown $code
 */
function getCode($code){
	return ORM::for_table('CodeMst')->where('code',$code)->order_by_asc('displayOrder')->find_many();
}

/*
 * コード1個取得
 */
function getCodeDetail($code, $number){
	return ORM::for_table('CodeMst')->where(array(
			"code"=>$code,
			"number"=>$number
	))->find_one();
}

/*
 * コード1個取得
 */
function getCodeTitle($code, $number){
	$code = ORM::for_table('CodeMst')->where(array(
			"code"=>$code,
			"number"=>$number
	))->find_one();
	
	if(isset($code)){
		return $code->title;
	}
	return '';
}

/**
 * すべてコードを取得
 */
function getAllCodes(){
	return ORM::for_table('CodeMst')->where('deleteFlg','00')->order_by_asc('code')->order_by_asc('displayOrder')->find_many();
}

/**
 * コンボボックス
 * @param unknown $code
 * @param unknown $hasDefault
 * @param unknown $val
 */
function MakeCodeMstCombo($code, $hasDefault, $val)
{
	if($hasDefault == true)
	{
		print("<option value=\"\">未選択</option>");
	}

	$codes = getCode($code);
	
	foreach($codes as $row)
	{
		if(!isset($val))
		{
			print("<option value=\"".$row->number."\">".$row->title."</option>");
		}
		else
		{
			if($val != $row->number)
			{
				print("<option value=\"".$row->number."\">".$row->title."</option>");
			}
			else
			{
				print("<option value=\"".$row->number."\" selected>".$row->title."</option>");
			}
		}
	}
	
}

?>