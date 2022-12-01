
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./../assets/Css/base.css">
    <link rel="stylesheet" href="./../assets/Css/cart-header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../assets/Css/register.css">
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v14.0"
        nonce="vWDw95v7"></script>
</head>

<body>
<div class="container">
    <div class="form__login-box">
        <div class="title">
            <h2 class="title_login">Đăng ký</h2>
            <h4><a class="title_register" href="?dang-nhap">Đăng nhập</a></h4>
        </div>
        <div class="error-message">
            <p class="message">Bạn đang bỏ trống dữ liệu</p>
            <button onClick="closeMessage()" class="close-message"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-register"  method="POST" enctype='multipart/form-data'> 
            <div class="input__bag">
                <input class="contact__input" type="text" name="kh_name" placeholder="Họ và tên">
            </div>
            <div class=" input__bag-image-box">
                <label class="label-image" for="image"><i class="fa-solid fa-user-pen"></i></label>
                <input type="file" hidden id="image" onchange="preview()" class="contact__input-file"  name="kh_avatar" type="text">
                 <div class="image-box">
                     <img  id="frame" class="input__bag-image" src="data:image/png;base64,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" class="preview" alt="">
                     <p>Ảnh đại diện</p>
                 </div>
            </div>
            <div class="input__bag">
                <input class="contact__input" type="text" name="kh_email"  placeholder="Email">
            </div>
            <div class="input__bag">
                <input class="contact__input" type="password"  name="kh_password" placeholder="Password">
            </div>
            <div class="input__bag">
                <input class="contact__input" type="text"  name="kh_phone" placeholder="Số diện thoại">
            </div>
            <button class="contact__btn">Đăng ký</button>
        </form>
    </div>
</div>
<script src="./../assets/js/ajax-auth.js"></script>