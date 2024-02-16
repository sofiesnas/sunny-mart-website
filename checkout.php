<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.8.0/src/Exception.php';
require 'PHPMailer-6.8.0/src/PHPMailer.php';
require 'PHPMailer-6.8.0/src/SMTP.php';

include 'config/connect.php';

session_start();

if(isset($message)){
    foreach($message as $message){
        echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
    }
 }
 if(isset($_POST['order_btn'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $street = $_POST['street'];
    $suburb = $_POST['suburb'];
    $state = $_POST['state'];
    $country = $_POST['country'];
 
    $cart_query = mysqli_query($con, "SELECT * FROM cart");
    $price_total = 0;
    if(mysqli_num_rows($cart_query) > 0){
       while($product_item = mysqli_fetch_assoc($cart_query)){
          $product_name[] = $product_item['name'] .' ('. $product_item['quantity'] .') ';
          $product_price = number_format($product_item['price'] * $product_item['quantity']);
          $price_total += $product_price;
       };
    };
 
    $total_product = implode(', ',$product_name);
    $detail_query = mysqli_query($con, "INSERT INTO orders (name, email, street, suburb, state, country, total_products, total_price) VALUES('$name','$email','$street','$suburb','$state','$country','$total_product','$price_total')") or die('query failed');
 
    if($cart_query && $detail_query){
        $fetch_order = mysqli_query($con, "SELECT * FROM orders ORDER BY id DESC LIMIT 1");
        $order_item = mysqli_fetch_assoc($fetch_order);
        // Set up the PHPMailer object
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'xxxxxx';
        $mail->Password = 'xxxxxxx';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('xxxxxxx', 'Sunny Mart');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Your order details - Sunny Mart';
        $mail->Body = '
            <p>Thank you for your order!<br></p>
            <p>Here are your order details:</p>
            <p>Products: '.$total_product.'</p>
            <p>Total Price: $'.$price_total.'</p>
            <p>Delivery Address: '.$street.', '.$suburb.', '.$state.', '.$country.'</p>
            <p>Order placed on: '.$order_item['placed_on'].'</p>
            <p>Please pay to the delivery courrier when your items arrive.<br></p>
            <p><b>Sunny Mart</b></p>
        ';

        // Send the email
        $mail->send();
        echo "
        <div class='order-message-container'>
        <div class='message-container'>
        <h3>thank you for shopping!</h3>
        <div class='order-detail'>
            <span>".$total_product."</span>
            <span class='total'> total : $".$price_total."  </span>
        </div>
        <div class='customer-details'>
            <p> your name : <span>".$name."</span> </p>
            <p> your email : <span>".$email."</span> </p>
            <p> your address : <span>".$street.", ".$suburb.", ".$state.", ".$country."</span></p>
            <p> order details are sent to your email! </p>
        </div>
            <a href='index.php' class='continueBtn'>continue shopping</a>
        </div>
        </div>
        ";
    }
}
 include 'includes/header.php';
?>
    <main>
        <section class="shopping-cart">
            <h2>checkout</h2>
            <table>
                <thead>
                    <th>image</th>
                    <th>name</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>total price</th>
                    <th>action</th>
                </thead>
                <tbody>
                <?php
                    $cart_query = mysqli_query($con, "SELECT * FROM cart") or die('query failed');
                    $grand_total = 0;
                    if(mysqli_num_rows($cart_query) > 0){
                        while($fetch_cart = mysqli_fetch_assoc($cart_query)){
                ?>
                    <tr>
                        <td><img src="<?php echo $fetch_cart['image']; ?>" height="40" alt=""></td>
                        <td><?php echo $fetch_cart['name']; ?></td>
                        <td>$<?php echo $fetch_cart['price']; ?></td>
                        <td><?php echo $fetch_cart['quantity']; ?></td>
                        <td>
                        </td>
                        <td>$<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></td>
                    </tr>
                <?php
                    $grand_total += $sub_total;
                        }
                    }else{
                        echo '<tr><td style="padding:20px; " colspan="6">no item added</td></tr>';
                    }
                ?>
                <tr class="table-bottom">
                    <td colspan="4">grand total :</td>
                    <td>$<?php echo $grand_total; ?></td>
                </tr>
            </tbody>
            </table>
        </section>
        
        <section class="checkout-form">
            <form action="" method="post">
                <div class="flex">
                    <div class="inputBox">
                        <span>name</span>
                        <input type="text" placeholder="enter your name" name="name" required>
                    </div>
                    <div class="inputBox">
                        <span>email</span>
                        <input type="email" placeholder="enter your email" name="email" required>
                    </div>
                    <div class="inputBox">
                        <span>street</span>
                        <input type="text" placeholder="e.g. 9 Paramatta Rd" name="street" required>
                    </div>
                    <div class="inputBox">
                        <span>suburb</span>
                        <input type="text" placeholder="e.g. Annandale" name="suburb" required>
                    </div>
                    <div class="inputBox">
                        <span>state</span>
                        <input type="text" placeholder="e.g. NSW" name="state" required>
                    </div>
                    <div class="inputBox">
                        <span>country</span>
                        <input type="text" placeholder="e.g. Australia" name="country" required>
                    </div>
                </div>
                <input type="submit" value="place order" name="order_btn" class="orderBtn">
            </form>
        </section>
<?php include 'includes/footer.php';?>