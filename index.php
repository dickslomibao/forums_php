<?php

require_once "classes/category_crud.php";
require_once "classes/user_crud.php";
require_once "classes/threads_crud.php";
$thread = new thread;
$category = new category;
$user = new user;

if (!isset($_SESSION['role'])) {
	header("Location: pages/login.php");
	exit();
}
if ($user->validataBan($_SESSION['user_id'])) {
	header("Location: pages/ban-error.php");
	exit();
}

if (isset($_POST['searchCategory'])) {
	$category->setSearchValue($_POST['value'])->display("search", "");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Aspin Forums</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="css/threads.css">
</head>

<body>

	<div style="width: 100%;box-shadow: 1px 5px 10px rgba(0,0,0,.1)">
		<div class="body-content">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="container-fluid" style="padding-left: 60px;">
					<a class="navbar-brand" href="" style="font-weight: 500;">
						<span class="logo-aspin"><sup>A</sup> spin</span><span class="logo-f">Forum</span> <sup><span class="logo-s">s</span></sup>
					</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
							<li class="nav-item responsive-nav active">
								<a class='nav-link' href=""><i class="fa-solid fa-house"></i>Home</a>
							</li>
							<li class="nav-item responsive-nav">
								<a class='nav-link' href="pages/users/threads.php"><i class="fa-solid fa-brain"></i>Threads</a>
							</li>
							<?php 
                            
                                if($_SESSION['role'] =="Admin"){
                                    echo "<li class='nav-item responsive-nav'>
                                    <a class='nav-link' href='pages/admin/category.php'><i class='fa-solid fa-table-list'></i>Category</a>
                                    </li>";
                                    echo "<li class='nav-item responsive-nav'>
                                    <a class='nav-link' href='pages/admin/user.php'><i class='fa-solid fa-user'></i>Users</a>
                                    </li>";
                                }
                            ?>
							<li class="nav-item responsive-nav">
								<a class='nav-link' href="pages/users/profile.php"><i class="fa-solid fa-address-card"></i>Profile</a>
							</li>
							<li class="nav-item responsive-nav">
								<a class='nav-link' href="pages/users/settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
							</li>

							<li class="nav-item">
								<a class="nav-link username" href="pages/users/profile.php">
									<div style="display: flex;justify-content:between;align-items:center">
										<div class="profile-pic">
											<img src="<?php echo $_SESSION['image'] ?>" alt="" srcset="">
										</div>
										<div style="margin-left:10px">
											<?php echo $_SESSION['name'] ?>
										</div>
									</div>
								</a>
							</li>
							<li class="nav-item logout" style="display: grid;justify-content:center;align-items:center">
								<a class="nav-link" href="pages/logout.php">
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
					<div >
						<ul class="list-group side-item">
							<li class="list-group-item active">
								<a href=""><i class="fa-solid fa-house"></i>Home</a>
							</li>
							<li class="list-group-item">
								<a href="pages/users/threads.php"><i class="fa-solid fa-brain"></i>Threads</a>
							</li>
							<?php 
                            
                                if($_SESSION['role'] =="Admin"){
                                    echo "<li class='list-group-item'>
                                    <a href='pages/admin/category.php'><i class='fa-solid fa-table-list'></i>Category</a>
                                    </li>";
                                    echo "<li class='list-group-item'>
                                    <a href='pages/admin/user.php'><i class='fa-solid fa-user'></i>Users</a>
                                    </li>";
                                }
                            ?>
							<li class="list-group-item">
								<a href="pages/users/profile.php"><i class="fa-solid fa-address-card"></i>Profile</a>
							</li>
							<li class="list-group-item">
								<a href="pages/users/settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="col-lg-6 col-md-8 center-content content-profile">
					<div class="container-fluid">

						<div class="row">
							<div class="col-12">
								<div class="box-main">
									<p>
										Aspin or Association of Pinoys, the #1 online based forum used as a platform for the widespread growth of Pinoys to interact with their co-Pinoys and to other countries' mainland. Aspin Forums help netizens to exchange their opinions and ideas with a particular topic through online conversation and threads, aims for the locals to explore in the world of endless thoughts.
									</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<div class="box-home">
									<h1><?php echo $thread->totalThreads() ?></h1>
									<p>Total Thread</p>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="box-home">
									<h1><?php echo $category->totalCategory() ?></h1>
									<p>Total Category</p>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="box-home">
									<h1><?php echo $user->totalUser() ?></h1>
									<p>Total User</p>
								</div>
							</div>
						</div>
						<div class="row">
							<h5 class="latest-post">Latest Thread:</h5>
							<?php echo $thread->getLatestorPopular("") ?>
							<h5 class="latest-post">Popular Thread:</h5>
							<?php echo $thread->getLatestorPopular("popular") ?>
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
	<script src="js/boostrapjs.js"></script>
	<script src="js/jquery.js"></script>
	<script>
		$(document).ready(() => {


			$(document).on('keyup', '#search-category', () => {
				$.ajax({
					url: 'index.php',
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