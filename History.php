<?php
    session_start();
    if (@$_SESSION['auth'] != "yes") {
        header("Location: LoginPage.php");
        exit();
}
?>
<html>
<head>
    <title>Item bid history</title>
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="PageLayout">
    <?php include 'Header.php' ?>
    <h1>Item details</h1>
    <?php
        // connect to database - Ignore this bit, it's just setting up the database.
        $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
        $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
        $cxn = $connection;
        
        $sql = "SELECT CONCAT('£ ', item_amount), item_bidder, item_date_bid FROM tbBids where item_id = '".$_SESSION['itemNumber']."' ORDER BY item_date_bid DESC;";
        $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 2");
        echo '<table>';
        echo "<th>Bid</th><th>Bidder</th>";
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
    ?>
    <a href="Item.php">Back to Item Details</a>
    <br /><br />
    <a href="Auctions.php">Back to Auctions</a>
    </div>
</body>
</html>
