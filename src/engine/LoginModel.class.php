<?php

//Módulo de login

declare(strict_types=1);

require_once 'Serenscript.class.php';

class LoginModel extends Serenscript {
	
	private $usersTable;
	private $userColunm;
	private $passwordColunm;
	private $idColunm;
	
	public function __construct(string $path){
		
		parent::__construct($path);
		$xml = file_get_contents($path.'login.xml');
		$xmlObject = new SimpleXMLElement($xml);
		$this->usersTable = (string)$xmlObject->usersTable;
		$this->userColunm = (string)$xmlObject->userColunm;
		$this->passwordColunm = (string)$xmlObject->passwordColunm;
		$this->idColunm = (string)$xmlObject->idColunm;
		
	}
	
    public function getAutentication (string $user, string $password): int {
        
        /* O array columnQuery deve conter o nome de todas as colunas utilizadas
         * na query. Por padrão são utilizados os campos referentes ao id, usuário
         * e senha.
         */
    	$columnQuery[] = $this->idColunm;
    	$columnQuery[] = $this->userColunm;
    	$columnQuery[] = $this->passwordColunm;
        //É necessário utilizar a função setColumn da classe Serenscript para definir as colunas da query
    	parent::setColumn($columnQuery);
        /* Em columnCompare deve-se utilizar como chave do array a tabela que será utilizada para a comparação
         * e o value será o valor que deve ser analisado
         */
        $columnCompare[$this->userColunm] = $user;
        $columnCompare[$this->passwordColunm] = $password;
        /*O método autentication recebe o nome das colunas que serão utilizadas no comando sql
         * e os valores que serão analisados nas colunas
         */
        $autentication = $this->autentication($columnCompare);
        return $autentication;
        
    }
    
    private function autentication(array $columnCompare): int{
    
        /*Necessário utilizar o método columnCompare para setar as colunas do comando sql
         * Não precisa estar aqui necessariamente, pode ser chamada a qualquer momento antes da execução deste método
         */
    	parent::columnCompare($columnCompare);
        /*Resultado da query com base em todos os parâmetros setados anteriormente
         * Necessário passar o nome da tabela definido no atributo da classe
         */
    	$select = parent::select($this->usersTable);
    	if($select->rowCount() == 1){
    
    		$login = $select->fetch(PDO::FETCH_ASSOC);
    		session_start();
                //setando na sessão o id e o login do usuário
    		$_SESSION['ID'] = $login[$this->idColunm];
    		$_SESSION['USER'] = $login[$this->userColunm];
    		return 0;
    
    	} elseif($select->rowCount() > 1){
                //acusando duplicação de registros no banco
    		return 1;
    	} else{
                //acusando ausêcia de registros no banco
    		return -1;
    	}
    }
}