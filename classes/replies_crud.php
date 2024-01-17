<?php 
require_once "database.php";

    class reply extends database{
        private $reply_id;
        private $content;
        private $post_id;

        public function setReplyId($reply_id){
            $this->reply_id = $this->escaper($reply_id);
            return $this;    
        }
        public function setContent($content){
            $this->content = $this->escaper($content);
            return $this;
        }
        public function setPostId($id){
            $this->post_id = $this->escaper($id);
            return $this;
        }


        public function operation($type){

            $con = $this->connection();
            $sql = "";
            $user = "and user_id='".$_SESSION['user_id']."'";
            if($_SESSION['role'] == 'Admin'){
                $user = "";
            }
            if($type == "add"){
                $sql ="INSERT INTO `replies`(
                    `reply_id`, 
                    `content`, 
                    `post_id`, 
                    `user_id`, 
                    `date_created`
                    ) 
                VALUES (
                    '".$this->generateId()."', 
                    '".$this->content."',
                    '".$this->post_id."',
                    '".$_SESSION['user_id']."',
                    '".$this->dateToday()."')";
            }else if ($type == "edit")
            {
                $sql = "UPDATE `replies` SET 
                `content`='".$this->content."' where reply_id = '".$this->reply_id."'".$user;
            }
            else if ($type == "delete")
            {
                $sql = "DELETE FROM replies where reply_id = '".$this->reply_id."'".$user;
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
                
            $this->connection()->close();

        }

        public function display(){
            $sql = "SELECT replies.content, concat(user_information.firstname, ' ', user_information.middlename, ' ' ,user_information.lastname) as name, user_information.user_profilepath, replies.date_created, replies.user_id, replies.reply_id,user_role.role_description from user_role, replies,posts,user_information WHERE user_information.user_role_id = user_role.user_role_id and replies.post_id = posts.post_id and user_information.user_id = replies.user_id and replies.post_id = '".$this->post_id."'  and replies.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = replies.user_id) order by replies.date_created";
            $html = "";
            
            $result = $this->connection()->query($sql);
            while($row = $result->fetch_array()){
                $operation = "";
                $adminIcon = "";
            if($row[4] === $_SESSION['user_id'] || $_SESSION['role'] == "Admin"){
                $operation = "
                <div class='dropdown'>
                    <a class='operation-reply' data-id='".$row[5]."' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='fa-solid fa-ellipsis-vertical'  data-id='".$row[5]."' ></i>
                    </a>
                    <ul class='dropdown-menu'>
                        <li><a class='dropdown-item' role='button' data-bs-toggle='modal' data-bs-target='#editReply'>Edit</a></li>
                        <li><a class='dropdown-item delete-reply' role='button'>Delete</a></li>
                    </ul>
                </div>";
            }
            if($row[6] === "Admin"){
                $adminIcon = "<i class='fa-solid fa-shield-dog'></i>";
            }
                $html .= "
                <div class='container p-0 replies-content'>
                    ".$operation."
                    <div class='row'>
                        <div class='col-12 p-0'>
                        <a href='profile.php?user_id=".$row[4]."' stlye='color:#f5f5f5 !important'> 
                            <div style='float:left;width:55px !important'>
                                <div class='author-dp'>
                                    <img src='../../".$row[2]."' alt=' srcset='>
                                </div>
                            </div>
                            <div style='float: right;width:calc(100% - 55px)'>
                                <h5>".$row[1]. " ". $adminIcon."</h5></a>
                                <h6 class='date'>".date_format(date_create($row[3]), "M d, Y h:i a")."</h6>
                                ".$row[0]."
                            </div>
                        </div>
                    </div>
                </div>
                ";
            }

            echo $html == "" ? "<p>No replies yet!</p>": $html;
            
            $this->connection()->close();
        }
        public function getSingleData()
        {
            $sql = "SELECT content FROM replies WHERE reply_id= '".$this->reply_id."'";
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
