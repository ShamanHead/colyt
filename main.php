<?php

require_once('cgi-bin/routes.php');

use Colyt\Routes as Routes;

$route = explode('/',$_GET['route']);
$routes = Routes::get();

$finded = false;

for($i = 0; $i < count($routes);$i++){
	if($route[0] == '' && $routes[$i]['name'] == 'empty'){
		$finded = true;
		$controllers = explode(',', $routes[$i]['controllers']);
		require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/core.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/orm.php');
		for($j = 0;$j < count($controllers);$j++){
			require_once($_SERVER['DOCUMENT_ROOT'].'/controllers/'.$controllers[$j].'/index.php');
		}

		require_once($_SERVER['DOCUMENT_ROOT'].'/public/'.$routes[$i]['template']);
	}
	if($route[0] == $routes[$i]['name']){
		$finded = true;
		$controllers = explode(',', $routes[$i]['controllers']);
		require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/core.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/cgi-bin/orm.php');
		for($j = 0;$j < count($controllers);$j++){
			require_once($_SERVER['DOCUMENT_ROOT'].'/controllers/'.$controllers[$j].'/index.php');
		}
		require_once($_SERVER['DOCUMENT_ROOT'].'/public/'.$routes[$i]['template']);
	}
}

if(!$finded){
	Header('Location:http://'.$_SERVER['SERVER_NAME'].'/404');
}

?>