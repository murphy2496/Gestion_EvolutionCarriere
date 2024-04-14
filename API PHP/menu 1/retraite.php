<?php

require_once('../cors.php');
include_once('../db-config.php');

$connexion = new Connexion();
$getConnexion = $connexion->Connectar();

if ($action == '1ans') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];

    if ($role != 'RH') {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms,
                TO_CHAR(agent.date_de_naissance, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_naissance,
                TO_CHAR(ADD_MONTHS(agent.date_de_naissance, 720), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_retraite_prevue,
                agent.statut, section.soa_libelle, ministere.ministere_libelle, agent.section_code
                FROM agent
                LEFT OUTER JOIN section ON agent.section_code = section.section_code
                LEFT OUTER JOIN ministere ON agent.code_ministere = ministere.ministere_code
                WHERE ADD_MONTHS(agent.date_de_naissance, 720) <= ADD_MONTHS(SYSDATE, 12)
		        AND ADD_MONTHS(agent.date_de_naissance, 720) >= SYSDATE";
         $stmt = $getConnexion->prepare($sql);
    } else {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms,
        TO_CHAR(agent.date_de_naissance, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_naissance,
        TO_CHAR(ADD_MONTHS(agent.date_de_naissance, 720), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_retraite_prevue,
        agent.statut, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        LEFT OUTER JOIN ministere ON agent.code_ministere = ministere.ministere_code
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ADD_MONTHS(agent.date_de_naissance, 720) <= ADD_MONTHS(SYSDATE, 12)
        AND ADD_MONTHS(agent.date_de_naissance, 720) >= SYSDATE 
        AND users.matricule = :matr";

         $stmt = $getConnexion->prepare($sql);
         $stmt->bindParam(':matr', $matricule);
    }


    $stmt->execute();
    $dataAgentsA1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($dataAgentsA1) {
        $data['dataAgents'] = $dataAgentsA1;
        $data['message'] = 'Success...! les données sont recuperés';
    } else {
        $data['error'] = true;
        $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
    }
}

if ($action == 'tard') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];

    if ($role != 'RH') {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms,
                TO_CHAR(agent.date_de_naissance, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_naissance, 
                TO_CHAR(ADD_MONTHS(agent.date_de_naissance, 720), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_retraite_prevue,
                agent.statut, section.soa_libelle, ministere.ministere_libelle, agent.section_code
                FROM agent 
                LEFT OUTER JOIN section ON agent.section_code = section.section_code
                LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
                WHERE ((MONTHS_BETWEEN(SYSDATE, agent.date_de_naissance) / 12) >= 60)";

         $stmt = $getConnexion->prepare($sql);
    } else {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms,
        TO_CHAR(agent.date_de_naissance, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_naissance, 
        TO_CHAR(ADD_MONTHS(agent.date_de_naissance, 720), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_retraite_prevue,
        agent.statut, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent 
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ((MONTHS_BETWEEN(SYSDATE, agent.date_de_naissance) / 12) >= 60)
        AND users.matricule = :matr";

         $stmt = $getConnexion->prepare($sql);
         $stmt->bindParam(':matr', $matricule);
    }


    $stmt->execute();
    $dataAgentsA1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($dataAgentsA1) {
        $data['dataAgents'] = $dataAgentsA1;
        $data['message'] = 'Success...! les données sont recuperés';
    } else {
        $data['error'] = true;
        $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
    }
}




$getConnexion = null;
echo json_encode($data);
?>

