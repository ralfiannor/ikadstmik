<?php
session_start();
date_default_timezone_set('Asia/Makassar');

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
    
    
    public function doLogout()
    {
        session_destroy();
        unset($_SESSION['admin_session']);
        return true;
    }


    public function pengaturan($tipe)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT a.id,a.tahun_akademik,a.semester,b.nidn as nip_direktur, b.nama as nama_direktur, c.nidn as nip_bendahara, c.nama as nama_bendahara FROM pengaturan as a LEFT JOIN dosen as b ON a.id_direktur = b.id LEFT JOIN dosen as c ON a.id_bendahara = c.id");
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 1)
            {
                if($tipe=='tahun_akademik')
                {
                    return $row['tahun_akademik'];
                }
                elseif($tipe=='semester')
                {
                    return $row['semester'];
                }
                elseif($tipe=='nip_direktur')
                {
                    return $row['nip_direktur'];
                }
                elseif($tipe=='nama_direktur')
                {
                    return $row['nama_direktur'];
                }
                elseif($tipe=='nip_bendahara')
                {
                    return $row['nip_bendahara'];
                }
                elseif($tipe=='nama_bendahara')
                {
                    return $row['nama_bendahara'];
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }


    public function tgl_indo($tanggal)
    {
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }

}

$login = new ADMIN();
