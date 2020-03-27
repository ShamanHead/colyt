<?php

namespace Colyt;

use Colyt\Core as Core;

class User{

	public static function getUser($login, $password){
		$cnt = Core::getPDOConnection();
		$findUser = $cnt->prepare('SELECT * FROM users WHERE login = ?');
		$findUser->bindParam(1, $login);
		$findUser->execute();
		$user = $findUser->fetch();
		if(!password_verify($password, $user['password'])){
			return 0;
		}else{
			return $user;
		}
	}

	public static function addUser($login, $password, $mail){
		$cnt = Core::getPDOConnection();
		$findIdenticalUser = $cnt->prepare('SELECT * FROM users WHERE login = ? OR mail = ?');
		$findIdenticalUser->bindParam(1, $login);
		$findIdenticalUser->bindParam(2, $mail);
		$findIdenticalUser->execute();
		if($findIdenticalUser->fetch()){
			return false;
		}else{
			$newUser = $cnt->prepare('INSERT INTO users (login, password, mail) VALUES (?, ?, ?)');
			$hashPassword = password_hash($password, PASSWORD_DEFAULT);
			$newUser->bindParam(1, $login);
			$newUser->bindParam(2, $hashPassword);
			$newUser->bindParam(3, $mail);
			return $newUser->execute();
		}
	}

}

?>