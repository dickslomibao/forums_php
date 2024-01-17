<?php
require_once "database.php";
class user extends database
{

    private $userid;
    private $username;
    private $password;
    private $firstname;
    private $middlename;
    private $lastname;
    private $sex;
    private $birthdate;
    private $email;
    private $user_profilepath;

    public function setUserId($userid)
    {
        $this->userid = $userid;
        return $this;
    }
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;
        return $this;
    }
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    public function setUserPath($user_profilepath)
    {
        $this->user_profilepath = $user_profilepath;
        return $this;
    }
    public function generateId()
    {
        return uniqid() . time();
    }

    public function operation($type)
    {

        date_default_timezone_set('Asia/Manila');
        $date_today = date('Y-m-d', time());
        $sql = "";
        if ($type === "add") {
            $sql = "INSERT INTO `user_information`
            (
                `user_id`, 
                `username`, 
                `password`, 
                `firstname`, 
                `middlename`, 
                `lastname`, 
                `sex`, 
                `birthdate`, 
                `email`,
                `user_profilepath`, 
                `user_role_id`, 
                `date_created`

            ) VALUES
            (
                '" . $this->generateId() . "',
                '" . $this->username . "',
                '" . md5($this->password) . "',
                '" . $this->firstname . "',
                '" . $this->middlename . "',
                '" . $this->lastname . "',
                '" . $this->sex . "',
                '" . $this->birthdate . "',
                '" . $this->email . "',
                '" . $this->user_profilepath . "',
                1,
                '" . $date_today . "'
            )";
        } else if ($type === "updateprofile") {
            $sql = "UPDATE user_information set user_profilepath = '".$this->user_profilepath."' where user_id = '".$_SESSION['user_id']."' ";
        }
        else if ($type === "updateinformation") {
            $sql = "UPDATE `user_information` SET `username`='".$this->username."',`firstname`='".$this->firstname."',`middlename`='".$this->middlename."',`lastname`='".$this->lastname."',`sex`='".$this->sex."',`birthdate`='".$this->birthdate."',`email`='".$this->email."' where user_id = '".$_SESSION['user_id']."' ";
        } 
        else if ($type === "updatepassword") {
            $sql = "UPDATE `user_information` SET `password`='".$this->password."' where user_id = '".$_SESSION['user_id']."' ";
        }
        if ($this->connection()->query($sql)) {

        }
    }
    public function login()
    {

        $sql = "SELECT user_id,username,password,firstname,middlename,lastname,sex,birthdate,email,user_profilepath,user_role.role_description from user_information, user_role where user_information.user_role_id= user_role.user_role_id and username = '" . $this->username . "' and password = '" . md5($this->password) . "'";
        $result = $this->connection()->query($sql);
        if ($result->num_rows != 1) {
            return "Invalid username or password";
        } else {
            $row = $result->fetch_array();

            $_SESSION['user_id'] = $row[0];
            $_SESSION['username'] = $row[1];
            $_SESSION['password'] = $row[2];
            $_SESSION['name'] = $row[3] . " " . $row[5];
            $_SESSION['role'] = $row[10];
            $_SESSION['image'] = $row[9];

            header("Location: ../");
        }
    }

    public function validataBan($userid)
    {
        $con = $this->connection();
        $sql = "Select * from banned_user where user_id = '" . $userid . "'";
        $result = $con->query($sql);
        $row = $result->num_rows;
        if ($row != 0) {
            $data = $result->fetch_array();
            $_SESSION['ban'] =  $data[1];
            return true;
        } else {
            return false;
        }
    }





    public function displaySingleUser($type)
    {
        $con = $this->connection();
        $sql = "SELECT * from user_information where user_id = '" . $this->userid . "'";
        $result = $con->query($sql);

        if ($result->num_rows  <= 0) {
            header("Location: thread.php");
        } else {
            while ($row = $result->fetch_array()) {
                if($type != 'formodalupdate'){
                    $gender = "<i class='fa-solid fa-mars'></i>";
                    if ($row[6] == "Female") {
                        $gender = "<i class='fa-solid fa-venus'></i>";
                    }
                    return "              
                    <div class='profile-pic-main'>
                        <img src='../../" . $row[9] . "' alt=' srcset='>
                    </div>
                    <div class='user-basic-info'>
                        " . $gender . "
                        <h1>" . $row[3] . " " . $row[4] . " " . $row[5] . "</h1>
                        <p>Joined since " . date_format(date_create($row[11]), "F d, Y") . "</p>
                    </div>                    
                    ";
                }else{
                    return array($row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[11]);
                }
            }
        }
    }



    public function display()
    {
        $html = "";
        $sql = "";
        if($this->username != ""){
            $sql = "SELECT * FROM user_information , user_role where user_role.user_role_id = user_information.user_role_id and role_description != 'admin' and username like '%".$this->username."%'";

        }else{
            $sql = "SELECT * FROM user_information, user_role where user_role.user_role_id = user_information.user_role_id and role_description != 'admin'";
        }
        $result = $this->connection()->query($sql);
        while ($row = $result->fetch_array()) {
            $red = "";
            $txt = "Ban";
            $ban = "data-bs-toggle='modal' data-bs-target='#banned-user'";
            $unbanned = "";
            if ($this->validataBan($row[0])) {
                $red = "banned-user";
                $txt = "Unbanned";
                $unbanned = "unbanned";
                $ban = "";
            }
            $html .= "<div class='col-12 user-container " . $red . "'>
            <div style='float:left'>
                <div class='dropdown'>
                    <a class='operation' data-id='" . $row[0] . "' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='fa-solid fa-ellipsis-vertical' data-id='" . $row[0] . "'></i>
                    </a>
                    <ul class='dropdown-menu'>
                        <li><a class='dropdown-item " . $unbanned . "' role='button' " . $ban . " >" . $txt . "</a></li>
                    </ul>
                </div>
                <div style='width:80px !important'>
                    <div class='author-dp'>
                        <img src='../../" . $row[9] . "' alt=' srcset='>
                    </div>
                </div>
            </div>
            <div class='right-content-info'>
                <h6>" . $row[3] . " " . $row[4] . " " . $row[5] . "</h6>
                <p>@" . $row[1] . "</p>
                <p>Date Registered: " . $row[11] . "</p>
            </div>
        </div>";
        }
        echo $html;
    }
    public function validateUsername($username){
        $con = $this->connection();
        $sql = "select * from user_information where username = '".$username."'";
    
        $result = $con->query($sql);

        if($result->num_rows > 0 ){
            return false;
        }

        return true;
      
    }
    public function totalUser(){
        $sql = "SELECT COUNT(*) from user_information WHERE user_information.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = user_information.user_id)";
    
        $result = $this->connection()->query($sql);
        $row = $result->fetch_array();

        return $row[0];
    }
}
