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
                if(password_verify($upass, $dosenRow['password']))
                {
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
    
    public function is_loggedin()
    {
        if(isset($_SESSION['dosen_session']))
        {
            return true;
        }
    }
    
    
    public function doLogout()
    {
        session_destroy();
        unset($_SESSION['dosen_session']);
        return true;
    }

    public function pengaturan($tipe)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT * FROM pengaturan");
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 1)
            {
                if($tipe=='tahun_akademik')
                {
                    return $row['tahun_akademik'];
                }
                else
                {
                    return $row['semester'];
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

}

$login = new dosen();
