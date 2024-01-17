<?php 

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: users/login.php");
}
if(!isset($_SESSION['error']) || $_SESSION['error'] == ""){
    header("Location: users/threads.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <title>Error</title>
    <style>
        * {
            text-align: center;
        }
        h1{
            color:forestgreen
        }
        p{
            color:white;
            font-size: 18px;
        }

        body {
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div>
        <h1>Something went wrong! Try to repeat the process again. </h1>
        <?php  unset($_SESSION['error'])?>
    </div>
</body>
</html>