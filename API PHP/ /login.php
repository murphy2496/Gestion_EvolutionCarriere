<?php
      require_once('../cors.php');
      include_once('../db-config.php');

      $connexion = new Connexion();
      $getConnexion = $connexion->Connectar();

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