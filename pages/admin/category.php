<?php
require_once "../../classes/category_crud.php";
$category = new category;

if (!isset($_SESSION['role'])) {
	header("Location: ../login.php");
	exit();
} else {
	if ($_SESSION['role'] != "Admin") {
		header("Location: ../../");
		exit();
	}
}

if (isset($_POST["display"])) {

	$category->display("","Admin");
	exit();
}

if (isset($_POST["add"])) {

	$category
		->setTitle(trim($_POST['title']))
		->setDescription(trim($_POST['description']))
		->operation('add');
	exit();
}

if (isset($_POST["getsingledata"])) {

	$category->getSingleData($_POST['id']);
	exit();
}

if (isset($_POST["update"])) {

	$category
		->setId($_POST['id'])
		->setTitle(trim($_POST['title']))
		->setDescription(trim($_POST['description']))
		->operation('update');
	exit();
}
if (isset($_POST["delete"])) {

	$category
		->setId($_POST['id'])
		->operation('delete');
	exit();
}

if (isset($_POST['searchCategory'])) {
	$category->setSearchValue($_POST['value'])->display("search","Admin");
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Category</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../../css/fontawesome/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="../../css/index.css">
	<link rel="stylesheet" href="../../css/category.css">
</head>

<body>
	<div style="width: 100%;box-shadow: 1px 5px 10px rgba(0,0,0,.1)">
		<div class="body-content">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="container-fluid" style="padding-left: 60px;">
					<a class="navbar-brand" href="../../" style="font-weight: 600;">
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
								<a class='nav-link' href="../users/threads.php"><i class="fa-solid fa-brain"></i>Threads</a>
							</li>
							<li class="nav-item responsive-nav active">
								<a class='nav-link' href="category.php"><i class='fa-solid fa-table-list'></i>Category</a>
							</li>
							<li class="nav-item responsive-nav ">
								<a class='nav-link' href="user.php"><i class="fa-solid fa-user"></i>Users</a>
							</li>
							<li class="nav-item responsive-nav">
								<a class='nav-link' href="../users/profile.php"><i class="fa-solid fa-address-card"></i>Profile</a>
							</li>
							<li class="nav-item responsive-nav">
								<a class='nav-link' href="../users/settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
							</li>
							<li class="nav-item">
								<a class="nav-link username" aria-current="page" href="../users/profile.php">
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
								<a class="nav-link" aria-current="page" href="../logout.php">
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
								<a href="../users/threads.php"><i class="fa-solid fa-brain"></i>Threads</a>
							</li>
							<li class="list-group-item active">
								<a href="category.php"><i class='fa-solid fa-table-list'></i>Category</a>
							</li>
							<li class="list-group-item ">
								<a href="user.php"><i class="fa-solid fa-user"></i>Users</a>
							</li>
							<li class="list-group-item">
								<a href="../users/profile.php"><i class="fa-solid fa-address-card"></i>Profile</a>
							</li>
							<li class="list-group-item">
								<a href="../users/settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-9 col-md-12">
					<div class="row" style="margin-bottom: 10px;padding-left: 25px;">
						<div class="col-lg-7 col-md-12">
							<i class="fa-solid fa-magnifying-glass search-icon"></i>
							<input placeholder="Search category..." id="search-category" type="text" class="w-100 input-search">
						</div>
						<div class="col-lg-4 col-md-12 order-first">
							<button class="btn-add new-category-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
								<i class="fa-solid fa-plus"></i> Create new category
							</button>
						</div>
					</div>
					<div class="list-item-content">
						<div class="row" style="margin-top: 20px;" id="category-container">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- modal for add -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Category</h5>
					<button type="button" class="btn-close" id="add-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Title</label>
						<input type="text" class="form-control input-modal" id="add-title">
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea class="form-control w-100 input-modal" rows="6" id="add-description"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-id="1123" id="add-button" class="btn-add-modal">Add</button>
				</div>
			</div>
		</div>
	</div>
	<!-- modal for update -->
	<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Category</h5>
					<button type="button" class="btn-close" id="update-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Title</label>
						<input type="text" class="form-control input-modal" id="update-title">
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea class="form-control w-100 input-modal" rows="6" id="update-description"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="update-button" class="btn-add-modal">Update</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Delete Category</h5>
					<button type="button" class="btn-close" id="delete-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<center>
						<i class="fa-solid fa-circle-xmark"></i>
						<h2>Are you sure you <br>want to delete this?</h2>
						<button class="delete-btn-y" id="delete">Yes</button>
						<button class="delete-btn-n" data-bs-dismiss="modal">No</button>
					</center>
				</div>
			</div>
		</div>
	</div>

	<script src="../../js/boostrapjs.js"></script>
    <script src="../../js/jquery.js"></script>
	<script src="../../js/category.js"></script>
	<script src="../../js/comfirmation.js"></script>
</body>

</html>