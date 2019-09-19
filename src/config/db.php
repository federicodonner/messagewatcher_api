<?php

class db{


/*
  // Properties
  private $dbhost = 'localhost';
  private $dbuser = 'feder161_mgadmin';
  private $dbpass = 'GgCy2p2VLGnBAC3';
  private $dbname = 'feder161_messagewatcher';

// */



//MAMP
   private $dbhost = 'localhost';
   private $dbuser = 'root';
   private $dbpass = 'root';
   private $dbname = 'messagewatcher';

// */

  public function connect(){
    $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=UTF8";
    $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbConnection;
  }
}
