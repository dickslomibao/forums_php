<?php


require_once("../../classes/user_crud.php");

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == "Admin") {
        header("Location: ../admin/category.php");
        exit();
    } else {
        header("Location: ../../");
        exit();
    }
}
$error = "";
if (isset($_POST['signup'])) {
    $user =  new user;
    if ($user->validateUsername($_POST['user'])) {

        $filename   = uniqid() . "-" . time();
        $extension  = pathinfo($_FILES["userpic"]["name"], PATHINFO_EXTENSION);
        $basename   = $filename . "." . $extension;
        $source       = $_FILES["userpic"]["tmp_name"];
        $destination  = "../../images/profilepicture/{$basename}";
        $allowTypes = array('jpg', 'png', 'jpeg');

        if (in_array($extension, $allowTypes)) {

            if (move_uploaded_file($source, $destination)) {
                $user
                    ->setUsername($_POST["user"])
                    ->setPassword($_POST['pass'])
                    ->setFirstname($_POST['fname'])
                    ->setMiddlename($_POST['mname'])
                    ->setLastname($_POST['lname'])
                    ->setSex($_POST['sex'])
                    ->setBirthdate($_REQUEST['birthdate'])
                    ->setEmail($_REQUEST['email'])
                    ->setUserPath("images/profilepicture/{$basename}")
                    ->operation("add");

                header("Location: ../login.php");
            }
        }else{
            $error = "<h6 style='color:red;text-align:center;margin:5px 0 10px 0;font-size:13px'>Invalid profile picture!</h6>";
        }
    } else {
        $error = "<h6 style='color:red;text-align:center;margin:5px 0 10px 0;font-size:13px'>Username already used!</h6>";
    }
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
    <link rel="stylesheet" href="../../css/login.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7 login-side-content">
                <div>
                    <h1>
                        <span class="aspin">Aspin </span><span class="forum">Forum</span><span class="s">s</span>
                    </h1>
                </div>
            </div>
            <div class="col-lg-5 form-content">
                <div class="container-fluid">
                    <h3>Sign up</h3>
                    <form action="registration.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Firstname:</label>
                                    <input type="text" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : ""  ?>" required class="form-control" name="fname">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Middlename:</label>
                                    <input type="text" value="<?php echo isset($_POST['mname']) ? $_POST['mname'] : ""  ?>" required class="form-control" name="mname">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Lastname:</label>
                                    <input type="text" required class="form-control" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : ""  ?>" name="lname">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Sex:</label>
                                    <select class="form-select" name="sex" aria-label="Default select example">
                                        <option value="Male" <?php if (isset($_POST['sex'])) echo  $_POST['sex'] == 'Male' ? 'Selected' : ""  ?>>Male</option>
                                        <option value="Female" <?php if (isset($_POST['sex'])) echo $_POST['sex'] == 'Female' ? 'Selected' : ""  ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Username:</label>
                                    <input type="text" value="<?php echo isset($_POST['user']) ? $_POST['user'] : ""  ?>" required class="form-control" name="user">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Password:</label>
                                    <input type="password" value="<?php echo isset($_POST['pass']) ? $_POST['pass'] : ""  ?>" required class="form-control" name="pass">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ""  ?>" required class="form-control" name="email">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Birthdate:</label>
                                    <input type="date" required class="form-control" value="<?php echo isset($_POST['birthdate']) ? $_POST['birthdate'] : ""  ?>" name="birthdate">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Picture:</label>
                            <input type="file" value="<?php echo isset($_FILES['userpic']["tmp_name"]) ?  fopen($_FILES['userpic']["tmp_name"], 'r') : ""  ?>" required class="form-control" name="userpic" required value="">
                        </div>
                        <div class="row">
                            <div class="col-12" style="padding-top: 10px;">
                                <?php echo $error ?>
                                <center>
                                    <a href="../login.php">Already have an account?</a>
                                </center>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <input type="submit" name="signup" class="signup-btn w-100" value="Signup">
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