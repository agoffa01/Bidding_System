<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
	<!-- Bootstrap -->  
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">  
	<link href="css/styles.css" rel="stylesheet">  
</head>
<body class="center-content" >
	<form action = "importinfo.php" method = "POST" style="border: 3px dashed #4B0082; padding: 15px; margin-top: 0px; -moz-border-radius: 15px; border-radius: 15px; ">
	    <label class="custom-label"> Sign Up </label> 
		<p>	<input class="form-control" type = "text" name = "firstName" id = "firstName" placeholder="First Name" required />	</p>		
		<p> <input class="form-control" type = "text" name = "lastName" id = "lastName"  placeholder="Last Name" required /> </p>	
		<p>	<input class="form-control" type = "text" name = "netId" id = "netId"  placeholder="NetID(__####)" required /> </p>
		<p>	<input class="form-control" type = "email" name = "emailAddress" id = "emailAddress"  placeholder="Email Address(@nyu.edu)" required /></p>				
		<p>	<input class="form-control" type = "password" name = "password" id = "password"  placeholder="Password" required />	</p>				
		<p>	<input class="form-control" type = "password" name = "repassword" id = "repassword" placeholder="Password Confirm"  required />	</p>				
		<p>	<input class="form-control" type = "tel" name = "phone" id = "phone"  placeholder="Phone Number" required /> </p>	
		<p>	<input class="form-control" type = "text" name = "street" id = "street"  placeholder="Address" required /> </p>	
		<p>	<input class="form-control" type = "text" name = "city" id = "city"  placeholder="City" required />	</p>
		<p>	<input class="form-control" type = "text" name = "state" id = "state"  maxlength=2  placeholder="State" required />	</p>
		<p>	<input class="form-control" type = "number" name = "zipcode" id = "zipcode" maxlength=5  placeholder="Zip Code" required />	</p> 
		<p><input class="btn btn-default btn-sm" type = "submit"  name = "submit" value = "Register" >
		<button class="btn btn-default btn-sm" type="reset" value="Reset">Reset</button>
		</p>			
	</form>
</body>
</html>