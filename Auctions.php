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
</head>
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
<body>
    <div class="PageLayout">
    <?php include 'Header.php'; ?>
    <form action="Item.php" method="post">
    <p>This is my secret page.</p>
    <?php
        if(isset($_SESSION['WhoLoggedOn'])) {
            echo "Welcome ".$_SESSION['WhoLoggedOn'];
        }
        // connect to database - Ignore this bit, it's just setting up the database.
        $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
        $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
        $cxn = $connection;
        
        $sql = "SELECT item_name, item_description, item_seller, item_location, "
                . "(SELECT item_amount FROM tbBids WHERE item_id = tbItems.item_id ORDER BY item_amount DESC LIMIT 1), "
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