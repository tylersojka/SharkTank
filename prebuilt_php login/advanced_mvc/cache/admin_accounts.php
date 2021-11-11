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
            
<h2>Accounts</h2>

<div class="links">
    <a href="<?php echo "http://localhost/projects/phplogin/advanced_mvc/admin/account" ?>">Create Account</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td class="responsive-hidden">Password</td>
                    <td class="responsive-hidden">Email</td>
                    <td class="responsive-hidden">Activation Code</td>
                    <td class="responsive-hidden">Role</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accounts)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php foreach ($accounts as $account): ?>
                <tr class="details" onclick="location.href='<?php echo "http://localhost/projects/phplogin/advanced_mvc/" ?>admin/account/<?php echo $account['id'] ?>'">
                    <td><?php echo $account['id'] ?></td>
                    <td><?php echo $account['username'] ?></td>
                    <td class="responsive-hidden" style="word-break:break-all;"><?php echo $account['password'] ?></td>
                    <td class="responsive-hidden"><?php echo $account['email'] ?></td>
                    <td class="responsive-hidden"><?php echo $account['activation_code'] ?></td>
                    <td class="responsive-hidden"><?php echo $account['role'] ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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





