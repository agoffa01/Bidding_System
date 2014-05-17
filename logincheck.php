<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
		<title>NYUBids</title>
		<!-- Bootstrap -->  
	    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	    <link href="css/styles.css" rel="stylesheet">  
	</head>

	<body>	
		<div class="col-xs-6" style="padding:0px; height: 100%;">
			<img src="images/bg.png" style="width:100%"> 
		</div>

		<div style="width:100%; padding:30px; ">
			<a href="buyer.php"> 
				<img src="images/NYU_Torch.jpg" height="42" width="42">
	    		<img src="images/NYUBids_Logo.png" > 
	    	</a> 
		</div>

		<div class="col-xs-3" style="padding:30px; text-align:center; font-size:300%; color:purple; ">
			<?php
	    		Print " <p><reem_label> Loading </reem_label></p>";
			?>
		</div>

		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/scripts.js"></script>

	</body>
</html>

<?php
	include "connection.php";
	include "functions.php";
	session_start();

	if(isset($_SESSION["netid"])) {
		header('Location: buyer.php');
	}
	else{
		if(isset($_POST["submit"])){		
			$success = checkLogin($con, $_POST["netid"], $_POST["user_password"]);
			if($success){
				$_SESSION["netid"] = $_POST["netid"];
				$_SESSION["user_password"] = $_POST["user_password"];
				header("refresh: 3; buyer.php");
			}
			else{
				 header('Location: index.php');
			}
		}
	}
?>