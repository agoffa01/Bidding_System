<?php
	include "connection.php";
	include "functions.php";
	session_start();

	if(isset($_SESSION["netid"])){
	    $userId = $_SESSION["netid"];
	}

	else {
	    header("Location: index.php");
	}

	//User cannot bid on an item more than once
	if(isset($_POST["submit"])){
		$biendded = false;
		date_default_timezone_set('America/New_York');
		$now = date('Y-m-d G:i:s', time());
		$checkQuery = "select bidenddate from item inner join sell on item.item_id = sell.item_id where bidenddate <= '". $now . "' and item.item_id = " . $_POST["test"];
		$checkResult = pg_exec($con, $checkQuery);
		
		if ($row = pg_fetch_row($checkResult)) {
				$biendded = true;
		}

		if($biendded){
			print '<script type="text/javascript">';
			print 'alert("Bidding for this item has ended")';
			print '</script>';
			header("refresh: 1; buyer.php");
		}
		else{
			$currentbid = 0;
			$query = "select current_bid_price from item where item_id = ". $_POST["test"];
			$result = pg_exec($con, $query);

			if ($row = pg_fetch_row($result)) {
				$currentbid = $row[0];
			}

			if($_POST["bidentry"] > $currentbid ){
				bid($con, $_POST["bidentry"], $_POST["test"],  $userId );
				$stmt = pg_prepare($con,"update_query","update item set current_bid_price = $1 where item_id = $2");
				$stmt =  pg_execute($con, "update_query", array($_POST["bidentry"], $_POST["test"]));
				print '<script type="text/javascript">';
				print 'alert("Your bid is currently the highest")';
				print '</script>';
				header("refresh: 1; buyer.php");
			}
			else{
				print '<script type="text/javascript">';
				print 'alert("Your bid is not high enough")';
				print '</script>';
				header("refresh: 1; buyer.php");
				}
		}
	}//end off isset
?>