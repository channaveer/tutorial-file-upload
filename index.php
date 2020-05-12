<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Demo</title>
    <style>
        .wrapper{ width: 250px; margin: auto; }
        form input{ width: 100%; padding: 3px; }
        .error{ font-style: italic; color: red; }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
            if(isset($errors)){
                foreach($errors as $error){
                    echo '<p class="error">'. $error .'</p>';
                }
            }
        ?>

        <form action="store_product.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="product_name">Product Name</label> <br>
                <input type="text" name="product_name" id="product_name">
            </div><br>
            <div>
                <label for="product_invoice">Product Invoice</label> <br>
                <input type="file" name="product_invoice" id="product_invoice">
            </div><br>
            <div>
                <input type="submit" value="Create Product">
            </div>
        </form>
    </div>
</body>
</html>