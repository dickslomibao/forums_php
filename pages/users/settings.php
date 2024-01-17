<?php
require_once "../../classes/user_crud.php";
require_once "../../classes/category_crud.php";
$category = new category;
$user = new user;
$error = "";
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
if($user->validataBan($_SESSION['user_id'])){
	header("Location: ../ban-error.php");
	exit();
}
if (isset($_POST['searchCategory'])) {
    $category->setSearchValue($_POST['value'])->display("search", "");
    exit();
}
if (isset($_POST['change-dp'])) {
    $user =  new user;
    $filename   = uniqid() . "-" . time();
    $extension  = pathinfo($_FILES["userpic"]["name"], PATHINFO_EXTENSION);
    $basename   = $filename . "." . $extension;
    $source       = $_FILES["userpic"]["tmp_name"];
    $destination  = "../../images/profilepicture/{$basename}";
    $allowTypes = array('jpg', 'png', 'jpeg');

    if (in_array($extension, $allowTypes)) {

        if (move_uploaded_file($source, $destination)) {
            $user
                ->setUserPath("images/profilepicture/{$basename}")
                ->operation("updateprofile");

            unlink("../../" . $_SESSION['image']);
            $_SESSION['image'] = "images/profilepicture/{$basename}";
            header("location: settings.php");
        }
    }else{
        $error = "<h6 style='color:red;text-align:center;margin:20px 0 0 0;font-size:13px'>Invalid Profile Picture!</h6>";
    }
}

if (isset($_POST['change-info'])) {
    $execute = false;
    if (md5($_POST['oldpass']) === $_SESSION['password']) {
        if ($_SESSION['username'] != $_POST['user']) {
            if (!$user->validateUsername($_POST['user'])) {
                $error = "<h6 style='color:red;text-align:center;margin:20px 0 0 0;font-size:13px'>Username already used!</h6>";
            } else{
                $execute = true;
            }
        } else {
            $execute = true;
        }
        if ($execute) {
            $user
                ->setUsername($_POST["user"])
                ->setFirstname($_POST['fname'])
                ->setMiddlename($_POST['mname'])
                ->setLastname($_POST['lname'])
                ->setSex($_POST['sex'])
                ->setBirthdate($_POST['birthdate'])
                ->setEmail($_POST['email'])
                ->operation("updateinformation");
            $_SESSION['name'] = $_POST['fname'] . " " . $_POST['lname'];
            $_SESSION['username'] = $_POST['user'];
            header("location: settings.php");
        }
    } else {
        $error = "<h6 style='color:red;text-align:center;margin:20px 0 0 0;font-size:13px'>Invalid password!</h6>";
    }
}
if(isset($_POST['change-pass'])){

    if($_POST['newpass'] === $_POST['comfirmpass']){
        if(md5($_POST['oldpass']) === $_SESSION['password']){
            $user
            ->setPassword(md5($_POST['newpass']))
            ->operation("updatepassword");
            $error = "<h6 style='text-align:center;color:forestgreen;margin:20px 0 0 0'>Successfully updated!</h6>";
        }else{
            $error = "<h6 style='text-align:center;color:red;margin:20px 0 0 0'>Invalid old password!</h6>";
        }   

    }else{
        $error = "<h6 style='text-align:center;color:red;margin:20px 0 0 0'>Password did not match!</h6>";
    }
}

$data = $user
    ->setUserId($_SESSION['user_id'])
    ->displaySingleUser("formodalupdate");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../css/setting.css">
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
                            <li class="nav-item responsive-nav">
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
                            <li class="nav-item responsive-nav active">
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
                                <a class="nav-link " href="../logout.php">
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
                            <li class="list-group-item">
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
                            <li class="list-group-item active">
                                <a href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8 center-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-5">
                                <div class="side-dp">
                                    <div class="dp-change">
                                        <img src="../../<?php echo $_SESSION['image']; ?>" alt="" srcset="">
                                    </div>
                                    <div class="btn-change">
                                        <button class="btn-btn" data-bs-toggle="modal" data-bs-target="#changeProfile">Change Profile</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="personal-info">
                                    <h6><span>Username:</span> <?php echo $data[0] ?></h6>
                                    <h6><span>Firstname:</span> <?php echo $data[2] ?></h6>
                                    <h6><span>Middlename:</span> <?php echo $data[3] ?></h6>
                                    <h6><span>Lastname:</span> <?php echo $data[4] ?></h6>
                                    <h6><span>Sex:</span> <?php echo $data[5] ?></h6>
                                    <h6><span>Birthdate:</span> <?php echo date_format(date_create($data[6]), "F d, Y") ?></h6>
                                    <h6><span>Email:</span> <?php echo $data[7] ?></h6>
                                    <h6><span>Joined Date:</span> <?php echo date_format(date_create($data[8]), "F d, Y") ?></h6>
                                    <?php echo $error ?>
                                    <button class="btn-btn btn-pass" data-bs-toggle="modal" data-bs-target="#changePassword">Change Password</button>
                                    <button class="btn-btn btn-modify" data-bs-toggle="modal" data-bs-target="#changeInfo">Modify Information</button>
                                </div>
                            </div>
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
                        <?php $category->display("", "") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changeProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Profile Picture</h5>
                    <button type="button" class="btn-close" id="add-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="settings.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Select Profile Picture:</label>
                            <input type="file" name="userpic" class="form-control" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-btn" name="change-dp" style="width: 200px;">Update Profile</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" id="add-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="settings.php">
                        <div class="mb-3">
                            <label class="form-label">Old Password:</label>
                            <input type="password" name="oldpass" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password:</label>
                            <input type="password" name="newpass" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comfirm new Password:</label>
                            <input type="password" name="comfirmpass" class="form-control" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-btn" name="change-pass" style="width: 200px;">Update password</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Information</h5>
                    <button type="button" class="btn-close" id="add-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="settings.php">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Firstname:</label>
                                        <input type="text" value="<?php echo $data[2] ?>" required class="form-control" name="fname">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Middlename:</label>
                                        <input type="text" value="<?php echo $data[3] ?>" required class="form-control" name="mname">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Lastname:</label>
                                        <input type="text" value="<?php echo $data[4] ?>" required class="form-control" name="lname">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sex:</label>
                                        <select class="form-select" name="sex" aria-label="Default select example">
                                            <option value="Male" <?php echo $data[5] === 'Male' ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?php echo $data[5] === 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Username:</label>
                                        <input type="text" required class="form-control" value="<?php echo $data[0] ?>" name="user">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email:</label>
                                        <input type="email" required value="<?php echo $data[7] ?>" class="form-control" name="email">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Birthdate:</label>
                                        <input type="date" value="<?php echo $data[6] ?>" required class="form-control" name="birthdate">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Old Password:</label>
                                        <input type="password" required class="form-control" name="oldpass">
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-btn" name="change-info" style="width: 200px;">Update Information</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../js/boostrapjs.js"></script>
    <script src="../../js/jquery.js"></script>
    <script>
        $(document).ready(() => {

            let id = 0;

            $(document).on('keyup', '#search-category', () => {
                $.ajax({
                    url: 'settings.php',
                    method: 'POST',
                    data: {
                        searchCategory: "",
                        value: $("#search-category").val()
                    },
                    success: function(data) {
                        $("#category-container").html(data);
                    }
                });

            });

        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
</head>

<body>

</body>

</html>