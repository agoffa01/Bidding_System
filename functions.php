<?php
include "connection.php";
include "classes.php";


function sellerQuery($con, $netid){
	$query = "select seller_id from seller where netid = '". $netid. "'";
	$id = 0;
	
	$result = pg_exec($con, $query);
	if ($row = pg_fetch_row($result)) {
			$id = $row[0];

		}
	return $id ;

}


function buyerQuery($con, $netid){
	$query = "select buyer_id from buyer where netid = '". $netid. "'";
	$id = 0;
	
	$result = pg_exec($con, $query);
	if ($row = pg_fetch_row($result)) {
			$id = $row[0];

		}
	return $id ;

}

//"currenacuction_query", "furniture"
function preparedStatement($con, $queryBy, $value, $query){
	$stmt = pg_prepare($con, $queryBy, $query);
	$stmt = pg_execute($con, $queryBy, array($value));
	
	return $stmt;
}
function checkLogin($con, $username, $password){
	
	$successful = false;
	$stmt = pg_prepare($con,"my_query","select netid, user_password from member where netid = $1 and user_password = $2");
	if($stmt){
		$stmt =  pg_execute($con, "my_query", array($username, $password));
		if ($row = pg_fetch_row($stmt)) {
			$successful = true;
		}
	}
	pg_free_result($stmt);
	return $successful;
}

function checkUserInformation($con, $id, $email){
	$taken = false;
	$stmt = pg_prepare($con,"info_query","select netid, email from member where netid = $1 or email = $2");
	if($stmt){
		$stmt =  pg_execute($con, "info_query", array($id, $email));
		if ($row = pg_fetch_row($stmt)) {
			$taken = true;
		}
	}
	pg_free_result($stmt);
	return $taken;
}

function importIntoMember($con, $id, $email, $password, $fname, $lname, $phone ){

	$stmt = pg_prepare($con,"member_query","insert into member values($1, $2, $3, $4, $5, $6)");
	$stmt = pg_execute($con, "member_query", array($id, $email, $password, $fname, $lname, $phone));
	pg_free_result($stmt);
}		


function importIntoBuyer($con,$netid, $street, $city, $state, $zip ){
	$buyerid = 0;
	$query = "select max(buyer_id) from buyer";
	$result = pg_exec($con, $query);
	if ($row = pg_fetch_row($result)) {
			$buyerid = $row[0];
		}

	$buyerid+=1;	

	$stmt = pg_prepare($con, "buyer_query","insert into buyer values($1, $2, $3, $4, $5, $6)");
	$stmt = pg_execute($con, "buyer_query", array($buyerid, $netid, $street, $city, $state, $zip));

	
	pg_free_result($stmt);
}		

function importIntoSeller($con, $netid){

	$sellerid = 0;
	$query = "select max(seller_id) from seller";
	$result = pg_exec($con, $query);

	if ($row = pg_fetch_row($result)) {
			$sellerid = $row[0];
		}

	$sellerid+=1;

	

	$stmt = pg_prepare($con,"seller_query","insert into seller values($1, $2)");
	$stmt = pg_execute($con, "seller_query", array($sellerid, $netid));

	
	pg_free_result($stmt);
}

function importIntoItem($con, Item &$i){

	$itemid = 0;
	$query = "select max(item_id) from item";
	$result = pg_exec($con, $query);

	if ($row = pg_fetch_row($result)) {
			$itemid = $row[0];
		}

	$itemid+=1;
	
	
	$i->item_id = $itemid;

	$stmt = pg_prepare($con,"item_query","insert into item values($1, $2, $3, $4, $5, $6, $7, $8, $9)");
	$stmt = pg_execute($con, "item_query", array($i->item_id, $i->itemName, $i->description, $i->category, $i->price, $i->condition, $i->imagedir1, $i->imagedir2, $i->bidPrice));


	pg_free_result($stmt);


}



function importTosellTable($con, $netid, Item &$i, $enddate, $location){

	
	$sellerId =  sellerQuery($con, $netid);
	$timestamp = date('Y-m-d G:i:s');
	$stmt = pg_prepare($con, "sell_query","insert into sell values($1, $2, $3, $4, $5, $6, $7)");
	$stmt = pg_execute($con, "sell_query", array($sellerId, $netid, $i->item_id, $i->itemName, $timestamp, $enddate, $location));
	pg_free_result($stmt);

}

function bid($con, $bidAmount, $itemid, $netid ){
	$info = pg_prepare($con,"getname_query","select item_name from item where item_id= $1");
	$info =  pg_execute($con, "getname_query", array($itemid));
	$itemname = '';
	if($iter = pg_fetch_array($info)){
		$itemname = $iter['item_name']; 
	};

	$buyerid = buyerQuery($con, $netid);
	$timestamp = date('Y-m-d G:i:s');

	$stmt = pg_prepare($con, "bid_query","insert into bid values($1, $2, $3, $4, $5, $6)");
	$stmt = pg_execute($con, "bid_query", array($buyerid, $netid, $itemid, $itemname, $bidAmount, $timestamp));
	pg_free_result($stmt);
	pg_free_result($info);
	

}

?>