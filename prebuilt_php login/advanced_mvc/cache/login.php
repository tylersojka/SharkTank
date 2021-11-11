<?php class_exists('View') or exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Login</title>
		<link href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/app/static/style.css" ?>" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		
<div class="login">
    <h1>Login</h1>
    <div class="links">
        <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/login" ?>" class="active">Login</a>
        <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/register" ?>">Register</a>
    </div>
    <form action="<?php echo "http://localhost/projects/phplogin/advanced_mvc/authenticate" ?>" method="post">
        <input type="hidden" name="token" value="<?php echo $token ?>">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Password" id="password" required>
        <label id="rememberme">
            <input type="checkbox" name="rememberme">Remember me
        </label>
        <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/forgotpassword" ?>">Forgot Password?</a>
        <div class="msg"></div>
        <input type="submit" value="Login">
    </form>
</div>

		
<script>
document.querySelector(".login form").onsubmit = function(event) {
    event.preventDefault();
    var form_data = new FormData(document.querySelector(".login form"));
    var xhr = new XMLHttpRequest();
    xhr.open("POST", document.querySelector(".login form").action, true);
    xhr.onload = function () {
        if (this.responseText.toLowerCase().indexOf("success") !== -1) {
            window.location.href = "home";
        } else {
            document.querySelector(".msg").innerHTML = this.responseText;
        }
    };
    xhr.send(form_data);
};
</script>

	</body>
</html>







