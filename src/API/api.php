<?php
      header('Access-Control-Allow-Origin:*');
      header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); // Autorise les en-têtes requis
      header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
      header('Content-Type: application/json');
      header("Content-Type: multipart/form-data");

      include_once("db.php");

      $connexion = new Connexion();
      $getConnexion = $connexion->Connectar();

    $data = array('error' => false);
    $action = '';

    if (isset($_GET['action'])) {
    $action = $_GET['action'];
    }

    if ($action == 'login'){

      $matricule=$_POST['matricule'];
      $password=$_POST['password'];

      $sql = "SELECT * FROM USERS WHERE matricule = :matricule AND password = :password";
        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if ($info) {
            $data['infoBD'] = $info;
            $data['message'] = 'Vous êtes connecté...';
          } else {
            $data['error'] = true;
            $data['message'] = 'Votre matricule ou mot de passe est incorrect';
          }

        }

    $getConnexion = null;
    echo json_encode($data);

?>