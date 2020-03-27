<?php

namespace Colyt;

class Routes{

	public static function get(){

		$routesFile = trim(str_replace(' ', '',file_get_contents($_SERVER['DOCUMENT_ROOT']."/routes.cf", 'r')));

		$routesList = [];

		for($i = 0, $buffer, $state = 0, $routeBuffer = []; $i < iconv_strlen($routesFile);$i++){
			if(strcasecmp($buffer,'name:') == 0 && $state != 5){
				$state = 1;
				$buffer = '';
			}else if(strcasecmp($buffer,'template:') == 0 && $state != 5){
				$state = 2;
				$buffer = '';
			}else if(strcasecmp($buffer,'controllers:') == 0 && $state != 5){
				$state = 3;
				$buffer = '';
			}else if($routesFile[$i] == '{' && $state != 5){
				$state = 5;
			}else if($routesFile[$i] == '}' && $state == 5){
				$state = 0;
				$buffer = '';
				$i++;
			}else if($routesFile[$i] == ';' && $state != 5){
				switch($state){
					case 1:
						$routeBuffer['name'] = $buffer;
					break;
					case 2:
						$routeBuffer['template'] = $buffer;
					break;
					case 3:
						$routeBuffer['controllers'] = $buffer;
					break;
					default:
						throw new \Error('something went wrong in '.$buffer);
					break;
				}
				$buffer = '';
				$state = 0;
				$i++;
				if(count($routeBuffer) == 3){
					array_push($routesList, $routeBuffer);
					$routeBuffer = [];
				}
			}
			$buffer = trim($buffer.$routesFile[$i]);
			if($i == iconv_strlen($routesFile) - 1 && count($routeBuffer) == 0){
				throw new \Error('semicolon not finded in '.$buffer);
			}
		}
		return $routesList;
	}

}

?>