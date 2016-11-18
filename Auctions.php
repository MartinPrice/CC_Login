<?php
    session_start();
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
        
        $sql = "SELECT * FROM tbItems;";
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
    ?>
    </form>
    </div>
</body>
</html>