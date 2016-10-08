<?php 

/*郵便番号検索*/
function searchZip($zipCode)
{
	if($zipCode === null || $zipCode === '')
	{
		return null;
	}
	
	$zips = ORM::for_table('zip_code_tbl')->where_like('zip', '%'.$zipCode.'%')->find_many();
	return $zips;
}

?>