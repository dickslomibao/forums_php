<?php 

require_once "database.php";
class post extends database{
    private $post_id;
    private $thread_id;
    private $content;


    public function setPostId($post_id){
        $this->post_id = $this->escaper($post_id);
        return $this;
    }
    public function setThreadId($thread_id){
        $this->thread_id = $this->escaper($thread_id);
        return $this;
    }
    public function setDescription($content){
        $this->content = $this->escaper($content);
        return $this;
    }

    public function operation($type){
        $con = $this->connection();
        $user = "and user_id='".$_SESSION['user_id']."'";
        if($_SESSION['role'] === 'Admin'){
            $user = "";
        }
        $sql = "";
        if($type== "add"){
            $sql = "INSERT INTO `posts`(
                `post_id`, 
                `content`, 
                `thread_id`, 
                `user_id`,
                `date_created`
            )
            VALUES(
                '".$this->generateId()."',
                '".$this->content."',
                '".$this->thread_id."',
                '".$_SESSION['user_id']."',
                '".$this->dateToday()."'
            )";
        }else if ($type == "edit")
        {
            $sql = "UPDATE `posts` SET 
            `content`='".$this->content."' where post_id = '".$this->post_id."' ".$user;
        }
        else if ($type == "delete")
        {
            $sql = "DELETE FROM posts where post_id = '".$this->post_id."' ".$user;
        }

        try {
            $con->query($sql); 
            if($con->affected_rows > 0){
                echo "200";
            }
            else{
                 $_SESSION['error'] = "error";
                echo "505";
            }
        }catch(mysqli_sql_exception $ex){
            $_SESSION['error'] = $ex->getCode();
            echo "505";
        } 
        $con->close();
    }
    public function display(){

        $sql= "select posts.post_id,user_information.user_id, posts.content, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, posts.date_created, user_role.role_description from user_role, user_information,threads,posts WHERE user_role.user_role_id = user_information.user_role_id and threads.thread_id = posts.thread_id and user_information.user_id = posts.user_id and posts.thread_id = '".$this->thread_id."' and posts.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = posts.user_id) order by posts.date_created DESC";        
        $results = $this->connection()->query($sql);
        $html = "";
        while($row = $results->fetch_array()){
            $operation = "";
            $adminIcon = "";
            if($row[1] === $_SESSION['user_id'] || $_SESSION['role'] == "Admin"){
                $operation = "<div class='dropdown'>
                <a class='operation-post' data-id='".$row[0]."' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                    <i class='fa-solid fa-ellipsis-vertical'  data-id='".$row[0]."' ></i>
                </a>
                <ul class='dropdown-menu'>
                    <li><a class='dropdown-item' role='button' data-bs-toggle='modal' data-bs-target='#editPost'>Edit</a></li>
                    <li><a class='dropdown-item delete-post' role='button'>Delete</a></li>
                </ul>
            </div>";
            }
            if($row[6] === "Admin"){
                $adminIcon = "<i class='fa-solid fa-shield-dog'></i>";
            }
            $html .= "<div class='col-12 content comments p-0' stlye=''>  
                        ".$operation."
                        <a href='profile.php?user_id=".$row[1]."' stlye='color:#f5f5f5 !important'>               
                        <div style='float:left;width:55px !important'>
                            <div class='author-dp'>
                                <img src='../../".$row[4]."' alt=' srcset='>
                            </div>
                            </a>
                            <center><h3>".$this->getTotalReplies($row[0])."<br>replies</h3></center>
                        </div>
                        <a href='profile.php?user_id=".$row[1]."' stlye='color:#f5f5f5 !important'>
                        <div style='float: right;width:calc(100% - 55px)'>
                            <h5>".$row[3]." ".$adminIcon."</h5></a>
                            <h6 class='date'>".date_format(date_create($row[5]), "M d, Y h:i a")."</h6>
                            ".$row[2]."
                            
                            <button class='btn-reply show show-reply' data-id='".$row[0]."' id='btn-".$row[0]."' >Show Replies</button>
                            <div class='replies-container' id='".$row[0]."'>
                                <h4 class='c-word'>Replies:</h4>
                                <div class='main-content-reply'>
                                
                                
                                </div>           
                                <button data-bs-toggle='modal' data-bs-target='#addReplies' class='btn-reply add add-reply' data-id='".$row[0]."'>Add Replies</button>
                            </div>
                        </div>
                    </div>";
    
        }

        echo $html == "" ?  "<p class='no-post-txt'>No post Available!</p>":  $html;
    
    }
    private function getTotalReplies($post_id){
        $sql = "select * from replies where post_id = '".$post_id."'  and replies.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = replies.user_id)";
        $results = $this->connection()->query($sql);
    
        return $results->num_rows;
    }
 
    public function getSingleData()
    {
        $sql = "SELECT content FROM posts WHERE post_id= '".$this->post_id."'";
        $result = $this->connection()->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_array();
            echo json_encode(array($row[0]));
            return;
        }
        echo "404";   
        $this->connection()->close(); 
    }
    
    private function dateToday(){
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
}
?>