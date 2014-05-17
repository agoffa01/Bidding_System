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
    <title>NYUBids - Buyer - Game Consoles</title>
    <!-- Bootstrap -->  
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">  
    <link href="css/styles.css" rel="stylesheet">  
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

</head>

<body>
    <?php include ('views/navbar.html'); ?> 
        
<div class="custom-list">
        
    <?php
        $info = preparedStatement($con, "category_query", "game consoles", "select item_name, item_id, category, current_bid_price, condition, description, image1 from item where category = $1");
            while($iter = pg_fetch_array($info)){                            
                Print "   <form action = 'product.php' method = 'GET'> ";
                Print "<div class='row'>";
                Print "  <div class='col-xs-6 col-md-4'>";
                Print "    <a href class='thumbnail'>";

                if(strlen($iter['image1']) == 0){
                    Print "      <img src='images/noImage.jpg'>"; 
                } 
                else{
                    Print "      <img src='images/" . $iter['image1'] . "'alt='noImage'>";
                }

                Print "    </a>";
                Print "  </div>";
                Print "  <div class='col-xs-6 col-md-4'>";
                Print "<input type = 'hidden' id = 'test' name = 'test'  value = ". "\"" . $iter['item_id']."\"".">";
                Print "     <p><capsize>" . $iter['item_name'] . "</capsize></p>";
                Print " <button type='submit' class='btn btn-default btn-sm' name = 'submit'> View Item </button>";
                Print "  </div>";
                Print "  <div class='col-xs-6 col-md-4'>";
                Print "     <p><priceLabel> $ " . $iter['current_bid_price'] . "</priceLabel></p>";
                Print "   <p><uppercase>" . $iter['condition'] . "</uppercase></p>";
                Print "  </div>";
                Print "</div>";
                Print "   </form>";
            }
        ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>   
</div>
</body>
</html>