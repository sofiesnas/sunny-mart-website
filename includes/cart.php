
<section class="shopping-cart">
    <h2>Cart</h2>
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
                <td>
                <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                    <input type="number" min="1" max="20" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                    <input type="submit" name="update_cart" value="update" class="option-btn">
                </form>
                </td>
                <td>$<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></td>
                <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('remove item from cart?');">remove</a></td>
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
            <td><a href="index.php?delete_all" onclick="return confirm('Delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">delete all</a></td>
        </tr>
    </tbody>
    </table>

    <div class="cart-btn">  
        <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
    </div>

</section>