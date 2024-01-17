<?php

require_once "database.php";

class category extends database
{
    private $id = 0;
    private $title;
    private $description;
    private $search_value;

    public function setTitle($title)
    {
        $this->title = $this->escaper($title);
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $this->escaper($description);
        return $this;
    }
    public function setSearchValue($search_value)
    {
        $this->search_value = $this->escaper($search_value);
        return $this;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function operation($type)
    {
        $sql = "";
        if ($type === "add") {
            $sql = "INSERT INTO category (`name`,`description`) VALUES('" . $this->title . "', '" . $this->description . "')";
        } else if ($type === "update") {
            $sql = "UPDATE category SET name = '" . $this->title . "' , description = '" . $this->description . "' where category_id = " . $this->id . "";
        } else if ($type === "delete") {
            $sql = "DELETE FROM category where category_id = " . $this->id . "";
        }

        if ($this->connection()->query($sql)) {
            echo "Successfully added";
        }

        $this->connection()->close();
    }

    public function display($type,$role)
    {
        $sql = "";
        if ($type == "search") {
            $sql = "select * from category where name like '%" . $this->search_value . "%' order by name";
        } else {
            $sql = "select * from category order by name";
        }
        $html = "";
        $result = $this->connection()->query($sql);
        while ($row = $result->fetch_array()) {
            if ($role=="Admin") {
                $html .= "
                    <div class='col-12 item-container'>
                        <div class='list-item-category'>
                            <h6>" . htmlspecialchars($row[1]) . "</h6>
                            <p>" . htmlspecialchars($row[2]) . "</p>

                            <div>
                                <span>Date created: " . date_format(date_create($row[3]), ' M-d-Y') . "</span>
                                <i class='fa-solid fa-pen-to-square edit' data-bs-toggle='modal' data-bs-target='#updateModal' data-id='" . $row[0] . "'></i>
                                <i class='fa-solid fa-trash-can delete' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='" . $row[0] . "'></i>
                            </div>
                        </div>
                    </div>
                ";
            } else {

                if($type =="dropdown"){
                        $html .= "<option value='".$row[0]."'>".htmlspecialchars($row[1])."</option>";
                }else{
                    $html .= "
                    <div class='c-box'>
                        <div class='container-fluid p-0 category-item'>
                            <div style='float:left'>
                                <div class='box-color' style='background-color: ".$this->randomHex()."'>
                                </div>
                            </div>
                            <div>
                                <h6>".htmlspecialchars($row[1])."</h6>
                            </div>
                            <hr>
                            <div class='row'>
                                <div class='col-4 box-t'>
                                    ".$this->getTotalThreads($row[0])."<br>".($this->getTotalThreads($row[0]) > 1 ? "Threads" : "Thread") ."
                                </div>
                                <div class='col-4 box-t'>
                                    ".$this->getTotalPosts($row[0])."<br>".($this->getTotalPosts($row[0]) > 1 ? "Posts" : "Post") ."

                                </div>
                                <div class='col-4 box-t'>
                                    ".$this->getTotalUser($row[0])."<br>".($this->getTotalUser($row[0]) > 1 ? "Users" : "User") ."
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
                }
            }
        }
        echo $html;
        $this->connection()->close();
    }
    public function getSingleData($id)
    {

        $sql = "select * from category where category_id = " . $id . "";
        $result = $this->connection()->query($sql);
        $row = $result->fetch_array();
        $data = array($row[1], $row[2]);

        echo json_encode($data);
    }
    private function getTotalThreads($id){
       $sql = "SELECT * from category, threads WHERE category.category_id = threads.category_id and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) and category.category_id = ".$id." ";
       $result = $this->connection()->query($sql);

       return $result->num_rows;
    }
    private function getTotalPosts($id){
        $sql = "SELECT * from posts,threads,category where posts.thread_id = threads.thread_id and category.category_id = threads.category_id and posts.user_id  not in (SELECT banned_user.user_id from banned_user WHERE banned_user.user_id = posts.user_id) and threads.user_id  not in (SELECT banned_user.user_id from banned_user WHERE banned_user.user_id = threads.user_id) and category.category_id = ". $id;
        $result = $this->connection()->query($sql);
 
        return $result->num_rows;
     }
    
    private function getTotalUser($id){
        $sql = "SELECT user_information.user_id from user_information,threads,category WHERE threads.user_id = user_information.user_id and category.category_id = threads.category_id and threads.user_id not in (select banned_user.user_id from banned_user where banned_user.user_id = threads.user_id) and category.category_id = ".$id." GROUP by user_information.user_id";
        $result = $this->connection()->query($sql);
 
        return $result->num_rows;
    }
    private function escaper($data)
    {
        return $this->connection()->real_escape_string($data);
    }
    private function randomHex()
    {
        $chars = 'ABCDEF0123456789';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $color;
    }

    public function totalCategory(){
        $sql = "SELECT count(*) from category";

        $result = $this->connection()->query($sql);
        $row = $result->fetch_array();
        return $row[0];
    }
}
