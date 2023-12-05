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

function rowCount($conn, $sql)
{
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row  = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $row;
}
function rowCountRH($conn, $sql, $matr)
{
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':matr', $matr);
    $stmt->execute();
    $row  = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $row;
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}


if ($action == 'compter') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];

    if ($role != 'RH') {
        $sql1 = "SELECT COUNT(*) AS avancement_acheve
        FROM agent
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        WHERE ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) <= SYSDATE";
        $row1 = rowCount($getConnexion, $sql1);


        $sql2 = "SELECT COUNT(*) AS avancement_6mois
        FROM agent  LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        WHERE ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) <= ADD_MONTHS(SYSDATE, 6)
        AND ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) >= SYSDATE";
        $row2 = rowCount($getConnexion, $sql2);


        $sql3 = "SELECT  COUNT(*) AS contractuel_acheve
        FROM agent WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= SYSDATE";
        $row3 = rowCount($getConnexion, $sql3);


        $sql4 = "SELECT  COUNT(*) AS contractuel_6mois
        FROM agent WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= ADD_MONTHS(SYSDATE, 6)
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) >= SYSDATE";
        $row4 = rowCount($getConnexion, $sql4);


        $sql5 = "SELECT COUNT(*) AS retraite_tout
        FROM agent WHERE ((MONTHS_BETWEEN(SYSDATE, agent.date_de_naissance) / 12) >= 60)";
        $row5 = rowCount($getConnexion, $sql5);


        $sql6 = "SELECT COUNT(*) AS retraite_1an
        FROM agent  WHERE ADD_MONTHS(agent.date_de_naissance, 720) <= ADD_MONTHS(SYSDATE, 12)
        AND ADD_MONTHS(agent.date_de_naissance, 720) >= SYSDATE";
        $row6 = rowCount($getConnexion, $sql6);
    } else {
        $sql1 = "SELECT COUNT(*) AS avancement_acheve
        FROM agent
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) <= SYSDATE
        AND users.matricule = :matr";
        $row1 = rowCountRH($getConnexion, $sql1, $matricule);


        $sql2 = "SELECT COUNT(*) AS avancement_6mois
        FROM agent  LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) <= ADD_MONTHS(SYSDATE, 6)
        AND ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) >= SYSDATE
        AND users.matricule = :matr";
        $row2 = rowCountRH($getConnexion, $sql2, $matricule);


        $sql3 = "SELECT  COUNT(*) AS contractuel_acheve
        FROM agent
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= SYSDATE
        AND users.matricule = :matr";
        $row3 = rowCountRH($getConnexion, $sql3, $matricule);


        $sql4 = "SELECT  COUNT(*) AS contractuel_6mois
        FROM agent
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= ADD_MONTHS(SYSDATE, 6)
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) >= SYSDATE
        AND users.matricule = :matr";
        $row4 = rowCountRH($getConnexion, $sql4, $matricule);


        $sql5 = "SELECT COUNT(*) AS retraite_tout
        FROM agent 
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ((MONTHS_BETWEEN(SYSDATE, agent.date_de_naissance) / 12) >= 60)
        AND users.matricule = :matr";
        $row5 = rowCountRH($getConnexion, $sql5, $matricule);


        $sql6 = "SELECT COUNT(*) AS retraite_1an
        FROM agent
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ADD_MONTHS(agent.date_de_naissance, 720) <= ADD_MONTHS(SYSDATE, 12)
        AND ADD_MONTHS(agent.date_de_naissance, 720) >= SYSDATE
        AND users.matricule = :matr";
        $row6 = rowCountRH($getConnexion, $sql6, $matricule);
    }

        $data['count1'] = $row1;
        $data['count2'] = $row2;
        $data['count3'] = $row3;
        $data['count4'] = $row4;
        $data['count5'] = $row5;
        $data['count6'] = $row6 ;

        $data['message'] = 'Success...! les données sont recuperés';
}



$getConnexion = null;
echo json_encode($data);
die();
