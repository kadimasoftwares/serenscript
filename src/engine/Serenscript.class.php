<?php

declare(strict_types=1);

abstract class Serenscript {
	
    private $host; 
    private $user; 
    private $password;
    private $db;
    private $colation;
    private $pdo;
    protected $column;
    protected $where;
    protected $values;
    
    protected function __construct(string $path) {
        
    	$xml = file_get_contents($path.'database.xml');
    	$xmlObject = new SimpleXMLElement($xml);
        $this->db = (string)$xmlObject->database;
        $this->host = (string)$xmlObject->host;
        $this->password = (string)$xmlObject->dbPassword;
        $this->user = (string)$xmlObject->dbUser;
        $this->colation = (string)$xmlObject->dbColation;
        $this->where = "";
        
        try{
        	
        	$this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->colation"));
        	$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        }catch (PDOException $e){
        	echo 'Ocorreu um erro durante a conexão com o banco de dados: '. $e->getMessage();	
        }
    }
    
    protected function select(string $table):PDOStatement{
    	
    	$result = $this->pdo->prepare("SELECT $this->column FROM $table $this->where");
    	
    	foreach ($this->values as $index => $value){
    		$param = $index + 1;
    		$result->bindValue($param, $value);
    	}
    	
    	$result->execute();
    	return $result;
        
    }

    protected function columnCompare(array $columnCompare){
        
    	$key = array_keys($columnCompare);
    	$values = array_values($columnCompare);
    	$this->where = " WHERE ";
        
    	foreach ($key as $index => $column){
    		
    		$this->where .= $column." = ?";
    		
    		if(next($key) == true){
    			$this->where .= " AND ";
    		}
    		
    	}
    	$this->values = $values;
    }
    
    protected function setColumn(array $column){
        
        foreach ($column as $selectColumn){
        
        	$this->column .= $selectColumn;
        
        	if(next($column) == true){
        		$this->column .= ",";
        	}
        }
    }
}