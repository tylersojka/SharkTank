<?php

class Pages extends Controller {

	private $account;
	private $admin;

	function init() {
		$this->account = new Account($this);
	}

	function login() {
		if ($this->account->is_loggedin()) {
			App::redirect('home');
		}
		$this->session('token', md5(uniqid(rand(), true)));
		$this->view('login.html', [
			'token' => $this->session('token')
		]);
	}

	function authenticate() {
		return $this->account->authenticate($this->post('username'), $this->post('password'), $this->post('rememberme'), $this->session('token'));
	}

	function register() {
		if ($this->account->is_loggedin()) {
			App::redirect('home');
		}
		$this->view('register.html');
	}

	function register_process() {
		return $this->account->register($this->post('username'), $this->post('password'), $this->post('cpassword'), $this->post('email'));
	}

	function home() {
		if (!$this->account->is_loggedin()) {
			App::redirect('login');
		}
		$this->view('home.html', [
			'role' => $this->session('role'),
			'name' => $this->session('name')
		]);
	}

	function profile() {
		if (!$this->account->is_loggedin()) {
			App::redirect('login');
		}
		$this->view('profile.html', [
			'role' => $this->session('role'),
			'account' => $this->account
		]);
	}

	function profile_edit() {
		if (!$this->account->is_loggedin()) {
			App::redirect('login');
		}
		$msg = '';
		if ($this->post('save')) {
			$msg = $this->account->update_info($this->post('username'), $this->post('password'), $this->post('cpassword'), $this->post('email'));
			if ($msg == 'Success') {
				App::redirect('profile');
			}
		}
		$this->view('edit-profile.html', [
			'role' => $this->session('role'),
			'account' => $this->account,
			'msg' => $msg
		]);
	}

	function activate($email, $code) {
		if ($this->account->is_loggedin()) {
			App::redirect('home');
		}
		if (!empty($email) && !empty($code)) {
			$activated = $this->account->activate($email, $code);
			$this->view('activate.html', [
				'activated' => $activated
			]);
		}
	}

	function forgot_password() {
		if ($this->account->is_loggedin()) {
			App::redirect('home');
		}
		$msg = '';
		if ($this->post('email')) {
			$msg = $this->account->forgot_password($this->post('email'));
		}
		$this->view('forgot-password.html', [
			'msg' => $msg
		]);
	}

	function reset_password($email, $code) {
		if ($this->account->is_loggedin()) {
			App::redirect('home');
		}
		if ($email && $code) {
			$msg = '';
			if ($this->post('npassword') && $this->post('cpassword')) {
				$msg = $this->account->reset_password($email, $code, $this->post('npassword'), $this->post('cpassword'));
			}
			$this->view('reset-password.html', [
				'msg' => $msg,
				'email' => $email,
				'code' => $code
			]);
		}
	}

	function resend_activation() {
		if ($this->account->is_loggedin()) {
			App::redirect('home');
		}
		$msg = '';
		if ($this->post('email')) {
			$msg = $this->account->resend_activation($this->post('email'));
		}
		$this->view('resend-activation.html', [
			'msg' => $msg
		]);
	}

	function admin_init() {
		if (!$this->account->is_loggedin()) {
			App::redirect('login');
		}
		if ($this->session('role') != 'Admin') {
			App::redirect('home');
		}
		$this->admin = new Admin($this);
	}

	function admin_accounts() {
		$this->admin_init();
		$this->view('admin/accounts.html', [
			'accounts' => $this->admin->get_accounts()
		]);
	}

	function admin_account($id = NULL) {
		$this->admin_init();
		$page = 'Create';
		if ($id) {
			$account = $this->admin->get_account($id);
			$page = 'Edit';
			if ($this->post('submit')) {
				$this->admin->update_account($id, $this->post('username'), $this->post('password'), $this->post('email'), $this->post('activation_code'), $this->post('rememberme'), $this->post('role'));
				App::redirect('admin');
			}
			if ($this->post('delete')) {
				$this->admin->delete_account($id);
				App::redirect('admin');
			}
		} else {
			$account = $this->admin->get_account();
			if ($this->post('submit')) {
				$this->admin->create_account($this->post('username'), $this->post('password'), $this->post('email'), $this->post('activation_code'), $this->post('rememberme'), $this->post('role'));
				App::redirect('admin');
			}
		}
		$this->view('admin/account.html', [
			'account' => $account,
			'page' => $page,
			'roles' => ['Member', 'Admin']
		]);
	}

	function admin_emailtemplate() {
		$this->admin_init();
		if ($this->post('emailtemplate')) {
            file_put_contents('app/views/activation-email-template.html', $this->post('emailtemplate'));
        }
        $contents = file_get_contents('app/views/activation-email-template.html');
		$this->view('admin/emailtemplate.html', [
			'contents' => $contents
		]);
	}

	function admin_settings() {
		$this->admin_init();
		$config_file = file_get_contents('config.php');
		preg_match_all('/define\(\'(.*?)\', ?(.*?)\)/', $config_file, $matches);
		if (!empty($this->post())) {
		    foreach ($this->post() as $k => $v) {
		        $v = in_array(strtolower($v), ['true', 'false']) ? strtolower($v) : '\'' . $v . '\'';
		        $config_file = preg_replace('/define\(\'' . $k . '\'\, ?(.*?)\)/s', 'define(\'' . $k . '\',' . $v . ')', $config_file);
		    }
		    file_put_contents('config.php', $config_file);
		    App::redirect('admin/settings');
		}
		$this->view('admin/settings.html', [
			'matches' => $matches
		]);
	}

	function logout() {
		session_start();
		session_destroy();
		if (isset($_COOKIE['rememberme'])) {
		    unset($_COOKIE['rememberme']);
		    setcookie('rememberme', '', time() - 3600);
		}
		App::redirect('login');
	}

}

?>
