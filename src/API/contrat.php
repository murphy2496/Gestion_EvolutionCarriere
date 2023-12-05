<?php
// recupération des données requêtes
include_once("db.php");

# code...
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Credentials: true');
# code...


$connexion = new Connexion();
$getConnexion = $connexion->Connectar();


$data = array('error' => false);
$action = '';

#recuperation de donnees dans la get
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}


if ($action == 'tout') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];

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
        WHERE statut='CONTRACTUEL'";
         $stmt = $getConnexion->prepare($sql);
    } else {
        $sql = "SELECT agent.agent_matricule,  trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
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
        WHERE statut='CONTRACTUEL' AND users.matricule = :matr";

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


if ($action == 'sixM') {

    $role = $_POST['role'];
    $matricule = $_POST['matricule'];

    if ($role != 'RH') {
        $sql = "SELECT agent.agent_matricule,  trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
                TO_CHAR(agent.poste_agent_date_debut_contrat, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS debut_contrat,
                TO_CHAR(ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS fin_contrat,
                corps.corps_code, grade.grade_code, section.soa_libelle, ministere.ministere_libelle, agent.section_code
                FROM agent
                LEFT OUTER JOIN corps ON agent.corps_code = corps.corps_code
                LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
                LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
                LEFT OUTER JOIN section ON agent.section_code = section.section_code
                WHERE statut='CONTRACTUEL'
                AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= ADD_MONTHS(SYSDATE, 6)
                AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) >= SYSDATE";
         $stmt = $getConnexion->prepare($sql);
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
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= ADD_MONTHS(SYSDATE, 6)
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) >= SYSDATE
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
        $sql = "SELECT agent.agent_matricule,  trim(agent.nom)||' '||trim(agent.prenom) as noms, agent.statut,
        TO_CHAR(agent.poste_agent_date_debut_contrat, 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS debut_contrat,
        TO_CHAR(ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS fin_contrat,
        corps.corps_code, grade.grade_code, section.soa_libelle, ministere.ministere_libelle, agent.section_code
        FROM agent
        LEFT OUTER JOIN corps ON agent.corps_code = corps.corps_code
        LEFT OUTER JOIN grade ON agent.grade_code = grade.grade_code
        LEFT OUTER JOIN ministere ON agent.code_ministere =  ministere.ministere_code
        LEFT OUTER JOIN section ON agent.section_code = section.section_code
        WHERE statut='CONTRACTUEL'
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= SYSDATE";
         $stmt = $getConnexion->prepare($sql);
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
        AND ADD_MONTHS(agent.poste_agent_date_debut_contrat, 24) <= SYSDATE
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