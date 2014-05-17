<!DOCTYPE html>
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

	<div style="width:100%; padding:30px; margin-left: -90px; ">
		<a href="buyer.php"> 
			<img src="images/NYU_Torch.jpg" height="42" width="42">
    		<img src="images/NYUBids_Logo.png" > 
    	</a> 
	</div>

	<div class="col-xs-3" style=" width: 250px; border: 3px dashed #4B0082; padding: 5px; margin-top:31px; border-radius: 15px; margin-right:50px;">
	    <label class="custom-label"> Sign In </label> 
	 	<form action = "logincheck.php" method = "POST">
			<p><input class="form-control" type = "text" name = "netid" id = "netid" placeholder="Username" required /></p>
			<p><input class="form-control" type = "password" name = "user_password" id = "user_password" placeholder="Password" required />	</p>
			<p> <input class="btn btn-default btn-sm" type = "submit"  name = "submit" value = "Log In" > </p>
		</form>
	</div>	

	<div class="col-xs-3" style="width:250px; margin-top:0px; ">
	    <!-- <a href="buyer.php"><img src="images/NYUBids_Logo.png"></a> -->
		<?php include ('register.php'); ?>
	</div>	
	
	 <!--Team B4 <br> Abdul Goffar | Danbee Jung | Jose Colindres | Reem A Hassan -->
	<script src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>