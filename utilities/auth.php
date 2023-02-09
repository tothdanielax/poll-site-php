<?php
require_once "storage.php";

class Auth
{
    private $users;

    public function __construct()
    {
        $this->users = new Storage(new JsonIO("../data/users.json"));
    }

    public function register($user)
    {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        return $this->users->add((object) $user);
    }

    public function user_exists($username)
    {
        $user = $this->users->findOne(['username' => $username]);
        return $user !== NULL;
    }

    public function login($user)
    {
        $_SESSION["user"] = $user;
    }

    public function check_credentials($username, $password)
    {
        $user = $this->users->findOne(['username' => $username]);

        if ($user !== NULL && $user['password'] == $password) {
            return $user;
        }

        return false;
    }

    public function is_authenticated()
    {
        return isset($_SESSION["user"]);
    }

    public function logout()
    {
        unset($_SESSION["user"]);
    }

    public function save($user)
    {
        if (!$this->user_exists($user['username'])) {
            $user = $this->users->add($user);

            return true;
        } else {
            return false;
        }
    }

    public function is_admin()
    {
        if (isset($_SESSION["user"])) {
            if ($_SESSION['user']['username'] == 'admin') {
                return true;
            }
        }

        return false;
    }
}
