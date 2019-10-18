<?php
session_start();

class dosen
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
    
    public function doLogin($nidn,$pass)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT id,nidn,password FROM dosen WHERE nidn=:nidn");
            $stmt->execute(array(':nidn'=>$nidn));
            $dosenRow=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 1)
            {
                if(password_verify($pass, $dosenRow['password']))
                {
                    $this->update($dosenRow['id']);
                    $_SESSION['dosen_session'] = $dosenRow['id'];
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

    public function update($id)
    {
        try
            {
                date_default_timezone_set("Asia/Jakarta");
                $login = date("Y-m-d H:i:s");
                $stmt = $this->conn->prepare("UPDATE dosen SET terakhir_login=:login WHERE id=:id");
                $stmt->bindparam(":login", $login);                   
                $stmt->bindparam(":id", $id);                   
                $stmt->execute();   
                
                return $stmt;   
            }

        catch(PDOException $e)
            {
                echo $e->getMessage();
            }               
    }
    
    public function is_loggedin()
    {
        if(isset($_SESSION['dosen_session']))
        {
            return true;
        }
    }
    
}

$login = new dosen();

include ROOT . 'views/dosen/login.view.php';