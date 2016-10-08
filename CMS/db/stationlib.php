<?php

/*線検索*/
function searchLine($ensenMei)
{
	$lines = ORM::for_table('ensen_tbl')->where_like('ensenMei', '%'.$ensenMei.'%')->find_many();
	return $lines;	
}
/*線検索*/

/*線検索*/
function searchStation($ensenCd)
{
	if($ensenCd === null || $ensenCd === '')
	{
		return null;
	}
	
	$stations = ORM::for_table('eki_tbl')->where_like('ensenCd', '%'.$ensenCd.'%')->find_many();
	return $stations;

}
/*線検索*/

?>