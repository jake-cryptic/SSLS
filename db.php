<?php
namespace AbsoluteDouble\SSLS;
class Database {
	private $hn = "127.0.0.1";
	private $un = "root";
	private $ps = "";
	private $db = "ssls";
	private $ek = ""; // Encryption key!!
	private $config;
	
	public $connection;
	
	public function __construct($config){
		$this->config = $config;
	}
	
	public function Connect(){
		$conn = @new \mysqli($this->hn,$this->un,$this->ps,$this->db);
		
		if ($conn->connect_error) {
			unset($conn);
			ShowError("Database",$this->config);
			die();
		} else {
			$conn->set_charset("utf8");
			$this->connection = $conn; 
		}
		
		return $this->connection;
	}
	
	public function Run($sql){
		return $this->connection->query($sql);
	}
	
	public function GetKey($pass,$salt){
		$str = base64_encode($pass . $salt . $this->ek);
		$hash = hash("sha512",$str);
		return $hash;
	}
	
	public function Disconnect(){
		$this->connection->close();
	}
	
	public function Error(){
		if ($this->config->debug == true)
			return "<h2>SQL Error:</h2>{$this->connection->error}";
		else
			return "<h2>The request failed</h2>";
	}
}
?>