<?php
// recupération des données requêtes
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
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


if ($action == 'section') {
    $sql = "SELECT section_code, soa_libelle FROM section ";
    $stmt = $getConnexion->prepare($sql);
    $stmt->execute();
    $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($info) {
        $data['dataSection'] = $info;
        $data['message'] = 'Success...! les données sont recuperés';
    } else {
        $data['error'] = true;
        $data['message'] = 'Failed...! les données sont pas recuperés';
    }
}

if ($action == 'addSec') {

    $matricule = $_POST['matricule'];
    $section_code = json_decode($_POST['section']);

    $sql_delete = "DELETE FROM user_section WHERE ref_mat_user = :matricule";
    $stmt_delete = $getConnexion->prepare($sql_delete);
    $stmt_delete->bindParam(':matricule', $matricule);
    $stmt_delete->execute();

    foreach ($section_code as $value) {

        $sql1 = "INSERT INTO user_section (ref_mat_user, ref_sec_code) VALUES (:matricule, :valeur)";
        $stmt1 = $getConnexion->prepare($sql1);

        $stmt1->bindParam(':matricule', $matricule);
        $stmt1->bindParam(':valeur', $value);

        if ($stmt1->execute()) {
            $data['message'] = 'Success...! les données sont enregistrées';
        } else {
            $data['error'] = true;
            $data['message'] = 'Failed...! les données ne sont pas enregistrées';
            break;
        }
    }
}

if ($action == 'user_section') {
    $matricule = $_POST['matricule'];
    try {
        $sql = "SELECT ref_sec_code FROM user_section WHERE ref_mat_user = :matricule";
        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->execute();
        $info = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $data['section'] = $info;
    } catch (PDOException $e) {
        echo "Erreur de récupération des sections de l'utilisateur : " . $e->getMessage();
    }
}

if ($action == 'nombre_section') {
    $matricule = $_POST['matricule'];
    try {
        $sql = "SELECT COUNT(ref_sec_code) AS nb_section_occupe FROM user_section WHERE ref_mat_user = :matricule";
        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->execute();
        $info = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $data['nombre'] = $info;
    } catch (PDOException $e) {
        echo "Erreur de récupération des sections de l'utilisateur : " . $e->getMessage();
    }
}

$getConnexion = null;
echo json_encode($data);
?>
