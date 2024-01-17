<?php

require_once("../classes/user_crud.php");

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "Admin") {
        header("Location: admin/category.php");
        exit();
    } else {
        header("Location: ../");
        exit();
    }
}
$status = "";
if (isset($_POST['signin'])) {
    $user = new user;
    $user
        ->setUsername($_POST["user"])
        ->setPassword($_POST['pass']);
    $status = "<h6>" . $user->login() . "</h6>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7 col-md-5  d-xs-none  login-side-content">
                <div>
                    <h1>
                        <span class="aspin">Aspin </span><span class="forum">Forum</span><span class="s">s</span>
                    </h1>
                </div>
            </div>
            <div class="col-lg-5 col-md-7 form-content">
                <div>
                    <form action="login.php" method="post">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <h3>Sign in</h3>
                            </div>
                            <div class="col-8">
                                <div class="mb-3">
                                    <label class="form-label">Username:</label>
                                    <input type="text" required class="form-control" name="user">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mb-3">
                                    <label class="form-label">Password:</label>
                                    <input type="password" required class="form-control" name="pass">
                                    <?php

                                    if ($status != "") {
                                        echo $status;
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-8">
                                <div class="mb-3">
                                    <input type="submit" style="margin-top: 15px;" name="signin" class="signup-btn w-100" value="Login">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mb-3">
                                    <a href="users/registration.php">
                                        <div class="signup">
                                            Sign up
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>