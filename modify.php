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
	
	if(isset($_POST["delete"])){
		$deleteFromBid = "delete from bid where item_id = ". $_POST["test"];
		pg_exec($con, $deleteFromBid);

		$deleteFromSell = "delete from sell where item_id = ". $_POST["test"];
		pg_exec($con, $deleteFromSell);

		$deleteFromItem = "delete from item where item_id = ". $_POST["test"];
		pg_exec($con, $deleteFromItem);
		print '<script type="text/javascript">';
		print 'alert("Ypu have successful deleted the item")';
		print '</script>';
		header("refresh: 1; seller_tab.php");
	}

	if(isset($_POST["sendemail"])){
	
	$netid = "";
	$bidamount = 0;
	$itemid =  $_POST["test"];
	$itemname = "";
	$pickuplocation = "";
	$email = "";
	$fname = "";
	$lname = "";
	$phone = 0;
	$myemail = "";


	$info = pg_prepare($con,"max_bid_query",
		"select temp.netid, temp.item_id, temp.item_name, temp.bid_amount, pickuplocation from bid temp inner join
		(select item_id, max(bid_amount) max_bid from bid group by item_id) temp2  on temp.item_id = temp2.item_id 
		and temp.bid_amount = temp2.max_bid inner join sell on sell.item_id = temp.item_id where now() > sell.bidenddate and 
		temp.item_id = $1");
    $info =  pg_execute($con, "max_bid_query", array($itemid));
    if ($row =  pg_fetch_array($info)) {
    		$netid = $row['netid'];
    		$itemname = $row['item_name'];
    		$bidamount = $row['bid_amount'];
    		$pickup = $row['pickuplocation'];


		}


		pg_free_result($info);

		$query =  pg_prepare($con, "fetch_buyer_info", "select firstname, lastname, email, phonenumber from member where netid = $1");
		$query =  pg_execute($con, "fetch_buyer_info", array($netid));
		if($result =  pg_fetch_array($query)){

			$email = $result['email'];
			$fname = $result['firstname'];
			$lname = $result['lastname'];
			$phone = $result['phonenumber'];

		}

		pg_free_result($query);


		
		$command =  pg_prepare($con, "my_info", "select email from member where netid = $1");
		$command =  pg_execute($con, "my_info", array( $userId));
		if($info =  pg_fetch_array($command)){

			$myemail = $result['email'];
			
		}

		pg_free_result($command);

	$message = " Winner: " . $netid. "\n".
				"Name: " . $fname . " " . $lname . "\n".
				"Email: " . $email. "\n".
				"Phone Number: " . $phone. "\n".
				"Item Name: " .  $itemname . "\n".
				"Bid Amount: " . $bidamount. "\n";
	$message = wordwrap($message, 70);
	$header = "From: ". $myemail;
	$subject = "Bid Winner Notification";
    mail($email,$subject,$message,$header);

	print '<script type="text/javascript">';
	print 'alert("Your email was sent successfully")';
	print '</script>';
	header("refresh: 1; seller_tab.php");


}
?>