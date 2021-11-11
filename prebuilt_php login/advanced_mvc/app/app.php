<?php
$app = new App([
	'db' => new db(db_host, db_user, db_pass, db_name)
]);

$app->route->get('/', 'Pages@login');
$app->route->get('/login', 'Pages@login');
$app->route->get('/logout', 'Pages@logout');
$app->route->get('/register', 'Pages@register');
$app->route->get('/home', 'Pages@home');
$app->route->get('/profile', 'Pages@profile');
$app->route->get('/profile/edit', 'Pages@profile_edit');
$app->route->get('/forgotpassword', 'Pages@forgot_password');
$app->route->get('/resetpassword/{email}/{code}', 'Pages@reset_password');
$app->route->get('/activate/{email}/{code}', 'Pages@activate');
$app->route->get('/resendactivation', 'Pages@resend_activation');

$app->route->post('/register_process', 'Pages@register_process');
$app->route->post('/authenticate', 'Pages@authenticate');
$app->route->post('/profile/edit', 'Pages@profile_edit');
$app->route->post('/forgotpassword', 'Pages@forgot_password');
$app->route->post('/resetpassword/{email}/{code}', 'Pages@reset_password');
$app->route->post('/resendactivation', 'Pages@resend_activation');

$app->route->get('/admin', 'Pages@admin_accounts');
$app->route->get('/admin/account', 'Pages@admin_account');
$app->route->get('/admin/account/{id}', 'Pages@admin_account');
$app->route->get('/admin/emailtemplate', 'Pages@admin_emailtemplate');
$app->route->get('/admin/settings', 'Pages@admin_settings');

$app->route->post('/admin/account', 'Pages@admin_account');
$app->route->post('/admin/account/{id}', 'Pages@admin_account');
$app->route->post('/admin/emailtemplate', 'Pages@admin_emailtemplate');
$app->route->post('/admin/settings', 'Pages@admin_settings');
?>
