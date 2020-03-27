<?php

namespace Colyt;

use Colyt\Core as Core;
use \Pdo as PDO; 

Class Connection{

	function __construct(){
		$this->DBH = Core::getPDOConnection();
		$this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}

	public function getProductList($option = 'all'){
		switch($option){
			case 'name':
				$getProductListQuery = $this->DBH->query('SELECT name FROM products');
				$productList = $getProductListQuery->fetchAll();
			break;
			case 'all':
				$getProductListQuery = $this->DBH->query('SELECT * FROM products'); 
				$productList = $getProductListQuery->fetchAll();
				for($i = 0;$i < count($productList);$i++){
					if($this->getSale($productList[$i]['id'])){
						$productList[$i]['sale'] = $this->getSale($productList[$i]['id'])['sale'];
					}
				}
			break;
			default:
				throw new Error('your $option value is incorrect ');
			break;
		}
		return new ProductList($productList);
	}

	public function addProduct($name, $image, $discription, $cost){
		$addProductQuery = $this->DBH->prepare('INSERT INTO products (name, image, description, cost) VALUES (?, ?, ?, ?)');
		$addProductQuery->bindParam(1, $name);
		$addProductQuery->bindParam(2, $image);
		$addProductQuery->bindParam(3, $discription);
		$addProductQuery->bindParam(4, $cost);
		return $addProductQuery->execute();
	}

	public function deleteProduct($id){
		$deleteProductQuery = $this->DBH->prepare('DELETE FROM products WHERE id = ?');
		$deleteProductQuery->bindParam(1, $id);
		return $deleteProductQuery->execute();
	}

	public function addSale($product_id, $sale){
		$addSale = $this->DBH->prepare('INSERT INTO sales (product_id, sale) VALUES (?,?)');
		$addSale->bindParam(1, $product_id);
		$addSale->bindParam(2, $sale);
		return $addSale->execute();
	}

	public function getSale($product_id){
		$getSale = $this->DBH->prepare('SELECT * FROM sales WHERE product_id = ?');
		$getSale->bindParam(1, $product_id);
		$getSale->execute();
		return $getSale->fetch();
	}

	public function deleteSale($id){
		$deleteSaleQuery = $this->DBH->prepare('DELETE FROM sales WHERE product_id = ?');
		$deleteSaleQuery->bindParam(1, $id);
		return $deleteSaleQuery->execute();
	}

}

class ProductList{

	public function __construct($array){
		$this->list = $array;
		if(!$this->list[0]['cost'] && !$this->list[0]) throw new \Error('your product list is empty');
	}

	public function sort($option){
		if(!$this->list[0]['cost'] && !$this->list[0]) return false;
		switch($option){
			case 'cost':
			if(!$this->list[0]['cost']) throw new\Error('name product lists not support this option');
			if( !$this->list[0]) throw new \Error('your product list is empty');
				for($i = 0; $i < count($this->list);$i++){
					for($j = 0;$j < count($this->list);$j++){
						if($this->list[$i]['cost'] > $this->list[$j]['cost']){
							$temp = $this->list[$i];
							$this->list[$i] = $this->list[$j];
							$this->list[$j] = $temp;
							unset($temp);
						}
					}
				}
				return new ProductList($this->list);
			break;
			case 'name':
			if(!$this->list[0]['name']) throw new\Error('name product lists not support this option');
				for($i = 0; $i < count($this->list);$i++){
					for($j = 0;$j < count($this->list);$j++){
						if(bin2hex($this->list[$i]['name']) < bin2hex($this->list[$j]['name'])){
							$temp = $this->list[$i];
							$this->list[$i] = $this->list[$j];
							$this->list[$j] = $temp;
							unset($temp);
						}
					}
				}
				return new ProductList($this->list);
			break;
			case 'sale':
			if(!$this->list[0]['cost']) throw new\Error('name product lists not support this option');
				for($i = 0; $i < count($this->list);$i++){
					for($j = 0;$j < count($this->list);$j++){
						if(bin2hex($this->list[$i]['sale']) > bin2hex($this->list[$j]['sale'])){
							$temp = $this->list[$i];
							$this->list[$i] = $this->list[$j];
							$this->list[$j] = $temp;
							unset($temp);
						}
					}
				}
				return new ProductList($this->list);
			break;
		}
	}

	public function __toString(){
		return (string)$this->list;
	}

}

?>