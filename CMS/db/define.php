<?php
#ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
#本番
ORM::configure('sqlsrv:server=home-agent.sppd.ne.jp,1433;Database=home-sppd00001;');
ORM::configure('username', 'home-sppd00001');
ORM::configure('password', 'N8Y8NbbL');
#本番
#ローカル
#ORM::configure('sqlsrv:server=DESKTOP-3J0UGQ6\LOCALSQLSERVER;Database=cs-mansion;');
#ORM::configure('username', 'sa');
#ORM::configure('password', 'root');
#ローカル
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