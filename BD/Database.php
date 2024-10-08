<?php

namespace BD;

require_once("Config/config.php");

use PDO;
use PDOException;

class Database
{
	private $host = DB_HOST;
	private $db_name = DB_NAME;
	private $username = DB_USER_NAME;
	private $password = DB_USER_PASSWORD;
	public $conn;

	public function getConnection()
	{
		$this->conn = null;

		try {
			$this->conn = new PDO(
				"mysql:host=" . $this->host . ";dbname=" . $this->db_name,
				$this->username,
				$this->password
			);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->exec("set names utf8");
		} catch (PDOException $exception) {
			echo "Error de conexión: " . $exception->getMessage();
		}

		return $this->conn;
	}
}
