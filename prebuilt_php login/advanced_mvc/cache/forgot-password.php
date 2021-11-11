<?php class_exists('View') or exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Forgot Password</title>
		<link href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/app/static/style.css" ?>" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		
<div class="login">
    <h1>Forgot Password</h1>
    <div class="links">
        <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/forgotpassword" ?>" class="active">Forgot Password</a>
        <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/login" ?>">Login</a>
    </div>
    <form action="<?php echo "http://localhost/projects/phplogin/advanced_mvc/forgotpassword" ?>" method="post">
        <label for="email">
            <i class="fas fa-envelope"></i>
        </label>
        <input type="email" name="email" placeholder="Your Email" id="email" required>
        <div class="msg"><?php echo $msg ?></div>
        <input type="submit" value="Submit">
    </form>
</div>

		
	</body>
</html>





