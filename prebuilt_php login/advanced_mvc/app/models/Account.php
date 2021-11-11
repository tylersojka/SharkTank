<?php

class Account {

    private $controller;
    private $app;
    private $db;
    private $info;

    function __construct($controller) {
        $this->controller = $controller;
        $this->app = $controller->app;
        $this->db = $controller->db;
    }

	function authenticate($username, $password, $remember, $token) {
        if (brute_force_protection) {
            $login_attempts = $this->login_attempts(FALSE);
            if ($login_attempts && $login_attempts['attempts_left'] <= 0) {
            	return 'You cannot login right now please try again later!';
            }
        }
        if (csrf_protection && (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token'])) {
        	exit('Incorrect token provided!');
        }
        if (empty($username) || empty($password)) {
        	return 'Please fill both the username and password field!';
        }
        $account = $this->db->query('SELECT * FROM accounts WHERE username = ?', $username)->fetchArray();
        if ($account) {
        	if (password_verify($password, $account['password'])) {
        		if (account_activation && $account['activation_code'] != 'activated') {
        			return 'Please activate your account to login, click <a href="' . App::root_url()  . '/resendactivation">here</a> to resend the activation email!';
        		} else {
                    $this->controller->session_start();
        			$this->controller->session_regenerate_id();
        			$this->controller->session('loggedin', TRUE);
        			$this->controller->session('name', $account['username']);
        			$this->controller->session('id', $account['id']);
        			$this->controller->session('role', $account['role']);
                    if ($remember) {
        				$cookiehash = !empty($account['rememberme']) ? $account['rememberme'] : password_hash($account['id'] . $account['username'] . 'yoursecretkey', PASSWORD_DEFAULT);
        				$days = 30;
        				setcookie('rememberme', $cookiehash, (int)(time()+60*60*24*$days));
        				$this->db->query('UPDATE accounts SET rememberme = ? WHERE id = ?', $cookiehash, $account['id']);
        			}
        			return 'Success';
        		}
        	} else {
                if (brute_force_protection) {
                    $login_attempts = $this->login_attempts(TRUE);
                    return 'Incorrect username and/or password, you have ' . $login_attempts['attempts_left'] . ' attempts remaining!';
                }
        		return 'Incorrect username and/or password!';
        	}
        }
        if (brute_force_protection) {
            $login_attempts = $this->login_attempts(TRUE);
            return 'Incorrect username and/or password, you have ' . $login_attempts['attempts_left'] . ' attempts remaining!';
        }
        return 'Incorrect username and/or password!';
	}

    function register($username, $password, $cpassword, $email) {
        if (!isset($username, $password, $cpassword, $email)) {
        	return 'Please complete the registration form!';
        }
        if (empty($username) || empty($password) || empty($email)) {
        	return 'Please complete the registration form';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        	return 'Email is not valid!';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            return 'Username is not valid!';
        }
        if (strlen($password) > 20 || strlen($password) < 5) {
        	return 'Password must be between 5 and 20 characters long!';
        }
        if ($cpassword != $password) {
        	return 'Passwords do not match!';
        }
        $account = $this->db->query('SELECT id, password FROM accounts WHERE username = ? OR email = ?', $username, $email)->fetchArray();
        if ($account) {
        	return 'Username and/or email exists!';
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        	$uniqid = account_activation ? uniqid() : 'activated';
        	$this->db->query('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)', $username, $password, $email, $uniqid);
        	if (account_activation) {
        		$this->send_activation_email($email, $uniqid);
        		return 'Please check your email to activate your account!';
        	} else {
        		return 'You have successfully registered, you can now login!';
        	}
        }
	}

    function is_loggedin() {
        if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme']) && !$this->controller->session('loggedin')) {
        	$account = $this->db->query('SELECT * FROM accounts WHERE rememberme = ?', $_COOKIE['rememberme'])->fetchArray();
        	if ($account) {
                $this->controller->session_start();
                $this->controller->session_regenerate_id();
                $this->controller->session('loggedin', TRUE);
                $this->controller->session('name', $account['username']);
                $this->controller->session('id', $account['id']);
                $this->controller->session('role', $account['role']);
        	} else {
        		return FALSE;
        	}
        } else if (!$this->controller->session('loggedin')) {
        	return FALSE;
        }
        return TRUE;
    }

    function send_activation_email($email, $code) {
        $subject = 'Account Activation Required';
    	$headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    	$activate_link = App::root_url() . '/activate/' . $email . '/' . $code;
    	$email_template = str_replace('%link%', $activate_link, file_get_contents('app/views/activation-email-template.html'));
    	mail($email, $subject, $email_template, $headers);
    }

    function info($column) {
        if (!$this->info) {
            $this->info = $this->db->query('SELECT * FROM accounts WHERE id = ?', $this->controller->session('id'))->fetchArray();
        }
        return $this->info[$column];
    }

    function update_info($username, $password, $cpassword, $email) {
        if (empty($username) || empty($email)) {
            return 'The input fields must not be empty!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please provide a valid email address!';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            return 'Username must contain only letters and numbers!';
        }
        if (!empty($password) && (strlen($password) > 20 || strlen($password) < 5)) {
            return 'Password must be between 5 and 20 characters long!';
        }
        if ($cpassword != $password) {
            return 'Passwords do not match!';
        }
        $account = $this->db->query('SELECT * FROM accounts WHERE (username = ? OR email = ?) AND username != ? AND email != ?', $username, $email, $this->info('username'), $this->info('email'))->fetchArray();
        if ($account) {
            return 'Account already exists with that username and/or email!';
        }
        $uniqid = account_activation && $this->info('email') != $email ? uniqid() : $this->info('activation_code');
        $password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $this->info('password');
        $this->db->query('UPDATE accounts SET username = ?, password = ?, email = ?, activation_code = ? WHERE id = ?', $username, $password, $email, $uniqid, $this->info('id'));
        $this->controller->session('name', $username);
        if (account_activation && $this->info('email') != $email) {
            $this->send_activation_email($email, $uniqid);
            unset($_SESSION['loggedin']);
            return 'You have changed your email address, you need to re-activate your account!';
        }
        return 'Success';
    }

    function activate($email, $code) {
        $account = $this->db->query('SELECT * FROM accounts WHERE email = ? AND activation_code = ?', $email, $code)->fetchArray();
    	if ($account) {
    		$this->db->query('UPDATE accounts SET activation_code = "activated" WHERE email = ? AND activation_code = ?', $email, $code);
    		return TRUE;
    	}
    	return FALSE;
    }

    function forgot_password($email) {
        $account = $this->db->query('SELECT * FROM accounts WHERE email = ?', $email)->fetchArray();
        if ($account) {
            $uniqid = uniqid();
            $this->db->query('UPDATE accounts SET reset = ? WHERE email = ?', $uniqid, $email);
        	$subject = 'Password Reset';
        	$headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            $reset_link = App::root_url() . '/resetpassword/' . $email . '/' . $uniqid;
        	$message = '<p>Please click the following link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a></p>';
        	mail($email, $subject, $message, $headers);
            return 'Reset password link has been sent to your email!';
        }
        return 'Account does not exist with that email!';
    }

    function reset_password($email, $code, $npass, $cpass) {
        $account = $this->db->query('SELECT * FROM accounts WHERE email = ? AND reset = ?', $email, $code)->fetchArray();
        if ($account) {
            if (isset($npass, $cpass)) {
                if (strlen($npass) > 20 || strlen($npass) < 5) {
                	return 'Password must be between 5 and 20 characters long!';
                } else if ($npass != $cpass) {
                    return 'Passwords must match!';
                } else {
                    $password = password_hash($npass, PASSWORD_DEFAULT);
                    $this->db->query('UPDATE accounts SET password = ?, reset = "" WHERE email = ?', $password, $email);
                    return 'Password has been reset! You can now <a href="' . App::root_url() . '/">login</a>!';
                }
            }
        }
        return 'Incorrect email and/or code!';
    }

    function resend_activation($email) {
        $account = $this->db->query('SELECT * FROM accounts WHERE email = ? AND activation_code != "" AND activation_code != "activated"', $email)->fetchArray();
        if ($account) {
            $this->send_activation_email($email, $account['activation_code']);
            return 'Activaton link has been sent to your email!';
        } else {
            return 'We do not have an account with that email!';
        }
    }


	function login_attempts($update = TRUE) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$now = date('Y-m-d H:i:s');
		if ($update) {
			$this->db->query('INSERT INTO login_attempts (ip_address, `date`) VALUES (?,?) ON DUPLICATE KEY UPDATE attempts_left = attempts_left - 1, `date` = VALUES(`date`)', $ip, $now);
		}
		$login_attempts = $this->db->query('SELECT * FROM login_attempts WHERE ip_address = ?', $ip)->fetchArray();
		if ($login_attempts) {
			$expire = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($login_attempts['date'])));
			if ($now > $expire) {
				$this->db->query('DELETE FROM login_attempts WHERE ip_address = ?', $ip);
				$login_attempts = [];
			}
		}
		return $login_attempts;
	}

}

?>
