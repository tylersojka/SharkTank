<?php class_exists('View') or exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Resend Activation Email</title>
		<link href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/app/static/style.css" ?>" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		
<div class="login">
	<h1>Resend Activation Email</h1>
	<form action="" method="post">
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





