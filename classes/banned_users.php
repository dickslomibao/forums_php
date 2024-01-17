<?php 

require_once 'database.php';

class ban extends database{


    private $userid;
    private $reason;



    public function operation($type){
        $sql = "";
        if($type == "banned"){
            $sql = "INSERT INTO banned_user VALUES('".$this->userid."', '".$this->reason."','".$this->dateToday()."')";
        }
        else if($type == "unbanned"){
            $sql = "DELETE FROM banned_user where user_id = '".$this->userid."'";
        }
        if($this->connection()->query($sql)){

        }
        
    }

    public function setUserId($userid){
        $this->userid = $this->escaper($userid);
        return $this;
    }
    public function setReason($reason){
        $this->reason = $this->escaper($reason);
        return $this;
    }
    private function escaper($data)
    {
        return $this->connection()->real_escape_string($data);
    }
    private function dateToday()
    {
        date_default_timezone_set("Asia/manila");
        return date("Y-m-d H:i:s");
    }
}
?>