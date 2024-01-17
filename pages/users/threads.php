<?php

require_once "../../classes/category_crud.php";
require_once "../../classes/threads_crud.php";
require_once "../../classes/user_crud.php";

$category = new category;
$thread = new thread;
$user = new user;

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
if($user->validataBan($_SESSION['user_id'])){
    
	header("Location: ../ban-error.php");
	exit();
}
if(isset($_POST['displayThreadSearch'])){
    $thread->setTitle($_POST['thread_title'])
    ->display();
    exit();
}
if(isset($_POST['displayThread'])){
    $thread->display();
    exit();
}

if (isset($_POST['searchCategory'])) {
    $category->setSearchValue($_POST['value'])->display("search","");
    exit();
}
if (isset($_POST['addThread'])) {
    $thread
    ->setTitle($_POST['title'])
    ->setCategoryId($_POST['category'])
    ->setDescription($_POST['descriprion'])
    ->operation("add");
    exit(); 
}

if (isset($_POST['getEditData'])) {
    $thread
    ->setThreadId($_POST['thread_id'])
    ->getSingleData("");
    exit(); 
}
if (isset($_POST['editThread'])) {
    $thread
    ->setThreadId($_POST['thread_id'])
    ->setTitle($_POST['title'])
    ->setCategoryId($_POST['category'])
    ->setDescription($_POST['descriprion'])
    ->operation("edit");
    exit(); 
}
if (isset($_POST['deleteThread'])) {
    $thread
    ->setThreadId($_POST['thread_id'])
    ->operation("delete");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threads</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../../css/fontawesome/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/threads.css">
    <link rel="stylesheet" href="../../css/comfirmjs.css">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    <div style="width: 100%;box-shadow: 1px 5px 10px rgba(0,0,0,.1)">
        <div class="body-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid" style="padding-left: 60px;">
                    <a class="navbar-brand" href="../../" style="font-weight: 500;">
                        <span class="logo-aspin"><sup>A</sup> spin</span><span class="logo-f">Forum</span> <sup><span class="logo-s">s</span></sup>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item responsive-nav">
                                <a class='nav-link' href="../../"><i class="fa-solid fa-house"></i>Home</a>
                            </li>
                            <li class="nav-item responsive-nav active">
                                <a class='nav-link' href="threads.php"><i class="fa-solid fa-brain"></i>Threads</a>
                            </li>

                            <?php 
                            
                                if($_SESSION['role'] =="Admin"){
                                    echo "<li class='nav-item responsive-nav'>
                                    <a class='nav-link' href='../admin/category.php'><i class='fa-solid fa-table-list'></i>Category</a>
                                    </li>";
                                    echo "<li class='nav-item responsive-nav'>
                                    <a class='nav-link' href='../admin/user.php'><i class='fa-solid fa-user'></i>Users</a>
                                    </li>";
                                }
                            ?>
                            <li class="nav-item responsive-nav">
                                <a class='nav-link' href="profile.php"><i class="fa-solid fa-address-card"></i>Profile</a>
                            </li>
                            <li class="nav-item responsive-nav">
                                <a class='nav-link' href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link username" href="profile.php">
                                    <div style="display: flex;justify-content:between;align-items:center">
                                        <div class="profile-pic">
                                            <img src="../../<?php echo $_SESSION['image'] ?>" alt="" srcset="">
                                        </div>
                                        <div style="margin-left:10px">
                                            <?php echo $_SESSION['name'] ?>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item logout" style="display: grid;justify-content:center;align-items:center">
                                <a class="nav-link" href="../logout.php">
                                    <span style="margin: 0 10px;color:rgba(0,0,0,.2)">|</span> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="body-content">
        <div class="container-fluid main-content-container">
            <div class="row">
                <div class="col-lg-3 d-none d-xs-none d-md-none d-lg-block">
                    <div>
                        <ul class="list-group side-item">
                            <li class="list-group-item">
                                <a href="../../"><i class="fa-solid fa-house"></i>Home</a>
                            </li>
                            <li class="list-group-item active">
                                <a href="threads.php"><i class="fa-solid fa-brain"></i>Threads</a>
                            </li>

                            <?php 
                            
                                if($_SESSION['role'] =="Admin"){
                                    echo "<li class='list-group-item'>
                                    <a href='../admin/category.php'><i class='fa-solid fa-table-list'></i>Category</a>
                                    </li>";
                                    echo "<li class='list-group-item'>
                                    <a href='../admin/user.php'><i class='fa-solid fa-user'></i>Users</a>
                                    </li>";
                                }
                            ?>
                            <li class="list-group-item">
                                <a href="profile.php"><i class="fa-solid fa-address-card"></i>Profile</a>
                            </li>
                            <li class="list-group-item">
                                <a href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8 center-content">
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-12">
                                <i class="fa-solid fa-magnifying-glass search-thread-title-icon"></i>
                                <input type="text" class="search-thread-title" placeholder="search threads title...">
                            </div>
                            <div class="col-12">
                                <button class="add-thread" data-bs-toggle="modal" data-bs-target="#addThreads">
                                    Create new thread
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="container thread-container" id="thread-display">
                        <div class="row" id="threads-container-data">
                            <?php  $thread->display()?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 d-none d-xs-none d-md-block d-lg-block col-md-4 side-content">
                    <h5>Category:</h5>
                    <div>
                        <i class="fa-solid fa-magnifying-glass s-logo"></i>
                        <input type="text" id="search-category" class="search-category w-100" placeholder="Search category...">
                    </div>
                    <div class="category-container" id="category-container">
                        <?php $category->display("","") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addThreads" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Threads</h5>
                    <button type="button" class="btn-close" id="add-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control input-modal" id="title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control input-modal" id="category">
                            <?php $category->display("dropdown","") ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <div id="editor-container-add-thread">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add-thread" class="add-thread">Post</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editThreads" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Threads</h5>
                    <button type="button" class="btn-close" id="edit-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control input-modal" id="edit-title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control input-modal" id="edit-category">
                            <?php $category->display("dropdown","") ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <div id="editor-container-edit">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="edit-thread" class="add-thread">Update</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../js/jquery.js"></script>
    <script src="../../js/comfirmation.js"></script>
    <script src="../../js/boostrapjs.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="../../js/thread.js"></script>
</body>
</html>