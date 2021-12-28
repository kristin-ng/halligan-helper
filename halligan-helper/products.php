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
  <h1>Buy Our Products</h1>
  <div class="purchase">
    <div class="prices">
      <script>
        function MenuItem(name, cost) {
          this.name = name;
          this.cost = cost;
        }

        menuItems = new Array(
          new MenuItem("Electronic Energy Drinks", 3),
          new MenuItem("Java Coffee", 3),
          new MenuItem("C++ Tea", 3),
          new MenuItem("Python Protein Bars", 6.5),
          new MenuItem("React Ramen", 4),
          new MenuItem("C Food Platter", 10),
          new MenuItem("MatLab MeatLoaf", 8),
          new MenuItem("Halligan Hamburgers", 6),
          new MenuItem("Phone charger (per hour)", 2),
          new MenuItem("Laptop charger (per hour)", 2),
          new MenuItem("Sleeping bags", 3),
          new MenuItem("Spray Shampoo", 5),
          new MenuItem("Tampons/Pad", 2),
          new MenuItem("Deodorant", 5),
          new MenuItem("Perfume", 10),
        );

        function updateCosts() {
          var subtotal = 0;
          // Changing total for each dish
          for (i = 0; i < menuItems.length; i++) {
            var selected = document.getElementsByName('quan' + i)[0].value;
            document.getElementsByName("cost" + i)[0].value = (menuItems[i].cost * selected).toFixed(2);
            subtotal += menuItems[i].cost * selected
          }
          document.getElementById('subtotal').value = subtotal.toFixed(2);
          document.getElementById('tax').value = (subtotal * 0.0625).toFixed(2);
          document.getElementById('total').value = (subtotal * 1.0625).toFixed(2);
        }
      </script>
      
      <!-- get menu items and put into select -->
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
          
          //run a query for names, prices
          $sql = "SELECT Name, Price FROM products";
          $result = $conn->query($sql);

          $products = array();
          if ($result->num_rows > 0) {
              while($row = $result->fetch_array()) 
              {
                  $products[$row[0]] = $row[1];
              }
          }
          else {
              echo "No Results :(";
          }
          
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
          	
          //close the connection	
          $conn->close();
          
          // page view functions
          function makeSelect($name, $minRange, $maxRange) {
              $t = "";
              $t .= "<select name='" . $name . "' size='1' onchange='changeCost()'>";
              for ($j=$minRange; $j<=$maxRange; $j++)
                  $t .= "<option>" . $j . "</option>";
              $t .= "</select>";
              return $t;
          }
          
          function makeMenu() {
              global $products;
              global $amount;
              $s = "";
              
              $i = 0;
              foreach($products as $name=>$price) {
                  makeIndividualMenu($name, $price, $i);
                  $i++;
              }
              return $s;
          }

          function makeIndividualMenu($name, $price, $i) {
              $s .= "<tr><td>";
              $s .= "<img class='' src='product_images/product" . $i . ".jpg' width='100' height='75'></img></td><td>";
              $s .= makeSelect("quan" . $i, 0, 10);
              $s .= "</td><td>" . $name . "</td>";
              $s .= "</td><td>" . $amount[$i] . "</td>";
              $s .= "<td> $" . number_format((float)$price, 2, '.', '') . "</td>";
              $s .= "<td>$<input type='text' name='cost" . $i . "'/></td></tr>";
          }

          function verification() 
            $i = 0;{
            foreach($products as $name=>$price) {
              if ($amount[i] <= 0) { // Warn user and reset menu for the product
                echo "alert(" . $name . "' is out of stock!')";
                makeIndividualMenu($name, $price, $i);
              }
            }
            i++;
            updateCosts();
          }
          
      ?>
      <form action="order.php" method="post">
      <table border="0" cellpadding="3" onchange="verification()" style="margin-left: auto; margin-right: auto;">
      <input type="hidden" name="productNames" value="Electronic Energy Drinks,Java Coffee,C++ Tea,Python Protein Bars,React Ramen,C Food Platter,MatLab MeatLoaf,Halligan Hamburgers,Phone charger (per hour),Laptop charger (per hour),Sleeping bags,Spray Shampoo,Tampons/Pad,Deodorant,Perfume">
        <tr>
          <th>Images</th>
          <th>Select Item</th>
          <th>Item Name</th>
          <th>Stock Left</th>
          <th>Cost Each</th>
          <th>Total Cost</th>
        </tr>
        <?php
            echo makeMenu();
        ?>
      </table>
    </div>
    <div class="totals">
      <script>
        function submitOrder() {
          document.getElementById("results").innerHTML = "Thank you for your order. Your total is $" + document.getElementById('total').value +".";
        };
      </script>
      <p>Subtotal:
        $<input type="text" name='subtotal' id="subtotal" />
      </p>
      <p>Mass tax 6.25%:
        $ <input type="text" name='tax' id="tax" />
      </p>
      <p>Total: $ <input type="text" name='total' id="total" />
      </p>
      <input type = "submit" value = "Submit Order"/>
      </form>
    </div>
  </div>
  <hr />

</body>
<footer>
  Company © Halligan Helper. All rights reserved.
</footer>

</html>