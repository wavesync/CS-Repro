<?php
/**
 * 物件情報取得
 * @param unknown $bukkenId
 */
function getBukkenFiles($bukkenId){
	//取得
	$files = ORM::for_table("BukkenFile")->where('bukkenId', $bukkenId)->find_many();
	return $files;
}

/**
 * 物件ファイル情報取得
 * @param unknown $bukkenId
 */
function getBukkenFile($pid){
	//取得
	if(isset($pid)){
		$file = ORM::for_table("BukkenFile")->find_one($pid);
	}
	//作成
	else {
		$file = ORM::for_table("BukkenFile")->create();
	}
	return $file;
}


/**
 * ファイル保存
 * @param unknown $file
 */
function saveBukkenFile($file)
{
	$file->save();	
}

/**
 * ファイル削除
 * @param unknown $pid
 */
function deleteBukkenFile($pid)
{
	$bukkenFile = getBukkenFile($pid);
	if(isset($bukkenFile))
	{
		//ファイルを削除
		$path = $bukkenFile->path;

		if($path !== null && $path !== '')
		{
			$name = $bukkenFile->name;
			$path = str_replace("/".$name, "", $path);
			try
			{
				delete_directory($path);

				#echo $path;

				//データベースを削除
				$bukkenFile->delete();
			}
			catch(Exception $e)
			{
				echo $e;
			}
		}

		//データベースを削除
		//DeleteBukkenFile($pid);
	}
}


#画像種別
function MakeComboImageType($hasDefault, $val)
{
	MakeCodeMstCombo("0016",$hasDefault, $val);

}

function guid()
{
	if (function_exists('com_create_guid'))
	{
		return com_create_guid();
	}
	else
	{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = chr(123)// "{"
		.substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12)
		.chr(125);// "}"
		return $uuid;
	}
}

function delete_directory($dirname)
{
	if (is_dir($dirname)) $dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
		while($file = readdir($dir_handle))
		{
			if ($file != "." && $file != "..")
			{
				if (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
					else
						delete_directory($dirname.'/'.$file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
}

?>