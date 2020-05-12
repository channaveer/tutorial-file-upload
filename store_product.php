<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /** array variable to hold errors */
    $errors = [];

    $product_name   = $_POST['product_name'];
    $product_invoice  = $_FILES['product_invoice'];
    /** Add form validation */
    if (empty($product_invoice['name'])) {
        $errors[] = 'Product invoice file required';
    }
    if (empty($product_name)) {
        $errors[] = 'Product name required';
    }
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
    }

    /** $_FILES will have the upload file details in PHP */
    
    // echo '<pre>';
    // print_r($_FILES['product_invoice']);
    // print_r(pathinfo($_FILES['product_invoice']['name']));
    // exit;

    /** I am using pathinfo to fetch the details of the PHP File */
    $file_name          = $product_invoice['name'];
    $file_size          = $product_invoice['size'];
    $file_tmp           = $product_invoice['tmp_name'];
    $pathinfo           = pathinfo($file_name);
    $extension          = $pathinfo['extension'];
    $file_extensions   = ['pdf', 'xls', 'jpeg', 'jpg', 'png', 'svg', 'webp'];


    $finfo = new finfo(FILEINFO_MIME_TYPE);
    echo '<pre>';
    print_r($finfo->file($file_tmp));exit;

    /** File strict validations */
    
    /** File exists */
    if(!file_exists($file_tmp)){
        $errors[] = 'File your trying to upload not exists';
    }

    /** Check if the was uploaded only */
    if(!is_uploaded_file($file_tmp)){
        $errors[] = 'File not uploaded properly';
    }

    /** Check for the file size 1024 * 1024 is 1 MB & 1024 KB */
    if($file_size > (1024 * 1024)){
        $errors[] = 'Uploaded file is greater than 1MB';
    }

    /** Check File extensions */
    if(!in_array($extension, $file_extensions)){
        $errors[] = 'File allowed extensions '. implode(', ', $file_extensions);
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit;
    }
    /** Since I want to rename the File I need its extension
     * which will be obtained with above $phpinfo variable
     * */
    /** generate random inage name */
    $new_file_name = rand(0, 10000000).time().md5(time()).'.'.$extension;
    move_uploaded_file($file_tmp, './uploads/products/'. $new_file_name);
    
    $product = $pdo->prepare("
        INSERT INTO 
            `products` (`name`, `product_invoice`)
        VALUES
            (:product_name, :product_invoice)
    ")
    ->execute([
        ':product_name'     => $product_name,
        ':product_invoice'    => $new_file_name,
    ]);

    if ($product) {
        echo 'Product added successfully';
    }
}
