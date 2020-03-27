<?php

require_once('./models/productModel.php');

Use Colyt\Connection as Connection;
Use Colyt\ProductModel as ProductModel;

print_r($route);

$cnt = new Connection;
$product = new Product($route[1]);
print_r( $product->info() );



?>