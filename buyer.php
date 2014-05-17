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
    <title>NYUBids - Buyer</title>
    <!-- Bootstrap -->          
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <link href="css/styles.css" rel="stylesheet"> 
    <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="/Bidding-System/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include ('views/navbar.html'); ?>

    <div id="container">
        <!-- Tabs -->
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs" style="margin-left:120px; margin-right:120px;">
            <li class="active"><a href="#auctions" data-toggle="tab">Your Auctions</a></li>  <!--baloon: participating blah-->
            <li><a href="#newsfeed" data-toggle="tab">Todays Feed</a></li> <!--baloon: in 24hrs-->
        </ul>

        <div id="my-tab-content" class="tab-content">
            <!-- Current Auctions Tab -->
            <div class="tab-pane active" id="auctions">
                <div class="custom-list">
                    <?php
                      
                        $info = pg_prepare($con,"userauction_query","select distinct item.item_id , item.item_name, category, current_bid_price, condition, description, image1, bidenddate from bid inner join item on bid.item_id = item.item_id inner join sell on sell.item_id = item.item_id and bid.netid = $1 order by bidenddate");
                        $info = pg_execute($con, "userauction_query", array($userId));
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
                            
                            Print "<form action = 'product.php' method = 'GET'> ";
                            Print "    <div class='row'>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "        <a href class='thumbnail'>";

                            if(strlen($iter['image1']) == 0){
                                Print "      <img src='images/noImage.jpg'>"; 
                            } 
                            else{
                                Print "      <img src='images/" . $iter['image1'] . "'alt='noImage'>";
                            }

                            Print "        </a>";
                            Print "        </div>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <p><capsize>" . $iter['item_name'] . "</capsize></p>";
                            Print "            <p><uppercase>" . $iter['condition'] . "</uppercase></p>";
                            Print "            <button type='submit' class='btn btn-default btn-sm' name = 'submit'> View Item </button></p>";
                            Print "            <input type = 'hidden' id = 'test' name = 'test'  value = ". "\"" . $iter['item_id']."\"".">";
                            Print "        </div>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <p><priceLabel> $ " . $iter['current_bid_price'] . "</priceLabel></p>";
                            Print "            <p><timeColor>" . $remainingTime . "</timeColor></p>";
                            Print "        </div>";
                            Print "    </div>";
                            Print "</form>";
                        }
                    ?>
                </div>
            </div>

            <!-- NewsFeed Tab -->
            <div class="tab-pane" id="newsfeed">
                <div class="custom-list">
                    <?php

                        date_default_timezone_set('America/New_York');
                        $currentdate = date('Y-m-d', time());
                        $info = "select item.item_id, item.item_name, category, current_bid_price, condition, description, image1, bidenddate from item inner join sell on item.item_id = sell.item_id where (item.category = 'furniture' or item.category = 'computers' or item.category = 'cellphones' or item.category = 'game consoles' or item.category = 'miscellaneous' or item.category ='books' or item.category ='clothing and shoes') and sell.post_date = '" . $currentdate . "' order by bidenddate" ;
                        $result= pg_exec($con, $info);
                        $today = strtotime(date('Y-m-d H:i:s', time()));

                        while($iter = pg_fetch_assoc($result)){
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
                            
                            Print "<form action = 'product.php' method = 'GET'> ";
                            Print "    <div class='row'>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "        <a href class='thumbnail'>";

                            if(strlen($iter['image1']) == 0){
                                Print "      <img src='images/noImage.jpg'>"; 
                            } 
                            else{
                                Print "      <img src='images/" . $iter['image1'] . "'alt='noImage'>";
                            }

                            Print "        </a>";
                            Print "        </div>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <p><capsize>" . $iter['item_name'] . "</capsize></p>";
                            Print "            <p><timeColor>" . $remainingTime . "</timeColor></p>";
                            Print "            <button type='submit' class='btn btn-default btn-sm' name = 'submit'> View Item </button></p>";
                            Print "            <input type = 'hidden' id = 'test' name = 'test'  value = ". "\"" . $iter['item_id']."\"".">";
                            Print "        </div>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <p><priceLabel> $ " . $iter['current_bid_price'] . "</priceLabel></p>";
                            Print "            <p><uppercase>" . $iter['condition'] . "</uppercase></p>";
                            Print "        </div>";
                            Print "    </div>";
                            Print "</form>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

       
    <script src="/Bidding-System/js/jquery.min.js"></script>
    <script src="/Bidding-System/js/bootstrap.min.js"></script> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</body>
</html>