<?php
    session_start();
    if (@$_SESSION['auth'] != "yes") {
        header("Location: LoginPage.php");
        exit();
    }
    if ($_POST['submit']) {
        $_SESSION['itemNumber'] = $_POST['submit'];
    }
    if ($_POST['PlaceBid']) {
            // connect to database - Ignore this bit, it's just setting up the database.
            $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
            $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
            $cxn = $connection;
            
            $sql = "SELECT item_amount FROM tbBids where item_id = '".$_SESSION['itemNumber']."' ORDER BY item_amount DESC LIMIT 1;";
            $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 4");
            $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
            if (($row["item_amount"] + 0.10) < $_POST['txtPlaceBid']) {
                $sql = "INSERT INTO tbBids (item_id, item_amount, item_bidder, item_date_bid) VALUES ('".$_SESSION['itemNumber']."', '".$_POST['txtPlaceBid']."', \"'".$_SESSION['WhoLoggedOn']."'\", NOW());";
                $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 3");
            }
            
    }
?>
<html>
<head> 
    <title>Items Page</title>
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <script src="js/validation.js" type="text/javascript"></script>
</head>
<body>
    <div class="PageLayout">
    <?php include 'Header.php' ?>
<form action=<?php echo $_SERVER['PHP_SELF']?> method="POST" id="ItemForm">
    <h1>Item details</h1>
    <?php
        // connect to database - Ignore this bit, it's just setting up the database.
        $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
        $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
        $cxn = $connection;
        
        $sql = "SELECT item_name, item_description, item_seller, item_location FROM tbItems where item_id = '".$_SESSION['itemNumber']."';";
        $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 1");
        echo '<table>';
        echo "<th>Item Name</th><th>Item Description</th><th>Item Seller</th><th>Item Location</th>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach($row as $field => $value) {
                echo "<td>";
                echo $value;
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        $sql = "SELECT CONCAT('£ ', item_amount), item_bidder FROM tbBids where item_id = '".$_SESSION['itemNumber']."' ORDER BY item_amount DESC LIMIT 1;";
        $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 2");
        echo '<table id="tblCurrentBid">';
        echo "<th>Current Bid</th><th>Current Highest Bidder</th>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach($row as $field => $value) {
                echo "<td>";
                echo $value;
                echo "</td>";
            }
            echo "</tr>";
            echo "<tr>";
            echo "<td>";
            echo 'Place a bid:<input type="text" name="txtPlaceBid">';
            echo "</td>";
            echo "<td>";
            echo '<input type="submit" name="PlaceBid" value="Place Bid"><br /><br />';
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    ?>
    <a href="History.php">Bidding history of Item</a>
    <br /><br />
    <a href="Auctions.php">Back to Auctions</a>
</form>


        
</div>
</body>
</html>