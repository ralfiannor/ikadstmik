<?php

require ROOT.'app/admin/session.php';

class layanan
{
	private $conn;
	
	function __construct()
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

	public function getID()
	{
		$stmt = $this->conn->prepare("SELECT a.id,a.tahun_akademik,a.semester,a.id_direktur,a.id_bendahara,b.nidn as nip_direktur, b.nama as nama_direktur, c.nidn as nip_bendahara, c.nama as nama_bendahara FROM pengaturan as a LEFT JOIN dosen as b ON a.id_direktur = b.id LEFT JOIN dosen as c ON a.id_bendahara = c.id WHERE a.id=1");
		$stmt->execute();
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
    public function ganti($tahunakademik,$semester,$direktur,$bendahara)
    {
        try
            {
                $stmt = $this->conn->prepare("UPDATE pengaturan SET tahun_akademik=:ta, semester=:semester, id_direktur=:direktur, id_bendahara=:bendahara WHERE id=1");
                $stmt->bindparam(":ta", $tahunakademik);                   
                $stmt->bindparam(":semester", $semester);                   
                $stmt->bindparam(":direktur", $direktur);                   
                $stmt->bindparam(":bendahara", $bendahara);                   
                $stmt->execute();                   
                return $stmt;   
            }

        catch(PDOException $e)
            {
                echo $e->getMessage();
            }               
    }
	
	
}

$layanan = new layanan();


include ROOT . 'views/admin/layanan.view.php';