<?php
session_start();

class ADMIN
{   

    private $conn;
    
    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }
    
    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
    
    
    public function doLogin($uname,$upass)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT id,username,password FROM admins WHERE username=:uname");
            $stmt->execute(array(':uname'=>$uname));
            $adminRow=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 1)
            {
                if(password_verify($upass, $adminRow['password']))
                {
                    $_SESSION['admin_session'] = $adminRow['id'];
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function is_loggedin()
    {
        if(isset($_SESSION['admin_session']))
        {
            return true;
        }
    }
    
    
    public function gantisandi($id,$ks)
    {
        try
            {

                $pass = password_hash($ks, PASSWORD_DEFAULT);

                $stmt = $this->conn->prepare("UPDATE pelanggan SET password=:pass WHERE id=:id");
                $stmt->bindparam(":pass", $pass);                   
                $stmt->bindparam(":id", $id);                   
                $stmt->execute();   
                
                return $stmt;   
            }

        catch(PDOException $e)
            {
                echo $e->getMessage();
            }               
    }

}

$login = new ADMIN();

include ROOT . 'views/admin/login.view.php';