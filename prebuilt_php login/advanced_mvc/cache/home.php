<?php class_exists('View') or exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Home</title>
		<link href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/app/static/style.css" ?>" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/home" ?>"><i class="fas fa-home"></i>Home</a>
				<a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/profile" ?>"><i class="fas fa-user-circle"></i>Profile</a>
				<?php if ($role == 'Admin'): ?>
				<a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/admin" ?>" target="_blank"><i class="fas fa-user-cog"></i>Admin</a>
				<?php endif ?>
				<a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/logout" ?>"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
        
<div class="content">
    <h2>Home Page</h2>
    <p class="block">Welcome back, <?php echo $name ?>!</p>
</div>

	</body>
</html>





