<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author developer pc
 */
namespace harrykdeveloper\Wordpress\Database;

class Mysql {
    
    protected $con;
    
    protected $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC);
    
    /**
     * 
     * @param type $host
     * @param type $dbname
     * @param type $user
     * @param type $pass
     */
    public function connection($host,$dbname,$user,$pass){
        try{
            $this->con = new \PDO("mysql:host={$host};dbname={$dbname}", $user, $pass, $this->options);
            return $this->con;
            
        }catch(\PDOException $e){
            throw new \Exception($e->getMessage());
        }
    }
    
}
