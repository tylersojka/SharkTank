<?php class_exists('View') or exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Accounts</title>
		<link href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/app/static/admin.css" ?>" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="admin">
        <header>
            <h1>Admin Panel</h1>
            <a class="responsive-toggle" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </header>
        <aside class="responsive-width-100 responsive-hidden">
            <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/admin" ?>"><i class="fas fa-users"></i>Accounts</a>
            <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/admin/emailtemplate" ?>"><i class="fas fa-envelope"></i>Email Template</a>
            <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/admin/settings" ?>"><i class="fas fa-tools"></i>Settings</a>
            <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/logout" ?>"><i class="fas fa-sign-out-alt"></i>Log Out</a>
        </aside>
        <main class="responsive-width-100">
            
<h2><?php echo $page ?> Account</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?php echo $account['username'] ?>" required>
        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Password" value="<?php echo $account['password'] ?>" required>
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Email" value="<?php echo $account['email'] ?>" required>
        <label for="activation_code">Activation Code</label>
        <input type="text" id="activation_code" name="activation_code" placeholder="Activation Code" value="<?php echo $account['activation_code'] ?>">
        <label for="rememberme">Remember Me Code</label>
        <input type="text" id="rememberme" name="rememberme" placeholder="Remember Me Code" value="<?php echo $account['rememberme'] ?>">
        <label for="role">Role</label>
        <select id="role" name="role" style="margin-bottom: 30px;">
            <?php foreach ($roles as $role): ?>
            <option value="<?php echo $role ?>"<?php echo $role == $account['role'] ? ' selected' : '' ?>><?php echo $role ?></option>
            <?php endforeach ?>
        </select>
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif ?>
        </div>
    </form>
</div>

        </main>
        <script>
        document.querySelector(".responsive-toggle").onclick = function(event) {
            event.preventDefault();
            var aside_display = document.querySelector("aside").style.display;
            document.querySelector("aside").style.display = aside_display == "flex" ? "none" : "flex";
        };
        </script>
    </body>
</html>





