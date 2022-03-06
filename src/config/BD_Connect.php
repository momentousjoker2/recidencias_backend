<?php

require __DIR__ . '/Config.php';


class DB_Connect
{

    private $connexion = null;
    private $BD = null;

    function createConnexicon()
    {
        try {
            $this->connexion = new PDO('mysql:host=' . HostDB . ';dbname=' . NameDB, UserNameDB, PasswordDB, null);
           // $this->connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
           // $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           // $this->connexion->setAttribute(PDO::ATTR_CASE, PDO::ERRMODE_EXCEPTION);
          //  $this->connexion->setAttribute(PDO::ATTR_TIMEOUT, 1000);


        } catch (PDOException $e) {

                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();

        }
        return $this->connexion;
    }
    

    
}

?>
