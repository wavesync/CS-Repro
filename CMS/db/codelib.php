<?php

/**
 * コードマスタ取得
 * @param unknown $code
 */
function getCode($code){
	return ORM::for_table('CodeMst')->where(array('code'=>$code, 'deleteFlg'=>'00'))
									->order_by_asc('displayOrder')->find_many();
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
	$codes = getCode($code);
	if($hasDefault == true)
	{
		print("<option value=\"\">未選択</option>");
	}	
	
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

/**
 * コンボボックス
 * @param unknown $code
 * @param unknown $hasDefault
 * @param unknown $val
 */
function MakeCodeMstRadio($code, $name, $val, $col)
{
	$codes = getCode($code);	
	
	$index = 0;
	foreach($codes as $row)
	{		
		
		$index++;
		$id = $name.$index;
		print('<input type="radio" name="'.$name.'" id="'.$id.'" value="'.$row->number.'" '.($val == $row->number ? "checked" : "").
			  '><label for="'.$id.'" '.(isset($col) ? ' class="inblock"' : '').'>'.$row->title.'</label>');
		if(isset($col) && $index % $col == 0){
			print('<br>');
		}
		
	}

}

/**
 * コンボボックス
 * @param unknown $code
 * @param unknown $hasDefault
 * @param unknown $val
 */
function MakeCodeMstMultiCheckbox($code, $name, $val, $col)
{

	$codes = getCode($code);
	$vals = explode(',', $val);
	$index = 0;
	$class = 'inblock';
	if($col != 6) $class = 'inblock2';
	foreach($codes as $row)
	{
		$check = in_array($row->number, $vals);		
		
		$index++;
		$id = $name.$index;
		print('<input type="checkbox" name="'.$name.'[]" id="'.$id.'" value="'.$row->number.'" '.($check ? "checked" : "").
				'><label for="'.$id.'" '.(isset($col) ? ' class="'.$class.'"' : '').'>'.$row->title.'</label>');
		if(isset($col) && $index % $col == 0){
			print('<br>');
		}

	}

}

?>