<!--
* Created by PhpStorm.
* User: djung
* Date: 3/23/14
-->

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

    if(isset($_POST["submit"])){
        $ItemName = strtolower($_POST['ItemName']);
        $StartingPrice = $_POST['StartingPrice'];
        $ItemCondition = $_POST['ItemCondition'];

        $Image1 = $_FILES["images"]["name"][0];
        $Image2 = $_FILES["images"]["name"][1];
        $file1TmpLoc = $_FILES["images"]["tmp_name"][0];
        $file2TmpLoc = $_FILES["images"]["tmp_name"][1];

        $pathAndName1 = dirname(__FILE__) . "/images/" . $Image1;
        $pathAndName2 = dirname(__FILE__) . "/images/" . $Image2;
        move_uploaded_file($file1TmpLoc, $pathAndName1);
        move_uploaded_file($file2TmpLoc, $pathAndName2);

        $location = $_POST['PickUp'];
        $bidend = $_POST['bidenddate'];

        $Description = $_POST['Description'];
        $itemid = 0;
        $category = $_POST["category"];

        $item = new Item($itemid, $ItemName, $Description, $category, $StartingPrice, $ItemCondition,$Image1, $Image2, $StartingPrice);
        importIntoItem($con, $item);
        importTosellTable($con, $userId, $item, $bidend, $location );
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>NYUBids - Seller</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <script type="text/javascript">
        function PreviewImage(img, num) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById(img.id).files[0]);
            oFReader.onload = function (oFREvent) {
                if (num == 1) {
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                } else if (num == 2) {
                    document.getElementById("uploadPreview2").src = oFREvent.target.result;
                }
            };
        };
    </script>
    <link href="css/styles.css" rel="stylesheet" media="screen">  
 
</head>
<body>
    <?php include ('views/navbar.html'); ?>
    
    <div id="container" >
        <!-- Tabs -->
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs" style="margin-left:120px; margin-right:120px;">
            <li class="active"><a href="#manage" data-toggle="tab">Manage</a></li>
            <li><a href="#upload" data-toggle="tab">Upload</a></li>
        </ul>

        <div id="my-tab-content" class="tab-content">
            <!-- Manage Tab -->
            <div class="tab-pane active" id="manage">
                <div class="custom-list">
                    <?php
                        $info = pg_prepare($con,"manage_query","select item.item_id , item.item_name, category, current_bid_price, condition, description, image1, bidenddate from sell inner join item on sell.item_id = item.item_id and netid = $1 order by bidenddate");
                        $info =  pg_execute($con, "manage_query", array($userId));
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

                            Print "<form action = 'modify.php' method = 'POST'> ";
                            Print "    <input type = 'hidden' id = 'test' name = test  value = ". "\"" . $iter['item_id']."\"".">";
                            Print "    <div class='row'>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <a href class='thumbnail'>";

                            if(strlen($iter['image1']) == 0){
                                Print "      <img src='images/noImage.jpg'>"; 
                            } 
                            else{
                                Print "      <img src='images/" . $iter['image1'] . "'>";
                            }

                            Print "            </a>";
                            Print "        </div>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <p><capsize>" . $iter['item_name'] . "</capsize></p>";
                            Print "            <p><uppercase>" . $iter['condition'] . "</uppercase></p>";
                            Print "            <button type='submit' class='btn btn-default btn-sm' name = 'delete'> Delete </button>";
                            Print "            <button type='submit' class='btn btn-default btn-sm' name = 'sendemail'> Send Email </button>";
                            Print "        </div>";
                            Print "        <div class='col-xs-6 col-md-4'>";
                            Print "            <p><priceLabel> $ " . $iter['current_bid_price'] . "</priceLabel></p>";
                            Print "            <p><timeColor> " . $remainingTime . "</timeColor></p>";
                            Print "        </div>";
                            Print "    </div>";
                            Print "</form>";
                        }
                    ?>
                </div>
            </div>

            <!-- Upload Tab -->
            <div class="tab-pane" id="upload">
                <div class="custom-list">
                    <form method="POST" action="seller_tab.php" class="form-horizontal" enctype="multipart/form-data">
                        
                        <div class="control-group">
                            <label class="control-label" for="input1"> Item name </label>
                            <div class="controls">
                                <input type="text" class="form-control" name="ItemName" id="ItemName" maxlength="60" size="65" width="65" placeholder="Type the name of the item up to 50 characters for listing." required/>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="input2"> Starting price </label>
                            <div class="controls" style="width:120px">
                                <div class="input-group control">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" name="StartingPrice" id="StartingPrice" maxlength="4" min="0" max="9999" required/>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="bidenddate"> Bid End Date </label>
                            <div class="controls">
                                <input type="date" class="form-control" name="bidenddate" width="20px" required>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="input3"> Item condition </label>
                            <div class="controls"> 
                                <input type="radio" name="ItemCondition" value="Used" required/> Used
                                <input type="radio" name="ItemCondition" value="Brand new" required/> Brand new
                            </div>
                        </div>
                        
                         <div class="control-group">
                            <label class="control-label" for="category"> Item Category </label>
                            <div class="controls">
                                <select name="category" required>
                                <option value="" disabled selected>Select a Category</option>
                                <option value="computers">Computers</option>
                                <option value="cellphones">Cellphones</option>
                                <option value="game consoles">Game Consoles</option>
                                <option value="clothing and shoes">Clothing & Shoes</option>
                                <option value="furniture">Furniture</option>
                                <option value="books">Books</option>
                                <option value="miscellaneous">Miscellaneous</option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="input4"> Images </label>
                            <div class="controls">
                                <input id= "Image1" type="file" name="images[]" onchange="PreviewImage(this, 1);" required/>
                                <img id="uploadPreview" />
                                <p></p>
                                <input id="Image2" type="file" name="images[]" onchange="PreviewImage(this, 2);" />
                                <img id="uploadPreview2" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="input5"> Description </label>
                            <div class="controls">
                                <textarea name="Description" class="form-control" id="Description" name="Description" rows="8" style="width:80%" class="span5" placeholder="Type what you want to describe about your item." required></textarea>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="input6"> Pick-up </label>
                            <div class="controls">
                                <select name="PickUp" id="PickUp" required>
                                <option value="" disabled selected>Select</option>
                                <option value="" disabled> - Brooklyn - </option>
                                <option value="Brooklyn-Magnet">Brooklyn - Magnet</option>
                                <option value="Brooklyn-Berndibner">Brooklyn - Bern Dibner</option>
                                <option value="Brooklyn-Rogershall">Brooklyn - Rogers Hall</option>
                                <option value="Brooklyn-Othmerhall">Brooklyn - Othmer Hall</option>
                                <option value="Brooklyn-Stgeorgeclark">Brooklyn - St. George Clark</option>
                                <option value="" disabled> - Manhattan - </option>
                                <option value="Manhattan-Stern">Manhattan - Stern</option>
                                <option value="Manhattan-Tisch">Manhattan - Tisch</option>
                                <option value="Manhattan-Silver">Manhattan - Silver</option>
                                <option value="Manhattan-Courant">Manhattan - Courant</option>
                                <option value="Manhattan-Bobst">Manhattan - Bobst</option>
                                <option value="Manhattan-Kimmel">Manhattan - Kimmel</option>
                                <option value="Manhattan-Coles">Manhattan - Coles</option>
                                <option value="Manhattan-Vanderbilt">Manhattan - Vanderbilt</option>
                                <option value="" disabled> - Dormitories - </option>                            
                                <option value="Manhattan-Alumni">Manhattan - Alumni</option>
                                <option value="Manhattan-Brittany">Manhattan - Brittany</option>
                                <option value="Manhattan-Broome">Manhattan - Broom</option>
                                <option value="Manhattan-Carlyle">Manhattan - Carlyle</option>
                                <option value="Manhattan-Coral">Manhattan - Coral</option>
                                <option value="Manhattan-Founders">Manhattan - Founders</option>
                                <option value="Manhattan-Goddard">Manhattan - Goddard</option>
                                <option value="Manhattan-Gramercy">Manhattan - Gramercy</option>
                                <option value="Manhattan-Greenhouse">Manhattan - Greenhouse</option>
                                <option value="Manhattan-Greenwich">Manhattan - Greenwich</option>
                                <option value="Manhattan-Hayden">Manhattan - Hayden</option>
                                <option value="Manhattan-Lafayette">Manhattan - Lafayette</option>
                                <option value="Manhattan-Palladium">Manhattan - Palladium</option>
                                <option value="Manhattan-Rubin">Manhattan - Rubin</option>
                                <option value="Manhattan-Secondavenue">Manhattan - Second Avenue</option>
                                <option value="Manhattan-Seniorhouse">Manhattan - Senior House</option>
                                <option value="Manhattan-Thirdavenue">Manhattan - Third Avenue</option>
                                <option value="Manhattan-Weinstein">Manhattan - Weinstein</option>
                                <option value="Manhattan-Stuytown">Manhattan - Stuytown</option>
                                <option value="Elsewhere"> - Elsewhere - </option>
                                </select>        

                            </div>
                        </div>ss
                        <br/>
                        <div class="form-actions" style="background-color:transparent">
                            <button type="submit" class='btn btn-default btn-sm' name = "submit"> Submit </button>
                            <button type="reset" class='btn btn-default btn-sm'> Clear </button>
                        </div> <!--form actions -->
                    </form>
                </div>
            </div> <!--tab pane-->
        </div> <!--tab contect-->
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#tabs').tab();
        });
    </script>
    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>