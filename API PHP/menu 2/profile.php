<?php
require_once('../cors.php');
include_once('../db-config.php');

$connexion = new Connexion();
$getConnexion = $connexion->Connectar();

//récuperation
if ($action == 'user'){

  $matricule = $_POST['matricule'];

  $sql = "select *  from USERS where matricule=:matricule"; 
  $stmt = $getConnexion->prepare($sql);
  $stmt->bindParam(':matricule', $matricule);
  $stmt->execute();
  $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($info) {
      $data['dataUser'] = $info;
      $data['message'] = 'Success...! les données sont recuperés';
    } else {
      $data['error'] = true;
      $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
    }

}

if ($action == 'edit') {

  $id = $_POST['id_mat'];
  $matricule = $_POST['matricule'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $password = $_POST['password'];

  if ($password == '') {
    $sql = "UPDATE users SET matricule = :new_matricule, nom = :new_nom, prenom = :new_prenom WHERE matricule = :old_matricule";
    $stmt = $getConnexion->prepare($sql);

    $stmt->bindParam(':new_matricule', $matricule);
    $stmt->bindParam(':new_nom', $nom);
    $stmt->bindParam(':new_prenom', $prenom);
    $stmt->bindParam(':old_matricule', $id);
  }else {
    $sql = "UPDATE users SET matricule = :new_matricule, nom = :new_nom, prenom = :new_prenom, password = :new_password WHERE matricule = :old_matricule";
    $stmt = $getConnexion->prepare($sql);

    $stmt->bindParam(':new_matricule', $matricule);
    $stmt->bindParam(':new_nom', $nom);
    $stmt->bindParam(':new_prenom', $prenom);
    $stmt->bindParam(':new_password', $password);
    $stmt->bindParam(':old_matricule', $id);
  }

  if ($stmt->execute()) {
      $data['message'] = "user Updated Successfully";
  } else {
      $data['error'] = true;
      $data['message'] = "Could not update user";
  }
}

$getConnexion = null;
echo json_encode($data);
?>