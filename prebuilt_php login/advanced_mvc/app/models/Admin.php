<?php

class Admin {

    private $controller;
    private $app;
    private $db;

    function __construct($controller) {
        $this->controller = $controller;
        $this->app = $controller->app;
        $this->db = $controller->db;
    }

    function get_accounts() {
        return $this->db->query('SELECT * FROM accounts')->fetchAll();
    }

    function get_account($id = NULL) {
        $account = [
            'username' => '',
            'password' => '',
            'email' => '',
            'activation_code' => '',
            'rememberme' => '',
            'role' => 'Member'
        ];
        if ($id) {
            $account = $this->db->query('SELECT * FROM accounts WHERE id = ?', $id)->fetchArray();
        }
        return $account;
    }

    function update_account($id, $username, $password, $email, $activation_code, $rememberme, $role) {
        $account = $this->db->query('SELECT password FROM accounts WHERE id = ?', $id)->fetchArray();
        $password = $account['password'] != $password ? password_hash($password, PASSWORD_DEFAULT) : $account['password'];
        $this->db->query('UPDATE accounts SET username = ?, password = ?, email = ?, activation_code = ?, rememberme = ?, role = ? WHERE id = ?', $username, $password, $email, $activation_code, $rememberme, $role, $id);
    }

    function create_account($username, $password, $email, $activation_code, $rememberme, $role) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->db->query('INSERT INTO accounts (username,password,email,activation_code,rememberme,role) VALUES (?,?,?,?,?,?)', $username, $password, $email, $activation_code, $rememberme, $role);
    }

    function delete_account($id) {
        $this->db->query('DELETE FROM accounts WHERE id = ?', $id);
    }

    static function format_key($key) {
        $key = str_replace(['_', 'url', 'db ', 'csrf'], [' ', 'URL', 'Database ', 'CSRF'], strtolower($key));
        return ucwords($key);
    }

    static function format_var_html($key, $value) {
        if ($key == 'cache_path') {
            return;
        }
        $html = '';
        $type = 'text';
        $value = htmlspecialchars(trim($value, '\''), ENT_QUOTES);
        $type = strpos($key, 'pass') !== false ? 'password' : $type;
        $type = in_array(strtolower($value), ['true', 'false']) ? 'checkbox' : $type;
        $checked = strtolower($value) == 'true' ? ' checked' : '';
        $html .= '<label for="' . $key . '">' . self::format_key($key) . '</label>';
        if ($type == 'checkbox') {
            $html .= '<input type="hidden" name="' . $key . '" value="false">';
        }
        $html .= '<input type="' . $type . '" name="' . $key . '" id="' . $key . '" value="' . $value . '" placeholder="' . self::format_key($key) . '"' . $checked . '>';
        return $html;
    }

}

?>
