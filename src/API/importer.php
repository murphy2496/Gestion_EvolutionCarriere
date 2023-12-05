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

    if (isset($_GET['action'])) {
      $action = $_GET['action'];
    }

    if ($action == 'vider') {
        $sql_truncate = "TRUNCATE table agent";
        $stmt_truncate = $getConnexion->prepare($sql_truncate);

        if ($stmt_truncate->execute()) {
            $data['message'] = 'ok ! table agent vide';
        } else {
            $data['error'] = true;
            $data['message'] = 'Erreur ! table agent non vide';
        }

    }


    if ($action == "add") {  
        $Agent = json_decode($_POST['DataAgent'], true); 
        $successCount = 0;

        $getConnexion->beginTransaction();  // démarrer séquence d'une ou plusieurs opérations 

        $sql_insert = "INSERT INTO agent VALUES (:POSTE_AGENT_NUMERO, :AGENT_MATRICULE, :NOM, :PRENOM,
       TO_DATE(:DATE_DE_NAISSANCE, 'MM/DD/YYYY'), :CIN, :SEXE, :STATUT, :CORPS_CODE, :GRADE_CODE, :CATEGORIE_CODE,
        :INDICE, :HEE_CODE, :HEE_CATEGORIE, :SECTION_CODE, :FIV_CODE, :SANCTION_CODE, :SOA,
        TO_DATE(:POSTE_AGENT_DATE_DEBUT_CONTRAT, 'MM/DD/YYYY'), TO_DATE(:POSTE_AGENT_DATE_FIN_CONTRAT, 'MM/DD/YYYY'),
        TO_DATE(:AVANCE_DATE, 'MM/DD/YYYY'), :REG_CODE, :CODE_MINISTERE)";

        $stmt_insert = $getConnexion->prepare($sql_insert);

        $pageSize = 50; 

        $i = 0;
        $totalAgents = count($Agent);

        function formatMMDDYYYY($date) {
            $dateTime = DateTime::createFromFormat('m/d/Y', $date);
            return $dateTime ? $dateTime->format('m/d/Y') : $date;
        }

        while ($i < $totalAgents) {
            $lot = array_slice($Agent, $i, $pageSize);

            foreach ($lot as $DataAgent) {
                $DataAgent['DATE_DE_NAISSANCE'] = formatMMDDYYYY($DataAgent['DATE_DE_NAISSANCE']);
                $DataAgent['POSTE_AGENT_DATE_DEBUT_CONTRAT'] = formatMMDDYYYY($DataAgent['POSTE_AGENT_DATE_DEBUT_CONTRAT']);
                $DataAgent['POSTE_AGENT_DATE_FIN_CONTRAT'] = formatMMDDYYYY($DataAgent['POSTE_AGENT_DATE_FIN_CONTRAT']);
                $DataAgent['AVANCE_DATE'] = formatMMDDYYYY($DataAgent['AVANCE_DATE']);

                Parametres($stmt_insert, $DataAgent);

                if (!$stmt_insert->execute()) {
                    $data['error'] = true;
                    $data['message'] = 'Échec de l\'enregistrement';
                    break;
                } else {
                    $successCount++;
                }
            }

            if ($data['error']) { 
                break;
            }

            $i += $pageSize;
        }

        if (!$data['error']) {
            if ($successCount == $totalAgents) {
                $data['message'] = 'Tous les enregistrements ont été insérés avec succès';
            } else {
                $data['error'] = true;
                $data['message'] = 'Certains enregistrements n\'ont pas été insérés';
            }

            $getConnexion->commit(); // Valider la transaction
        } else {
            $getConnexion->rollBack(); // Annuler la transaction en cas d'erreur
        }

        $data['successCount'] = $successCount;
    }

    function Parametres($stmt, $DataAgent) {
        $stmt->bindParam(':POSTE_AGENT_NUMERO', $DataAgent['POSTE_AGENT_NUMERO']);
        $stmt->bindParam(':AGENT_MATRICULE', $DataAgent['AGENT_MATRICULE']);
        $stmt->bindParam(':NOM', $DataAgent['NOM']);
        $stmt->bindParam(':PRENOM', $DataAgent['PRENOM']);
        $stmt->bindParam(':DATE_DE_NAISSANCE', $DataAgent['DATE_DE_NAISSANCE']);
        $stmt->bindParam(':CIN', $DataAgent['CIN']);
        $stmt->bindParam(':SEXE', $DataAgent['SEXE']);
        $stmt->bindParam(':STATUT', $DataAgent['STATUT']);
        $stmt->bindParam(':CORPS_CODE', $DataAgent['CORPS_CODE']);
        $stmt->bindParam(':GRADE_CODE', $DataAgent['GRADE_CODE']);
        $stmt->bindParam(':CATEGORIE_CODE', $DataAgent['CATEGORIE_CODE']);
        $stmt->bindParam(':INDICE', $DataAgent['INDICE']);
        $stmt->bindParam(':HEE_CODE', $DataAgent['HEE_CODE']);
        $stmt->bindParam(':HEE_CATEGORIE', $DataAgent['HEE_CATEGORIE']);
        $stmt->bindParam(':SECTION_CODE', $DataAgent['SECTION_CODE']);
        $stmt->bindParam(':FIV_CODE', $DataAgent['FIV_CODE']);
        $stmt->bindParam(':SANCTION_CODE', $DataAgent['SANCTION_CODE']);
        $stmt->bindParam(':SOA', $DataAgent['SOA']);
        $stmt->bindParam(':POSTE_AGENT_DATE_DEBUT_CONTRAT', $DataAgent['POSTE_AGENT_DATE_DEBUT_CONTRAT']);
        $stmt->bindParam(':POSTE_AGENT_DATE_FIN_CONTRAT', $DataAgent['POSTE_AGENT_DATE_FIN_CONTRAT']);
        $stmt->bindParam(':AVANCE_DATE', $DataAgent['AVANCE_DATE']);
        $stmt->bindParam(':REG_CODE', $DataAgent['REG_CODE']);
        $stmt->bindParam(':CODE_MINISTERE', $DataAgent['CODE_MINISTERE']);
    }

    if ($action == 'date_import') {
        $sql = "SELECT TO_CHAR(MAX(date_dernier), 'DD Month YYYY', 'NLS_DATE_LANGUAGE = FRENCH') AS date_dernier FROM DERNIER_IMPORT";
        $stmt = $getConnexion->prepare($sql);

        if ($stmt->execute()) {
            $info = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($info)) {
                $data['message'] = 'date trouvé';
                $data['Date_dernier_import'] = $info[0];
            } else {
                $data['error'] = true;
                $data['message'] = 'aucune date trouvé';
            }
        } else {
            $data['error'] = true;
            $data['message'] = 'erreur lors de l\'exécution de la requête';
        }
    }

    $getConnexion = null;
    echo json_encode($data);

?>