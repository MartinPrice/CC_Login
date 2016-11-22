<?php
    session_start();
    if (@$_SESSION['auth'] != "yes") {
        header("Location: LoginPage.php");
        exit();
    }
?>
<html>
<head>
    <title>Auctions Page</title>
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
    <script language="javascript" src="js/CountdownClock.js"></script>
</head>
<body>
    <div class="PageLayout">
    <?php include 'Header.php'; ?>
    <?php
        $timestamp = strtotime('22-11-2017 13:15:00');
        $rdate1 = $timestamp;
        $expiredate = date('d m Y G:i:s', $rdate1);       
        $time = $rdate1 - time();
        
        if ($time <= $expiredate) {
            echo "Bidding has ended";
            rename("Item.php", "ItemBiddingEnded.php");
            $myfile = fopen("Item.php", "w") or die("Unable to create new Item.php file!");
            $txt = '<?php header("Location: Auctions.php"); exit(); ?>';
            fwrite($myfile, $txt);
            fclose($myfile);

            // connect to database - Ignore this bit, it's just setting up the database.
            $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
            $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
            $cxn = $connection;

            $sql = "SELECT item_name, item_description, item_seller, item_location, "
                    . "CONCAT('£ ',(SELECT item_amount FROM tbBids WHERE item_id = tbItems.item_id ORDER BY item_amount DESC LIMIT 1), "
                    . "(SELECT item_bidder FROM tbBids WHERE item_id = tbItems.item_id ORDER BY item_amount DESC LIMIT 1), "
                    . "item_id "
                    . "FROM tbItems;";
            $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 1");
            echo '<table>';
            echo '<tr>';
            echo '<th>Item</th>';
            echo '<th>Description</th>';
            echo '<th>Seller</th>';
            echo '<th>Location</th>';
            echo '<th>Winning Bid</th>';
            echo '<th>Winning Bidder</th>';
            echo '</tr>';
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
            // email winning bidder
            exit();
        }
        if ($time > $expiredate) {
            $days = floor($time/86400);
            $hours = floor(($time-($days*86400))/3600);
            $mins = floor(($time-($days*86400)-($hours*3600))/60);
            $secs = floor($time-($days*86400)-($hours*3600)-($mins*60));

            printf("
                Bidding ends in
                <span class='datetime' id='days'>%s</span> Days
                <span class='datetime' id='hours'>%s</span> Hours
                <span class='datetime' id='minutes'>%s</span> Minutes
                <span class='datetime' id='seconds'>%s</span> Seconds
                ", $days, $hours, $mins, $secs);
        }
    ?>
    <form action="Item.php" method="post">
    <?php
        if(isset($_SESSION['WhoLoggedOn'])) {
            echo "Welcome ".$_SESSION['WhoLoggedOn'];
        }
        // connect to database - Ignore this bit, it's just setting up the database.
        $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
        $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
        $cxn = $connection;
        
        $sql = "SELECT item_name, item_description, item_seller, item_location, "
                . "CONCAT('£ ',(SELECT item_amount FROM tbBids WHERE item_id = tbItems.item_id ORDER BY item_amount DESC LIMIT 1)), "
                . "(SELECT item_bidder FROM tbBids WHERE item_id = tbItems.item_id ORDER BY item_amount DESC LIMIT 1), "
                . "item_id "
                . "FROM tbItems;";
        $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 1");
        echo '<table>';
        echo '<tr>';
        echo '<th>Item</th>';
        echo '<th>Description</th>';
        echo '<th>Seller</th>';
        echo '<th>Location</th>';
        echo '<th>Top Bid</th>';
        echo '<th>Top Bidder</th>';
        echo '</tr>';
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach($row as $field => $value) {
                echo "<td>";
                echo $value;
                echo "</td>";
            }
            echo "<td>";
            echo '<button name="submit" value="'.$row['item_id'].'" type="submit" title="Submit">'.$row['item_id'].'</button>';
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    ?>
    </form>
    </div>
</body>
</html>