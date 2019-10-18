<?php

require ROOT.'app/admin/session.php';

class gantisandi
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

    public function ganti($id,$upass)
    {
        try
            {

                $pass = password_hash($upass, PASSWORD_DEFAULT);

                $stmt = $this->conn->prepare("UPDATE admins SET password=:pass WHERE id=:id");
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

$gantisandi = new gantisandi();


include ROOT . 'views/admin/gantisandi.view.php';