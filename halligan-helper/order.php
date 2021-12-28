<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<html lang="en">

<head>
  <title>Halligan Helper</title>
  <meta name="viewport" content="width=device-width, initial-scale-1, user-scalable=no" />
  <link rel="stylesheet" href="index.css" />
</head>

<body>
    
<div class="nav-bar">
     <div class="nav-left">
         <a href="index.html">
           <div class="logo">
             <img src="logo.jpg" alt="logo" style="height: 5%;"></img>
           </div>
         </a>
     </div>
     <div class="nav-right">
         <a href="index.html">Home</a>
         <a href="about-us.html">About Us</a>
         <a href="products.php">Products</a>
         <a href="feedback.html">Feedback</a>
         <a href="contacts.html">Contact</a>
     </div>
 </div>
 
 <h1>Order Summary</h1>


<!-- Get form data -->
<?php
    //establish connection info
    $server = "sql304.epizy.com";
    $userid = "epiz_29722285";
    $pw = "ny8b6tbqsUQT7ML";
    $db= "epiz_29722285_halliganhelper";

    // Create connection
    $conn = new mysqli($server, $userid, $pw);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //select the database
    $conn->select_db($db);
    
    //run a query for amount left
    $sql = "SELECT ItemsLeft FROM products";
    $result = $conn->query($sql);

    $amount = array();
    $count = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) 
        {
            $amount[$count] = $row['ItemsLeft'];
            $count++;
        }
    }
    else {
        echo "No Results :(";
    }


    $productNames = explode(",", $_REQUEST["productNames"], 15);
    echo "Below is a detailed summary of your order: <br>";
    echo "<br>";
    // print name, quan, and cost for each product
    $orderSuccess = True;
    for ($i = 0; $i < 15; $i++) {
        $quantity = $_REQUEST["quan$i"];
        $name = $productNames[$i];
        if ($quantity > 0) {
            $left = $amount[$i] - $quantity;
            if ($amount[$i] <= 0) {
                $orderSuccess = False;
                $s = "Order failed! Your item is not in stock!";
                $i = 15;
            }
            if ($orderSuccess) {
                // update stock left
                $sql = "UPDATE products SET ItemsLeft=$left WHERE Name='$name'";
                if (!mysqli_query($conn, $sql)) {
                    echo "Error updating record: " . mysqli_error($conn);
                }
                echo $quantity . " " . $name . " for              ";
                echo "$" . $_REQUEST["cost$i"];
                echo "<br>";
            }
        }
    }
    echo $s;
    // print subtotal, tax, total
    if ($orderSuccess) {
        $total = $_REQUEST["total"];
        echo " <br> Subtotal                                  $";
        echo $_REQUEST["subtotal"] . "<br>";
        echo "Tax                                             $";
        echo $_REQUEST["tax"] . "<br>";
        echo "Total                                           $";
        echo $total . "<br> <br>";
    }
?>
</body>
</html>