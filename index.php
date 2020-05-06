<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location:login");
    exit();
}
include_once './include/dbh.php';
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./dist/css/style.min.css">
    <title>Anime Tracker</title>
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container">
            <a href="/" class="navbar-brand font-weight-bold">MangaTrack</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#mobileNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mobileNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="./include/logout.php" class="nav-link">Signout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <!-- Landing Bg Start -->
    <section class="landing-background">
        <div class="welcome-section container">
            <div>
            <div class="heading">
                <h1 class="font-weight-bold display-4" id="welcome-heading"></h1> 
                <h1 class="font-weight-bold display-4" id="welcome-heading"><?php echo ucfirst($_SESSION["username"]) ?></h1> 
            </div>
            <div class="ui-actions">
                <a href="#" class="btn btn-lg btn-custom-blue" id="addCollectionCTA">Add An Anime</a>               
            </div> 
            </div>
        </div>
    </section>
    <!-- Landing bg End -->
    <!-- List Items Start -->
    
    <!-- Manga List Start -->
    <section class="section-padding manga-list">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="heading mb-5">
                        <h1 class="font-weight-bold display-4">Anime List</h1>
                    </div>
                </div>
            </div>
            <div class="row" id="manga-page-content">
            </div>
        </div>
    </section>
    <!-- Manga List End -->
    <!-- List Items End -->

    <div class="collection-form-container" id="collection-form">
        <!-- Add to Collection Form Start -->            
            <form method="post" class="mt-3" id="collection-item-form">
                <button class="close" id="close-collection-form">
                    <i class="fas fa-times text-white"></i>
                </button>
                <div id="collection-item-form-msg"></div>
                <div class="form-group">
                    <label class="font-weight-bold" for="name">Name</label>
                    <input type="text" name="collection_item_name" id="collection_item_name" class="form-control">
                    <small id="name-help-text" class="font-weight-bold"></small><br>
                    <small id="episode-help-text" class="font-weight-bold"></small>
                </div>
                
                <input type="hidden" name="collection_item_url" id="collection_item_url" value="">
                <input type="hidden" name="collection_item_id" id="collection_item_id" value="">
                <input type="hidden" name="collection_item_picture" id="collection_item_picture" value="">
                <input type="hidden" name="collection_item_updatedAt" id="collection_item_updatedAt" value="">
                <input type="hidden" id="user_id" name="user_id" value="<?php echo "{$_SESSION['user_id']}" ?>">
                
                <input type="submit" value="Save" class="btn btn-block btn-lg btn-custom-blue mt-4">
            </form>
        <!-- Add to Collection From End --> 
    </div>
<!-- Modal -->
<div class="modal fade" id="delete_modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to delete this?
            </div>
            <div class="modal-footer">
                <form method="post" id="delete_modal_form">
                    <input type="submit" class="btn btn-secondary" data-dismiss="modal" value="No" />
                    <input type="submit" class="btn btn-primary" value="Yes" />
                    <input type="hidden" name="collection_item_id" id="delete_modal_collection_item_id">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="success_modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body"></div>
            <div class="modal-footer">          
                <button class="btn btn-custom-blue" data-dimiss="modal" onclick="$('#success_modal').modal('hide')">Ok</button>                      
            </div>
        </div>
    </div>
</div>


<script src="./js/main.js"></script>
<script src="./js/animation.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!-- Gsap CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.2.6/gsap.min.js" integrity="sha256-3arngJBQR3FTyeRtL3muAGFaGcL8iHsubYOqq48mBLw=" crossorigin="anonymous"></script>
</body>
</html>