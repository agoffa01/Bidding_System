<?php

class Item{
	private $item_id;
	private $itemName;
	private $description;
	private $category;
	private $price;
	private $condition;
	private $imagedir1;
	private $imagedir2;
	private $bidPrice;

	public function __construct($id, $name, $descrip, $cat, $priceTag, $cond, $dir1, $dir2, $bidprice){
		$this->item_id = $id;
		$this->itemName = $name;
		$this->description = $descrip;
		$this->category = $cat;
		$this->price = $priceTag;
		$this->condition = $cond;
		$this->imagedir1 = $dir1;
		$this->imagedir2 = $dir2;
		$this->bidPrice = $bidprice;

	}

	public function __get($variable) {
		return $this->$variable;
	}	
    
  	public function __set($variable, $value) {
    $this->$variable = $value;
	}
}



?>