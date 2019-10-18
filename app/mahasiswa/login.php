<?php
session_start();

class mahasiswa
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
    
    
    public function doLogin($nim,$pass)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT id,nim,password FROM mahasiswa WHERE nim=:nim");
            $stmt->execute(array(':nim'=>$nim));
            $mahasiswaRow=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 1)
            {
                if(password_verify($pass, $mahasiswaRow['password']))
                {
                    $this->update($mahasiswaRow['id']);
                    $_SESSION['mahasiswa_session'] = $mahasiswaRow['id'];
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
                $stmt = $this->conn->prepare("UPDATE mahasiswa SET terakhir_login=:login WHERE id=:id");
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
        if(isset($_SESSION['mahasiswa_session']))
        {
            return true;
        }
    }
    
}

$login = new mahasiswa();

include ROOT . 'views/mahasiswa/login.view.php';