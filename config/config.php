<?php

$con = mysqli_connect('localhost', 'root', '', 'sksbv_kkd');

define('IN_DEVELOPMENT', True);

mysqli_set_charset($con, 'utf8');

class User(){
    private $id;
    private $type;
    public $username;
    protected $password_hash;
    public $name;
    public $mobile;

    function __construct($username){
        $this->username = $username;
    }

    function create_user($connection, $user_type, $err_redirection){
        $this->id = uniqid('sksbv_');
        $this->type = $user_type;
        $query = "INSERT INTO sksbv_users VALUES
                        (
                            '$this->id',
                            '$this->type',
                            '$this->username',
                            '$this->password_hash',
                            '$this->name',
                            '$this->mobile')";
        $create = mysqli_query($connection, $query);
        if($create)return True;
        else{
            print_error($connection->error, "Some error occured!", $err_redirection);
            return False;
        }
    }

    function fetch_user($connection, $err_redirection){
        $fetch = mysqli_query($connection, "SELECT * FROM examify_users WHERE username='$this->username'");
        if($fetch->num_rows){
            $user = mysqli_fetch_array($fetch);
            $this->id = $user['user_id'];
            $this->type = $user['user_type'];
            $this->username = $user['username'];
            $this->password_hash = $user['password'];
            $this->name = $user['name'];
            $this->mobile = $user['mobile'];
            return True;
        }else{
            print_error($connection->error, "Some Error Occured", $err_redirection);
            return False;
        }
    }

    function store_password($password_sha256){
        $store = $this->password_hash = password_hash($password_sha256, PASSWORD_BCRYPT);
        if($store)return True;
        else return False;
    }
}

function print_error($error, $message, $redirection){
    if (IN_DEVELOPMENT){
        echo $error;
        echo '<br><a href="'.$redirection.'">REDIRECT</a>';
    }else{
        echo '<script>
                alert("'.$message.'");
                window.location.href = "'.$redirection.'";
             </script>';
    }
}
