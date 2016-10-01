<?php
#ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
ORM::configure('sqlsrv:server=DESKTOP-3J0UGQ6\LOCALSQLSERVER;Database=cs-mansion;');
ORM::configure('username', 'sa');
ORM::configure('password', 'root');
ORM::configure('id_column_overrides', array(
		'UserMst'=>'userId',
		'Bukken'=>'pid',
		'ReinsBukken'=>'objectCode',
		'BukkenFile'=>'pid',
		'MemberInfo'=>'pid',
		'HopeInfo'=>'pid',
		'CareBukken'=>'pid'
) );
?>