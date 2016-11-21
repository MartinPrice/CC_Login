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

            $sql = "INSERT INTO tbBids (item_id, item_amount, item_bidder) VALUES ('".$_SESSION['itemNumber']."', '".$_POST['txtPlaceBid']."', \"'".$_SESSION['WhoLoggedOn']."'\");";
            $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 3");
    }
?>
<html>
<head>
    <title>Items Page</title></head>
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
<body>
    <div class="PageLayout">
    <?php include 'Header.php' ?>
<form action=<?php echo $_SERVER['PHP_SELF']?> method="POST"> 
    <h1>Item details</h1>
    <?php
        // **************** echo "Which item was selected ? ".$_POST['submit'];
        echo "Which item was selected ? ".$_SESSION['itemNumber'];
        
        // connect to database - Ignore this bit, it's just setting up the database.
        $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
        $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
        $cxn = $connection;
        
        // **************** $sql = "SELECT * FROM tbItems where item_id = '".$_POST['submit']."';";
        $sql = "SELECT * FROM tbItems where item_id = '".$_SESSION['itemNumber']."';";
        $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 1");
        echo '<table>';
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

        // connect to database - Ignore this bit, it's just setting up the database.
        $connection2 = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
        $db2 = mysqli_select_db($connection2, "CustomerDirectory") or die ("Couldn't select database.");
        $cxn2 = $connection2;
        
        
        // ****************** $sql = "SELECT item_amount, item_bidder FROM tbBids where item_id = '".$_POST['submit']."' ORDER BY item_amount DESC LIMIT 1;";
        $sql = "SELECT item_amount, item_bidder FROM tbBids where item_id = '".$_SESSION['itemNumber']."' ORDER BY item_amount DESC LIMIT 1;";
        $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 2");
        echo '<table>';
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
</form>
</div>
</body>
</html>