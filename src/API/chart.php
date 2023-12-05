<?php
// recupération des données requêtes
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

if ($action == 'nb') {

  $sql = "SELECT CATEGORIE_CODE , COUNT(CATEGORIE_CODE) as nombre FROM agent GROUP BY CATEGORIE_CODE";
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

if ($action == 'nbSec') {
  $matricule = $_POST['matricule'];
  $sql = "SELECT SECTION_CODE , COUNT(SECTION_CODE) as nombre FROM AGENTS A JOIN USER_SECTION US on A.SECTION_CODE = US.id_section WHERE US.id_user='$matricule' GROUP BY SECTION_CODE;";
  $resultat = $conn->query($sql);
  $info = array();
  while ($a = $resultat->fetch_array()) {
    array_push($info, $a);
  }
  $row = mysqli_num_rows($resultat);
  if ($row > 0) {

    $data['dataUser'] = $info;
    $data['message'] = 'Success...! les données sont recuperés';
  } else {
    # code...
    $data['error'] = true;
    $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
  }
}

// SELECT SECTION_CODE , COUNT(SECTION_CODE) as nombre FROM AGENTS A JOIN USER_SECTION US on A.SECTION_CODE = US.id_section WHERE US.id_user='55' GROUP BY SECTION_CODE;

$getConnexion = null;
echo json_encode($data);

?>
