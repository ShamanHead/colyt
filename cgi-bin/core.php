<?php

namespace Colyt;

class Core{
	private static $config = [
		'shop_name' => 'Colyt',
		'database' => [
			'host' => '127.0.0.1',
			'name' => 'colyt',
			'username' => 'root',
			'password' => ''
		],
		'errors' => [
			'404' => 'site/errors/404.html'
		]
	];

	public static function getPDOConnection(){
		return new \PDO("mysql:host=".self::$config['database']['host'].";dbname=".self::$config['database']['name'], self::$config['database']['username'], self::$config['database']['password']);
	}

	public static function getConfig(){
		return self::$config;
	}
}

?>