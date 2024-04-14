<?php
class Connexion {
    public function Connectar() {
        define('HOST', '127.0.0.1');
        define('PORT', 1521);
        define('NAME', 'XE');
        define('USER', 'system');
        define('PASS', 'murphy');

        $bd_settings = "(DESCRIPTION = 
            (ADDRESS = 
            (PROTOCOL = TCP)
            (HOST = " . HOST . ")
            (PORT = " . PORT . ")
            )
            (CONNECT_DATA = 
            (SERVER = DEDICATED)
            (SERVICE__NAME = " . NAME . ")
            )
        )";

    try {
        $bd = new PDO('oci:dbname='.$bd_settings, USER, PASS);
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $bd;

    } catch (Exception $e) {
        echo "ERREUR DE CONNEXION: ".$e->getMessage();
        }

    }

}

?>