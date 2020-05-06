<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./dist/css/style.min.css">
    <title>Anime Tracker</title>
    <style>
    body {
        position: relative;
        background: url('./img/register-cover-img.jpg') 50% 50% no-repeat;
        background-position: center;
        background-size: cover;
        width: 100%;
        height: 100vh;
    }
    body::after {
        content: '';
        z-index: -1;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(0, 0, 0, .2);
    }
    section {
        padding-left: 15px;
        padding-right: 15px;
        position: absolute;
        top: 50%;
        left: 50%;    
        width: 100%;
        transform: translate(-50%,-50%);
        max-width: 600px;
    }
    h1{
        color: #fff;
    }
    small {
        color:#6c757d;
    }
    </style>
</head>
<body>

    <section>
        <form class="login-form mx-auto" action="./include/register.php" method="post" id="register_form">
            <div class="heading text-center">
                <h4 class="text-white display-4 font-weight-bold">Register</h4>
                <p class="text-white mb-5">Fill the form below to create an account</p>
            </div>  
            <?php
            if(isset($_GET['error'])) {
                if($_GET["error"] === "emptyfields") {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>Please fill the empty fields</strong>
                        <button type='button' class='close' data-dismiss='alert'>
                            <span>&times;</span>
                        </button>
                    </div>";
                }
                if($_GET["error"] === "match") {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>Passwords do not match</strong>
                        <button type='button' class='close' data-dismiss='alert'>
                            <span>&times;</span>
                        </button>
                    </div>";
                }
                if($_GET["error"] === "invalid") {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>Passwords do not match</strong>
                        <button type='button' class='close' data-dismiss='alert'>
                            <span>&times;</span>
                        </button>
                    </div>";
                }
                
            }
            
            ?>
            <div class="form-group">
                <label class="font-weight-bold" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control">
                <small class="" id="username_helper_text">Required(*)</small>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="password1">Password</label>
                <input type="password" name="password1" id="password1" class="form-control">
                <small class="" id="password1_helper_text">Required(*)</small>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="password2">Confirm Password</label>
                <input type="password" name="password2" id="password2" class="form-control">
                <small class="" id="password2_helper_text">Required(*)</small>
            </div>
            <input name="submit" type="submit" value="Register" class="btn btn-lg btn-custom-blue font-weight-bold mt-5 btn-block" />
        </form>
        <p class="text-center mt-4">
            <a href="./login.php" class="ml-3 text-white font-weight-bold h4">Want to signin?</a>
        </p>
    </section>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="./js/register.js"></script>
</body>
</html>