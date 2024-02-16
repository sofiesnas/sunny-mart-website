<?php

include 'config/connect.php';
session_start();

$max_quantity = 20;

if(isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    $product_image = $_POST['product_image'];

    $query = "SELECT * FROM products WHERE name='$product_name' AND in_stock='Yes'";
    $result = mysqli_query($con, $query) or die('query failed');
    if(mysqli_num_rows($result) > 0) {
        $select_cart = mysqli_query($con, "SELECT * FROM cart WHERE name = '$product_name'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            $row = mysqli_fetch_assoc($select_cart);
            $new_quantity = $row['quantity'] + $product_quantity;
            $cart_id = $row['id'];
            if ($new_quantity <= $max_quantity) {
                mysqli_query($con, "UPDATE cart SET quantity='$new_quantity' WHERE id='$cart_id'") or die('query failed');
                $message[] = 'quantity updated in cart!';
            } else {
                $message[] = 'maximum item quantity reached!';
            }
         } else {
            mysqli_query($con, "INSERT INTO cart (name, price, quantity, image) VALUES ('$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'added to cart!';
         }
    } else {
        $message[] = 'product is out of stock!';
    }
}
if(isset($_POST['update_cart'])){
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($con, "UPDATE cart SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
    $message[] = 'cart quantity updated successfully!';
 }
 if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    mysqli_query($con, "DELETE FROM cart WHERE id = '$remove_id'") or die('query failed');
    header('location:index.php');
 }
 if(isset($_GET['delete_all'])){
    mysqli_query($con, "DELETE FROM cart") or die('query failed');
    header('location:index.php');
 }
if(isset($message)){
    foreach($message as $message){
        echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
    }
 }
 include 'includes/header.php';
?>
    <main>
        <?php
        $category_id = $_GET['category_id'] ?? null; // using null coalescing operator to avoid undefined variable notice
        $select_product = mysqli_query($con, "SELECT * FROM products" . ($category_id ? " WHERE category_id = '$category_id'" : "")) or die('query failed');
        if(mysqli_num_rows($select_product) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_product)){
        ?>
        <section class="card">
            <div class="view-more">
                <a href="product.php?id=<?php echo $fetch_product['id']; ?>" class="viewBtn"><ion-icon name="eye-outline"></ion-icon></a>
            </div>
            <div class="product_image">
                <img src="<?php echo $fetch_product['image']; ?>" alt="">
            </div>
            <div class="caption">
                <p class="product_name"><?php echo $fetch_product['name']; ?></p>
                <p class="price"><b>$<?php echo $fetch_product['price']; ?></b></p>
                <p class="in_stock">In Stock: <?php echo $fetch_product['in_stock']; ?></p>
            </div>
            <form method="post" class="box" action="">
                <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                <input type="hidden" min="1" name="product_quantity" value="1">
                <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                <input type="submit" name="add_to_cart" value="add to cart" class="btn">
            </form>
        </section>
        <?php
            }
        } else {
            echo "No products found.";
        }
        include 'includes/cart.php';
        ?>
<?php include 'includes/footer.php';?>


