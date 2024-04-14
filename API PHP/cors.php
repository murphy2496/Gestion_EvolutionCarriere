<?php

        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); // Autorise les en-têtes requis
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Content-Type: application/json');
        header("Content-Type: multipart/form-data");
        header('Access-Control-Allow-Credentials: true');
        
        
        $data = array('error' => false);
        $action = '';

        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }

?>