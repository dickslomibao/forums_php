<?php

require_once "database.php";

class thread extends database
{
    private $user_id;
    private $thread_id;
    private $category_id;
    private $title;
    private $desciption;

    public function setThreadId($thread_id)
    {
        $this->thread_id = $thread_id;
        return $this;
    }
    public function setCategoryId($id)
    {
        $this->category_id = $id;
        return $this;
    }
    public function setTitle($title)
    {
        $this->title = $this->escaper($title);
        return $this;
    }
    public function setDescription($desciption)
    {
        $this->desciption = $this->escaper($desciption);
        return $this;
    }
    public function setUserId($user_id){
        $this->user_id = $this->escaper($user_id);
        return $this;
    }
    public function operation($type)
    {
        $con = $this->connection();
        $user = "and user_id='".$_SESSION['user_id']."'";
        if($_SESSION['role'] == 'Admin'){
            $user = "";
        }
        $sql = "";
        if ($type == "add")
        {
            $sql = "INSERT INTO `threads`(
                `thread_id`, 
                `category_id`, 
                `title`, 
                `description`, 
                `user_id`, 
                `date_created`
            ) 
            VALUES(
                '" . $this->generateId() . "',
                " . $this->category_id . ",
                '" . $this->title . "',
                '" . $this->desciption . "',
                '" . $_SESSION['user_id'] . "',
                '" . $this->dateToday() . "'
            )";
        }else if ($type == "edit")
        {
            $sql = "UPDATE `threads` SET 
            `category_id`='".$this->category_id."',
            `title`='".$this->title."',
            `description`='".$this->desciption."'
            where thread_id = '".$this->thread_id."'".$user;
        }
        else if ($type == "delete")
        {
            $sql = "DELETE FROM threads where thread_id = '".$this->thread_id."'".$user;
        }

        try {
            $con->query($sql); 
            if($con->affected_rows > 0){
                echo "200";
            }
            else{
                 $_SESSION['error'] = "Not Found data".$sql.$con->affected_rows;
                 echo "505";
            }
        }catch(mysqli_sql_exception $ex){
            $_SESSION['error'] = $ex->getCode();
            echo "505";
        } 
        $con->close();
                }
    public function display()
    {
        $for_discussion = false;
        $html  ="";
        $sql ="";
        if ($this->thread_id == "") {
            if ($this->title != "") {
                $sql = "select threads.thread_id,user_information.user_id, category.name, threads.title, threads.description, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, threads.date_created,user_role.role_description from user_role, user_information,threads,category WHERE user_information.user_role_id = user_role.user_role_id and category.category_id = threads.category_id and user_information.user_id = threads.user_id and threads.title like '%".$this->title."%' and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) order by threads.date_created DESC";
            }else{
                $sql = "select threads.thread_id,user_information.user_id, category.name, threads.title, threads.description, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, threads.date_created,user_role.role_description from user_role, user_information,threads,category WHERE user_information.user_role_id = user_role.user_role_id and category.category_id = threads.category_id and user_information.user_id = threads.user_id  and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) order  by threads.date_created DESC";
            }
        }
        else {
            
            $for_discussion = true;
            $sql = "select threads.thread_id,user_information.user_id, category.name, threads.title, threads.description, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, threads.date_created,user_role.role_description from user_role, user_information,threads,category WHERE user_information.user_role_id = user_role.user_role_id and category.category_id = threads.category_id and user_information.user_id = threads.user_id and threads.thread_id = '" . $this->thread_id . "' and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) ";
        }
        if($this->user_id != ""){
            $sql = "select threads.thread_id,user_information.user_id, category.name, threads.title, threads.description, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, threads.date_created,user_role.role_description from user_role, user_information,threads,category WHERE user_information.user_role_id = user_role.user_role_id and category.category_id = threads.category_id and user_information.user_id = threads.user_id and user_information.user_id = '".$this->user_id."' and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) order by threads.date_created DESC";
        }
        $results = $this->connection()->query($sql);

        while ($row = $results->fetch_array()) {
            $operation = "";
            $adminIcon = "";
            if($row[1] == $_SESSION['user_id'] || $_SESSION['role'] == "Admin")
            {
                $operation = "<div class='dropdown'>
                <a class='operation' role='button' data-id='" . $row[0] . "' data-bs-toggle='dropdown' aria-expanded='false'>
                    <i class='fa-solid fa-ellipsis-vertical edit-delete' data-id='" . $row[0] . "'></i>
                </a>
                <ul class='dropdown-menu'>
                    <li><a class='dropdown-item' role='button' data-bs-toggle='modal' data-bs-target='#editThreads'>Edit</a></li>
                    <li><a class='dropdown-item delete-thread' role='button'>Delete</a></li>
                </ul>
            </div>";
            }
            if($this->user_id != ""){
                $operation = "";
            }
            if($row[8] === "Admin"){
                $adminIcon = "<i class='fa-solid fa-shield-dog'></i>";
            }
            $posttxt = $this->totalPosts($row[0]) > 1 ? "Posts": "Post";
            $html .= "
            <div class='col-12 content'>
                " . $operation . "
                <a href='profile.php?user_id=".$row[1]."' stlye='color:#f5f5f5 !important'>
                    <div style='float:left;width:40px !important'>
                        <div class='author-dp'>
                            <img src='../../" . $row[6] . "' alt=' srcset='>
                        </div>
                        <center><h3 style='font-size:12px;padding-top:20px !important;color:forestgreen'>".$this->totalPosts($row[0])."<br>".$posttxt."</h3></center>
                    </div>
                    <div style='float: right;width:calc(100% - 50px)'>
                    <h6 class='author'>" . $row[5] . " ". $adminIcon." </h6></a>
                    <a href='discussion.php?id=" . $row[0] . "'>
                    <h6 class='date'>" . date_format(date_create($row[7]), "M d, Y h:i a") . "</h6>
                    <h5>" . $row[3] . "</h5>
                     " . $row[4] . "
                     
                     <h6 class='category'>Category: " . $row[2] . "</h6>
                     
                    </div>
                </a>
            </div>'";
        }
        if($html==""){
            if($for_discussion){
                return "invalid";
            }
            echo "<h6 style='color:white'>No data yet!</h6>";
        }else{
            echo $html;
        }

        $this->connection()->close();
    }
    public function getSingleData()
    {

        $sql = "SELECT threads.title,threads.category_id,threads.description FROM threads WHERE threads.thread_id = '".$this->thread_id."'";
        $result = $this->connection()->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_array();
            echo json_encode(array($row[0],$row[1],$row[2]));
            return;
        }
        echo "404";   
        $this->connection()->close(); 
    }
    
    private function dateToday()
    {
        date_default_timezone_set("Asia/manila");
        return date("Y-m-d H:i:s");
    }

    private function generateId()
    {
        return uniqid() . time();
    }
    private function escaper($data)
    {
        return $this->connection()->real_escape_string($data);
    }
    public function getLatestorPopular($type){
        if($type == "popular"){
            $sql = "select threads.thread_id,user_information.user_id, category.name, threads.title, threads.description, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, threads.date_created,user_role.role_description from user_role, user_information,threads,category WHERE user_role.user_role_id = user_information.user_role_id and category.category_id = threads.category_id and user_information.user_id = threads.user_id and threads.thread_id = '".$this->getPopularThreads_ID()."'";
        }else{
            $sql = "select threads.thread_id,user_information.user_id, category.name, threads.title, threads.description, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, threads.date_created,user_role.role_description from user_role, user_information,threads,category WHERE user_role.user_role_id = user_information.user_role_id and category.category_id = threads.category_id and user_information.user_id = threads.user_id and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) order by threads.date_created DESC LIMIT 1";

        }
        

        $results = $this->connection()->query($sql);
        $html = "";
        if($results->num_rows > 0){
        $row = $results->fetch_array();

            $adminIcon = "";
            
            if($row[8] === "Admin"){
                $adminIcon = "<i class='fa-solid fa-shield-dog'></i>";
            }

            $posttxt = $this->totalPosts($row[0]) > 1 ? "Posts": "Post";
            $html = "
            <div class='col-12 content'>
                
                <a href='pages/users/profile.php?user_id=".$row[1]."' stlye='color:#f5f5f5 !important'>
                    <div style='float:left;width:40px !important'>
                        <div class='author-dp'>
                            <img src='" . $row[6] . "' alt=' srcset='>
                        </div>
                        <center><h3 style='font-size:12px;padding-top:20px !important;color:forestgreen'>".$this->totalPosts($row[0])."<br> ".$posttxt."</h3></center>
                    </div>
                    <div style='float: right;width:calc(100% - 50px)'>
                    <h6 class='author'>" . $row[5] . " ". $adminIcon." </h6></a>
                    <a href='pages/users/discussion.php?id=" . $row[0] . "'>
                    <h6 class='date'>" . date_format(date_create($row[7]), "M d, Y h:i a") . "</h6>
                    <h5>" . $row[3] . "</h5>
                     " . $row[4] . "
                     
                     <h6 class='category'>Category: " . $row[2] . "</h6>
                     
                    </div>
                </a>
            </div>";    
        }     
        if($html == ""){
            $html = "<p style='color:white;font-size:13px'>No Data Yet</p>";
        }
        return $html;
    }

    private function getPopularThreads_ID(){
        $sql = "SELECT posts.thread_id ,posts.user_id, count(posts.thread_id) as times from posts,threads WHERE posts.thread_id = threads.thread_id and posts.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = posts.user_id) and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) GROUP BY posts.thread_id ORDER by Times DESC limit 1";
        $result = $this->connection()->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_array();
            return $row[0];
        }
    }

    public function totalThreads(){
        $sql = "select count(*) from user_information,threads,category WHERE category.category_id = threads.category_id and user_information.user_id = threads.user_id and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id)";

        $result = $this->connection()->query($sql);
        $row = $result->fetch_array();

        return $row[0];
    }

    public function totalPosts($id){
        $sql = "SELECT COUNT(*) from posts where posts.thread_id = '".$id."' and posts.user_id not in (SELECT banned_user.user_id from banned_user WHERE banned_user.user_id = posts.user_id)";

        $result = $this->connection()->query($sql);
        $row = $result->fetch_array();

        return $row[0];
    }
}
