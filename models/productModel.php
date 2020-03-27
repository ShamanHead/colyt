<?php

Use Colyt\Core as Core;
Use Colyt\Connection as Connection;

Class Product{
	function __construct($product_id){
		$bdb = Core::getConfig()['database'];
		$this->DBH = new PDO("mysql:host=".$bdb['host'].";dbname=".$bdb['name'], $bdb['username'], $bdb['password']);
		$this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$getProduct = $this->DBH->prepare('SELECT * FROM products WHERE id =?');
		$getProduct->bindParam(1, $product_id);
		$getProduct->execute();

		$this->productInfo = $getProduct->fetch();

		$cnt = new Connection();

		if($cnt->getSale($this->productInfo['id'])) $this->productInfo['sale'] = $cnt->getSale($this->productInfo['id'])['sale'];

		if(!$this->productInfo) throw new \Exception("Product not found");
	}

	public function setCopy($copy){
		$setCopy = $this->DBH->prepare('INSERT INTO product_copies (product_id, content) VALUES (?, ?)');
		$setCopy->bindParam(1, $this->productInfo['id']);
		$setCopy->bindParam(2, $copy);
		return $setCopy->execute();
	}

	public function getCopy(){
		$getCopy = $this->DBH->prepare('SELECT * FROM product_copies WHERE product_id = ?');
		$getCopy->bindParam(1, $this->productInfo['id']);
		$getCopy->execute();
		$copy = $getCopy->fetch();
		$deleteCopy = $this->DBH->prepare('DELETE FROM product_copies WHERE id = ?');
		$deleteCopy->bindParam(1, $copy['id']);
		$deleteCopy->execute();
		return $copy;
	}

	public function setName($name){
		$setName = $this->DBH->prepare('UPDATE products SET name = ? WHERE id = ?');
		$setName->bindParam(1, $name);
		$setName->bindParam(2, $this->productInfo['id']);
		return $setName->execute();
	}

	public function setDescription($description){
		$setDescriprion = $this->DBH->prepare('UPDATE products SET description = ? WHERE id = ?');
		$setDescriprion->bindParam(1, $description);
		$setDescriprion->bindParam(2, $this->productInfo['id']);
		return $setDescriprion->execute();
	}

	public function setImage($href){
		$setImage = $this->DBH->prepare('UPDATE products SET image = ? WHERE id = ?');
		$setImage->bindParam(1, $href);
		$setImage->bindParam(2, $this->productInfo['id']);
		return $setImage->execute();
	}

	public function setCost($cost){
		$setCost = $this->DBH->prepare('UPDATE products SET cost = ? WHERE id = ?');
		$setCost->bindParam(1, $cost);
		$setCost->bindParam(2, $this->productInfo['id']);
		return $setCost->execute();
	}

	public function setSale($sale){
		$cnt = new Connection();
		$cnt->addSale($this->productInfo['id'], $sale);
		return true;
	}

	public function deleteSale(){
		$cnt = new Connection();
		$cnt->deleteSale($this->productInfo['id']);
		return true;
	}

	public function info(){
		return $this->productInfo;
	}
}

?>