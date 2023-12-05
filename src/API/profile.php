<?php
// recupération des données requêtes
include_once("db.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("Content-Type: multipart/form-data");
# code...


$connexion = new Connexion();
$getConnexion = $connexion->Connectar();

     
$data = array('error' => false);
$action = '';

if (isset($_GET['action'])) {
  $action = $_GET['action'];

}

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