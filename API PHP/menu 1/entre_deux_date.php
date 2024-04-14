<?php

require_once('../cors.php');
include_once('../db-config.php');

$connexion = new Connexion();
$getConnexion = $connexion->Connectar();

try {
    if ($action == 'avenant') {

        $role = $_POST['role'];
        $matricule = $_POST['matricule'];
        $ancien = $_POST['ancien'];
        $recent = $_POST['recent'];


        if ($role != 'RH') {
            $sql = "SELECT agent.agent_matricule,trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
        TO_CHAR(agent.avance_date, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS avance_date,
        TO_CHAR(ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS prochain_date,
        corps.corps_code, grade.grade_code, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN corps ON agent.corps_code = corps.corps_code
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        WHERE ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) BETWEEN  TO_DATE(:ancien, 'YYYY/MM/DD') AND TO_DATE(:recent, 'YYYY/MM/DD')";
            $stmt = $getConnexion->prepare($sql);
            $stmt->bindParam(':ancien', $ancien);
            $stmt->bindParam(':recent', $recent);
        } else {
            $sql = "SELECT agent.agent_matricule,trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
        TO_CHAR(agent.avance_date, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS avance_date,
        TO_CHAR(ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS prochain_date,
        corps.corps_code, grade.grade_code, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN corps ON agent.corps_code = corps.corps_code
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE ADD_MONTHS(agent.avance_date, grade.grade_duree_requise * 12) BETWEEN  TO_DATE(:ancien, 'YYYY/MM/DD') AND TO_DATE(:recent, 'YYYY/MM/DD')
        AND users.matricule = :matr";

            $stmt = $getConnexion->prepare($sql);
            $stmt->bindParam(':ancien', $ancien);
            $stmt->bindParam(':recent', $recent);
            $stmt->bindParam(':matr', $matricule);
        }

        $stmt->execute();
        $dataAgentsA1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($dataAgentsA1) {
            $data['dataAgents'] = $dataAgentsA1;
            $data['message'] = 'Success...! les données sont recuperés';
        } else {
            $data['dataAgents'] =$dataAgentsA1  ;
            $data['error'] = true;
            $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
        }
    }
} catch (PDOException $e) {
    $data['error'] = true;
    $data['message'] = "Erreur de récupération des données : " . $e->getMessage();
}
try{
if ($action == 'contrat') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];
    $ancien = $_POST['ancien'];
    $recent = $_POST['recent'];

    if ($role != 'RH') {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
        TO_CHAR(agent.poste_agent_date_debut_contrat, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS debut_contrat,
        TO_CHAR(ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS fin_contrat,
        corps.corps_code, grade.grade_code, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN corps ON agent.corps_code = corps.corps_code
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) BETWEEN  TO_DATE(:ancien, 'YYYY/MM/DD') AND TO_DATE(:recent, 'YYYY/MM/DD')";

        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':ancien', $ancien);
        $stmt->bindParam(':recent', $recent);

    } else {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
        TO_CHAR(agent.poste_agent_date_debut_contrat, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS debut_contrat,
        TO_CHAR(ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS fin_contrat,
        corps.corps_code, grade.grade_code, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN corps ON agent.corps_code = corps.corps_code
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        JOIN user_section ON agent.section_code = user_section.ref_sec_code
        JOIN users ON user_section.ref_mat_user = users.matricule
        WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) BETWEEN  TO_DATE(:ancien, 'YYYY/MM/DD') AND TO_DATE(:recent, 'YYYY/MM/DD')
        AND users.matricule = :matr";

        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':ancien', $ancien);
        $stmt->bindParam(':recent', $recent);
        $stmt->bindParam(':matr', $matricule);
    }

    $stmt->execute();
    $dataAgentsA1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($dataAgentsA1) {
        $data['dataAgents'] = $dataAgentsA1;
        $data['message'] = 'Success...! les données sont recuperés';
    } else {
        $data['dataAgents'] =$dataAgentsA1  ;
        $data['error'] = true;
        $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
    }
}
} catch (PDOException $e) {
    $data['error'] = true;
    $data['message'] = "Erreur de récupération des données : " . $e->getMessage();
}

try{
if ($action == 'retraite') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];
    $ancien = $_POST['ancien'];
    $recent = $_POST['recent'];

    if ($role != 'RH') {
        $sql = "SELECT agent.agent_matricule, trim(agent.nom)||' '||trim(agent.prenom) as noms,
        TO_CHAR(agent.date_de_naissance, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_naissance,
        TO_CHAR(ADD_MONTHS(agent.date_de_naissance, 720), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_de_retraite_prevue,
        agent.statut, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        LEFT OUTER JOIN ministere ON agent.code_ministere = ministere.ministere_code
        WHERE ADD_MONTHS(agent.date_de_naissance, 720) BETWEEN TO_DATE(:ancien, 'YYYY/MM/DD') AND TO_DATE(:recent, 'YYYY/MM/DD')";

        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':ancien', $ancien);
        $stmt->bindParam(':recent', $recent);

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
        WHERE ADD_MONTHS(agent.date_de_naissance, 720) BETWEEN TO_DATE(:ancien, 'YYYY/MM/DD') AND TO_DATE(:recent, 'YYYY/MM/DD')
        AND users.matricule = :matr";

        $stmt = $getConnexion->prepare($sql);
        $stmt->bindParam(':ancien', $ancien);
        $stmt->bindParam(':recent', $recent);
        $stmt->bindParam(':matr', $matricule);
    }

    $stmt->execute();
    $dataAgentsA1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($dataAgentsA1) {
        $data['dataAgents'] = $dataAgentsA1;
        $data['message'] = 'Success...! les données sont recuperés';
    } else {
        $data['dataAgents'] =$dataAgentsA1  ;
        $data['error'] = true;
        $data['message'] = 'Failed...! les Ddonnées sont pas recuperés';
    }
}
} catch (PDOException $e) {
    $data['error'] = true;
    $data['message'] = "Erreur de récupération des données : " . $e->getMessage();
}


$getConnexion = null;
echo json_encode($data);
?>