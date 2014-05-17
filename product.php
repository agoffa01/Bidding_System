
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
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
    <title>Search</title>
    <!-- Bootstrap -->  
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">  
    <link href="css/styles.css" rel="stylesheet">  
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

</head>

<body>
    <?php include ('views/navbar.html'); ?>

    <div class="custom-list">
    <?php
		if(isset($_GET["submit"])){
			$var =  $_GET["test"];
			$info = preparedStatement($con, "product_query", $var, "select item.item_name, item.item_id, description, category, price, current_bid_price, condition, image1, post_date, bidenddate, pickuplocation from item inner join sell on item.item_id = sell.item_id where item.item_id = $1");
			date_default_timezone_set('America/New_York');                    
            $today = strtotime(date('Y-m-d H:i:s'), time());
			
			while($iter = pg_fetch_array($info)){
				$expireDay = strtotime($iter['bidenddate']);
                $timeleft = $expireDay-$today;
	            $days = floor((($timeleft/24)/60)/60);
	            $hours = floor((($timeleft/60)/60)%24);
	            $minutes = floor(($timeleft/60)%60);
	            
	            if ($minutes > 0) {
	                $remainingTime = $days . " day(s) " . $hours . " hr(s) " . $minutes . " min(s) left";
	            }
	            else {
	                $remainingTime = " Auction Ended";
	            }

				Print "<form action = 'bid.php' method = 'POST'> ";
				Print "    <input type = 'hidden' id = 'test' name = 'test'  value = ". "\"" . $iter['item_id']."\"".">";
				Print "    <div class='row'>";
				Print "        <div class='col-xs-6 col-md-4'>";
                Print "    <a href class='thumbnail'>";

                if(strlen($iter['image1']) == 0){
                    Print "      <img src='images/noImage.jpg'>"; 
                } 
                else{
                    Print "      <img src='images/" . $iter['image1'] . "'alt='noImage'> </a>";
                }

                Print "    </a>";
				Print "        </div>";
				Print "        <div class='col-xs-6 col-md-4'>";
				Print "  	       <p> <capsize> " . $iter['item_name'] . "</capsize></p>";
		        Print "            <p> <uppercase>" . $iter['condition'] . "</uppercase></p>";
				Print "  	       <p><reem_label>Category: </reem_label> <catCap>" . $iter['category'] . "</catCap></p>";
				Print "  	       <p><reem_label>Description: </reem_label> " . $iter['description'] . "</p>";
				Print "  	       <p><reem_label>Bid Ends: </reem_label> " . $iter['bidenddate'] . "</p>";
				Print "            <p> <timeColor>" . $remainingTime . "</timeColor></p>";
				Print "  	   </div>";
				Print "        <div class='col-xs-6 col-md-4'>";
				Print "  	       <p><reem_label>Starting Bid Price: </reem_label> <oldPrice> $ " . $iter['price'] . "</oldPrice></p><p></p>";
				Print "  	       <p><reem_label>Current Bid Price: </reem_label> <priceLabel> $ " . $iter['current_bid_price'] . "</priceLabel></p><p></p>";
		   		Print "		       <p><reem_label for= 'bidentry '>Enter Bid Amount: </reem_label></p>";	
		    	Print "   	       <input class= 'form-control' name = 'bidentry' id = 'bidentry' type = 'number' style='width:120px' min =" . $iter['current_bid_price'] . " max='9999' placeholder= 'Bid Amount'/>";
		    	Print "   	       <input type = 'submit' class='btn btn-default btn-sm' name = 'submit' value ='Submit '></button>";
				Print "        </div>";
				Print "    </div>";
				Print "</form>";
			} //end of while
		}
	?>
	</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

