<?php

require_once('../cors.php');
include_once('../db-config.php');


$connexion = new Connexion();
$getConnexion = $connexion->Connectar();

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

if ($action == 'agent_section') {
    try {
        $sql = "SELECT section.section_code, section.soa_libelle, COUNT(agent.agent_matricule) AS nombre_agents
        FROM section
        LEFT JOIN agent ON section.section_code = agent.section_code
        WHERE section.section_code IN ('00010110', '00050110', '00110110', '00140110', '00150110', '00160110', '00210013',
        '00210014', '00210114', '00210115', '00210116', '00210117', '00210129', '00210130', '00260110', '00320110',
        '00340110', '00350110', '00360110', '00370110', '00380110', '00410110', '00430110', '00440110', '00450110',
        '00510110', '00520110', '00530110', '00610110', '00620110', '00630110', '00660110', '00710110', '00711010',
        '00711020', '00711030', '00711040', '00711050', '00711060', '00750110', '00760110', '00810110', '00811110',
        '00811120', '00811130', '00811140', '00811150', '00811160', '00830110', '00840110', '00860110', '00870110', '00930110')
        GROUP BY section.section_code, section.soa_libelle";
        $stmt = $getConnexion->prepare($sql);

        if ($stmt->execute()) {
            $data['message'] = 'Success...! les données sont enregistrées';
            $info = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data['agent_section'] = $info;
        } else {
            $data['error'] = true;
            $data['message'] = 'Failed...! les données ne sont pas enregistrées';
        }

    } catch (PDOException $e) {
        echo "Erreur de récupération des sections de l'utilisateur : " . $e->getMessage();
    }
}

$getConnexion = null;
echo json_encode($data);
?>