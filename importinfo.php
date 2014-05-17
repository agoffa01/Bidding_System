<?php

	include "connection.php";
	include "functions.php";

	if(isset($_POST["submit"])){
		$taken = checkUserInformation($con, $_POST["netId"], $_POST["emailAddress"]);
		
		if($taken){
			print '<script type="text/javascript">';
			print 'alert("That username or email address is already in use. Please try a new one.")';
			print '</script>';
			header("refresh: 1; register.php");
		}
		else{
			$firstname = $_POST["firstName"];
			$lastname= $_POST["lastName"];
			$netid= $_POST["netId"];
			$emailaddress= $_POST["emailAddress"];
			$password= $_POST["password"];
			$phonenumber= $_POST["phone"];
			$street =  $_POST["street"];
			$city = $_POST["city"];
			$state =  $_POST["state"];
			$zipcode =  $_POST["zipcode"];

			importIntoMember($con, $netid, $emailaddress, $password, $firstname, $lastname, $phonenumber );
			importIntoBuyer($con, $netid, $street, $city, $state, $zipcode );
			importIntoSeller($con, $netid);
			
			print '<script type="text/javascript">';
			print 'alert("Your registration was successful. You will be directed to login page")';
			print '</script>';
			header("refresh: 1; index.php");
		}
	}
?>