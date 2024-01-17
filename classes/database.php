<?php 
    session_start();
    class database{

        private $host = "localhost";
        private $user = "root";
        private $password = "";
        private $db = "forums_db";
        private $port = "3306";
        private $con;

        protected function connection(){

            try{
                
                $this->con = new mysqli(
                    $this->host,
                    $this->user,
                    $this->password,
                    $this->db,
                    $this->port
                );

            }catch(mysqli_sql_exception $ex){
                echo 'Error: ' . $ex->getMessage();
            }

            return $this->con;
        }
        
    }

?>