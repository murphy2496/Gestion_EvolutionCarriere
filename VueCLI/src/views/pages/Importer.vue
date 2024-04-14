<template>
    <v-app>
    <v-container>
      <v-row justify="center">
        <v-col cols="12" sm="8" md="6">
          <v-card>
            <v-card-title class="text-h5">Importation des données Excel</v-card-title>
            <v-card-text>
              <v-file-input @change="handleFileChange" label="Sélectionner un fichier Excel"
                accept=".xls, .xlsx">
              </v-file-input>
              <v-btn  style="background-color: #0277BD; color: blanchedalmond; margin-left: 40px;" :disabled="!selectedFile"
                @click.prevent="importData()">Importer</v-btn>
              <v-btn  style="background-color: #E53935; color: blanchedalmond; margin-left: 15px;"
                @click.prevent="viderTable()">Réinitialiser</v-btn>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { accountService } from "@/_service";
import XLSX from 'xlsx';
import Swal from 'sweetalert2';
export default {
    name: 'PageImportation',
    data() {
        return {
          selectedFile: null,
        }
    },

    mounted(){
        
    },

    methods:{

      handleFileChange(event) {
        this.selectedFile = event.target.files[0];

        const reader = new FileReader();

        reader.onload = (e) => {
          try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });

            const sheet = workbook.Sheets[workbook.SheetNames[0]];

            const enTetesAttendus = [ "POSTE_AGENT_NUMERO", "AGENT_MATRICULE", "NOM", "PRENOM", "DATE_DE_NAISSANCE",
                                      "CIN", "SEXE", "STATUT", "CORPS_CODE", "GRADE_CODE", "CATEGORIE_CODE", "INDICE",
                                      "HEE_CODE", "HEE_CATEGORIE", "SECTION_CODE", "FIV_CODE", "SANCTION_CODE", "SOA",
                                      "POSTE_AGENT_DATE_DEBUT_CONTRAT", "POSTE_AGENT_DATE_FIN_CONTRAT", "AVANCE_DATE",
                                      "REG_CODE", "CODE_MINISTERE"
                                    ];

            // Obtenir les en-têtes de colonnes réelles du fichier Excel (première ligne)
            const enTetesReelles = Object.keys(sheet).map(key => sheet[key].v);

            console.log('En-têtes attendus :', enTetesAttendus);
            console.log('En-têtes réelles :', enTetesReelles);

            const enTetesManquants = enTetesAttendus.filter(enTete => !enTetesReelles.includes(enTete));

            if (enTetesManquants.length > 0) {
                Swal.fire({
                  icon: 'error',
                  title: 'Erreur',
                  text: `Les colonnes suivantes sont manquantes : ${enTetesManquants.join(', ')}`,
                });

                this.selectedFile = null;
                return; // arrêt et vider la variable selectedFile
            }
            const jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]], { defval: null });
            console.log(jsonData);

            console.log('En-têtes valides. Continuer avec le reste du processus d\'importation...');

          } catch (error) {
            console.error('Erreur lors de la lecture du fichier Excel :', error);
          }
        };

        reader.readAsArrayBuffer(this.selectedFile);
      },

      async importData() {
        if (this.selectedFile && XLSX) {
          
          const reader = new FileReader();

          const loadingSwal = Swal.fire({
            text: 'Traitement des données en cours ...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
              Swal.showLoading();
            }
          });

          reader.onload = async (e) => {
            try {
                const workbook = XLSX.read(e.target.result, { type: 'binary' });

                const jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]], { defval: null });

                const pageSize = 100;  // taille du lot
                const paginatedData = this.paginateData(jsonData, pageSize);

                for (const dataChunk of paginatedData) { // itère sur chaque morceau 

                    // console.log("DataChunk:", dataChunk);
                    await this.sendDataToServer(dataChunk);
                }

                loadingSwal.close();

                Swal.fire({
                  icon: 'success',
                  title: 'succès',
                  text: 'Toutes les données ont été importées avec succès!',
                });

            } catch (error) {
                loadingSwal.close();
                Swal.fire({
                  icon: 'error',
                  title: 'Erreur',
                  text: 'Une erreur s\'est produite lors de l\'importation des données.',
                });
                console.error('Erreur lors de la lecture du fichier Excel :', error);
            }
          };

          reader.readAsBinaryString(this.selectedFile);
        } else {
          console.error('Aucun fichier sélectionné ou bibliothèque xlsx non disponible.');
        }
      },

      paginateData(data, pageSize) { // divise en morceaux 
        const paginatedData = [];
        for (let i = 0; i < data.length; i += pageSize) {
          const dataChunk = data.slice(i, i + pageSize);
          paginatedData.push(dataChunk);
        }
        return paginatedData;
      },

      async sendDataToServer(jsonData) {
        const donnee = new FormData();
        donnee.append('DataAgent', JSON.stringify(jsonData));

        try {
            const res = await accountService.addImport(donnee);
            if (res.data.error) {
              console.log("🚫 Erreur non non...!", res.data.message);
            } else {
              console.log("✅ Succès oui oui...!", res.data);
            }
        } catch (err) {
            console.log(err);
        }
      },

      async viderTable() {
        try {
              const result = await Swal.fire({
              icon: "question",
              text: `Êtes-vous sûr de vouloir supprimer toutes les données des agents actuels ?`,
              cancelButtonText: "Annuler",
              showCancelButton: true,
              confirmButtonColor: "blue",
              cancelButtonColor: "#e9061c",
              confirmButtonText: "Oui, supprimez tout !",
              });

              if (result.isConfirmed) {
                  accountService.viderTable().then((res) => {
                            if (res.data.error) {
                              console.log("🚫 Erreur non vide...!", res.data.message);
                              Swal.fire({
                                title: "Suppression!",
                                title: "Erreur !!",
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1000,
                              });
                            } else {
                              console.log("✅ Succès vide...!", res.data.message);
                              Swal.fire({
                                title: "Suppression!",
                                title: "Tout est effacé !",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000,
                              });
                            }
                       }).catch((err) => {
                            console.log(err);
                       });
              }
        } catch (err) {
              console.log(err);
        }
                    
      },

  },

}

</script>

<style lang="scss" scoped>

</style>