<?php
session_start();
ob_start();
include('./../model/connect.php');
include('./../model/product.php');
include('./../model/order.php');
include('./../model/category.php');
include('./../model/size.php');
include('./../model/user.php');
include('./../helper/baseUrl.php');
include('./../helper/dd.php');

if(isset($_GET['dang-ky'])){
    include('./views/register.php');
    die;
}else if (isset($_GET['dang-nhap'])){
    include('./views/login.php');
    die;
}else if(isset($_GET['logout'])){
        unset($_SESSION["user"]);
}

if(isset($_SESSION["user"])){
    $user =$_SESSION["user"];
}
include('./views/layouts/header.php');


if(isset($_GET['url'])){
    $cates =  getCateAll();
    $size =  getSizeWhereType(1);
    $color =  getSizeWhereType(2);
    switch ($_GET['url']) {
        // Trang sản phẩm
        case 'san-pham':
           
            if(isset($_GET['cate'])){
                $products =  getProductWhereCate($_GET['cate']);
            }else if(isset($_GET['size'])){
                $products =  getProductWhereSize($_GET['size']);
            }else if(isset($_GET['color'])){
                $products =  getProductWhereSize($_GET['color']);
            }else if(isset($_GET['key_word'])){
                $key_word = $_GET['key_word'];
                $products =  searchProduct(($_GET['key_word']));
            }else{
                $products =  getProductAll();
            }
            include('./views/product.php');
            break;

        case 'tim-kiem-san-pham':
            if($_POST['key_word']){
                header("location:".BASE_CLIENT."?url=san-pham&key_word=" .$_POST['key_word']);
            }
            break;


        case 'binh-luan':
                if(isset($_SESSION["user"])){
                   $_POST["kh_id"] = $_SESSION["user"]["kh_id"];
                   commentProduct($_POST);
                   header("location:".BASE_CLIENT."?url=san-pham-chi-tiet&id=". $_POST["sp_id"]."&dg=danh-gia");
                }else{
                    header("location:".BASE_CLIENT."?dang-nhap");
                }
                break;


         case 'binh-luan-delete':
                if(isset($_GET["id"])){
                    deleteCmtt($_GET["id"]);
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
                   
                break;

            // Sản phẩm chi tiết
         case 'san-pham-chi-tiet':
            $error = '';
            $isDanhgia;
            if(isset($_GET['dg'])){
                $isDanhgia= $_GET['dg'];
            }
            if(isset($_GET['id'])){
                $imageSub = getSubImage($_GET['id']);
                $product = getProductFind($_GET['id']);
                $productRelate= getProductWhereCate($product[0]["dm_id"]);
                $comments = getCommentProduct($_GET['id']);
            }

            include('./views/product-detail.php');
            break;
        case 'xoa-gio-hang':
            if($_GET["id"]){
                // dd($_GET["id"]);
                unset($_SESSION["cart"][$_GET["id"]]);
            }
            // Quay về trang trước
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            break;

          // Sản phẩm chi tiết
        case 'add-gio-hang':
            $product = getProductFind($_POST['id']);
            $error = '';
            if(!empty( $_POST["size"]) && !empty( $_POST["color"]) ){
                if (isset($_SESSION["cart"])) {
                    $cart = $_SESSION["cart"] ;
                    if (array_key_exists($_POST["id"], $cart)) {
                        if (in_array($cart[$_POST["id"]]['size'], $cart[$_POST["id"]], true)) {
                            $cart[$_POST["id"]] = [
                                'id' => $_POST["id"],
                                'name' => $product[0]["sp_name"],
                                'img' => $product[0]["sp_image"],
                                'size' => $cart[$_POST["id"]]['size'] . ',' . $_POST["size"] . '-' . 'Màu ' .  $_POST["color"],
                                'price' => $product[0]["sp_sale"],
                                'number' => $cart[$_POST["id"]]['number'] + $_POST["quantity"], 
                                'current-quantity' => $product[0]["sp_quantity"],
                            ];
                        } else {
                            $cart[$_POST["id"]] = [
                                'id' => $_POST["id"],
                                'name' => $product[0]["sp_name"],
                                'img' => $product[0]["sp_image"],
                                'size' => $_POST["size"] . '-' .  'Màu ' .  $_POST["color"],
                                'price' => $product[0]["sp_sale"],
                                'number' => $cart[$_POST["id"]]['number'] + $_POST["quantity"],
                                'current-quantity' => $product[0]["sp_quantity"],
                            ];
                        }
                    } else {
                        $cart[$_POST["id"]] = [
                            'id' => $_POST["id"],
                            'name' => $product[0]["sp_name"],
                            'img' => $product[0]["sp_image"],
                            'size' => $_POST["size"] . '-' .  'Màu ' .  $_POST["color"],
                            'price' => $product[0]["sp_sale"],
                            'number' =>  $_POST["quantity"],
                            'current-quantity' => $product[0]["sp_quantity"],
                        ];
                    }
                } else {
                    $cart = [];
                    $cart[$_POST["id"]] = [
                        'id' => $_POST["id"],
                        'name' => $product[0]["sp_name"],
                        'img' => $product[0]["sp_image"],
                        'size' => $_POST["size"] . '-' .  'Màu ' .  $_POST["color"],
                        'price' => $product[0]["sp_sale"],
                        'number' =>$_POST["quantity"],
                        'current-quantity' => $product[0]["sp_quantity"],
                    ];
                }
                header("location:".BASE_CLIENT."?url=gio-hang");
            }else{
                $error = 'Bạn chưa chọn thuộc tính !!!';
                include('./views/product-detail.php');
            }
            
            $_SESSION['cart'] = $cart;
            break;

        // Trang giỏ hàng
        case 'gio-hang':
            if(!isset($_SESSION["user"])){
                header("location:".BASE_CLIENT."?dang-nhap");
            }
            if(isset($_SESSION['cart'])){
                $cart = $_SESSION['cart'];
                include('./views/cart.php');
            }else{
                header("Location: ".BASE_CLIENT."?san-pham");
            }
           
            break;

        // Trang liên hệ
         case 'lien-he':
            include('./views/contact.php');
            break;

        // Trang thanh toán
         case 'thanh-toan':
            if(!isset($_SESSION["user"])){
                header("location:".BASE_CLIENT."?dang-nhap");
            }elseif (!isset($_SESSION["cart"])) {
                header("location:".BASE_CLIENT."?url=san-pham");
            }else{
                $user = $_SESSION["user"];
                $cart =  $_SESSION['cart'];
                // dd($user);
                include('./views/pay.php');
                break;  
            }

         // Trang lưu thanh toán
         case 'luu-thanh-toan':
           if($_POST){
             try {
                $idNew = insertOrder($_POST);
                $cart =  $_SESSION['cart'];
                foreach( $cart as $key => $value){
                    insertOrderDetail($value ,  $idNew);
                }
                unset($_SESSION["cart"]);
                $_SESSION['success'] = 'Thanh toán thành công !!!';
             } catch (\Throwable $th) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
             };
            header("location:".BASE_CLIENT."");
           }
           break;

        // Trang chi tiết sản phẩm
         case 'san_pham/chi-tiet':
            include('./views/product-detail.php');
            break;
        default:
            # code...
            break;
    }
}else{
    $products =  getProductAll();
    include('./views/home.php');
}


include('./views/layouts/footer.php');



ob_end_flush();