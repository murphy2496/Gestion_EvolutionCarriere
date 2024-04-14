<?php

require_once('../cors.php');
include_once('../db-config.php');

$connexion = new Connexion();
$getConnexion = $connexion->Connectar();

//récuperation
if ($action == 'user'){
    
  $sql = "select *  from USERS where role='RH'"; 
  $stmt = $getConnexion->prepare($sql);
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

if ($action == 'add') {

  $matricule = $_POST['matricule'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $sql1 = "INSERT INTO users (matricule, nom, prenom, password, role) VALUES (:matricule, :nom, :prenom, :password, :role)";
  $stmt1 = $getConnexion->prepare($sql1);

  $stmt1->bindParam(':matricule', $matricule);
  $stmt1->bindParam(':nom', $nom);
  $stmt1->bindParam(':prenom', $prenom);
  $stmt1->bindParam(':password', $password);
  $stmt1->bindParam(':role', $role);

  if ($stmt1->execute()) {
     $data['message'] = 'Success...! les données sont enregistrées';
  } else {
     $data['error'] = true;
     $data['message'] = 'Failed...! les données ne sont pas enregistrées';
  }
}

if ($action == 'edit') {

  $id = $_POST['id_mat'];
  $matricule = $_POST['matricule'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $role = $_POST['role'];

  $sql = "UPDATE users SET matricule = :new_matricule, nom = :new_nom, prenom = :new_prenom,  role = :new_role WHERE matricule = :old_matricule";
  $stmt = $getConnexion->prepare($sql);

  $stmt->bindParam(':new_matricule', $matricule);
  $stmt->bindParam(':new_nom', $nom);
  $stmt->bindParam(':new_prenom', $prenom);
  $stmt->bindParam(':new_role', $role);
  $stmt->bindParam(':old_matricule', $id);

  if ($stmt->execute()) {
      $data['message'] = "user Updated Successfully";
  } else {
      $data['error'] = true;
      $data['message'] = "Could not update user";
  }
}

if ($action == 'delete') {

  $ID = $_POST['id'];
  $sql = "DELETE FROM users WHERE matricule = :matricule";
  $stmt = $getConnexion->prepare($sql);

  $stmt->bindParam(':matricule', $ID);

  if ($stmt->execute()) {
      $data['message'] = "User Deleted Successfully";
  } else {
      $data['error'] = true;
      $data['message'] = "Could not delete User";
  }

}



$getConnexion = null;
echo json_encode($data);
?>