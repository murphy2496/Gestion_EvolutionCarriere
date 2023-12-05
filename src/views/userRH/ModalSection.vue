<template>
  <!-- Modal -->
  <div class="modal fade" id="section" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--Header-->
        <div class="modal-header">
          <input type="search" class="form-control"   placeholder="Sélectionner les sections" v-model="search" @input="filterSections">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!--Header-->
        <!--Body-->
        <div class="modal-body custom-scroll">
          <div style="font-size: 14px;" class="form-floating" v-for="(data, index) in sectionsFiltrées" :key="index">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" :value="data.section_code" v-model="section" />
              <label class="form-check-label"><strong>({{ data.section_code }})-</strong>{{ data.soa_libelle }}</label>
            </div>
          </div>
        </div>
        <!--Body-->
        <!--Footer-->
        <div class="modal-footer">
          <button class="btn btn-outline-primary" data-dismiss="modal" @click="enregistre()">Enregistrer</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
        </div>
        <!--Footer-->
      </div>
    </div>
  </div>
  <!-- Modal -->
</template>

<script>
import { accountService } from "@/_service";
import Swal from 'sweetalert2';
import $ from 'jquery';

export default {
  name: 'ModalSection',
  props: {
    selectedUser: Object,
  },
  data() {
    return {
      localUser: {
        matricule: '',
      },
      dataSection: [],
      section: [],
      search: '',
      sectionsFiltrées: [],
    };
  },

  mounted() {
    this.getSection();
  },

  computed: {
    filteredDataSection() {
      return this.dataSection.filter((data) =>
        data.soa_libelle.toLowerCase().includes(this.search.toLowerCase()) ||
        data.section_code.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },

  methods: {
    async openModal() {
      $('#section').modal('show');
      if (this.selectedUser) {
        this.localUser = {
          matricule: this.selectedUser.matricule,
        };
      }
      await this.getUserSection();
    },

    getSection() {
      accountService.allSection().then(res => {
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1..78.!", res.data.message);
          this.dataSection = res.data.dataSection;
          console.log(this.dataSection);
          // Initialiser les sections filtrées avec toutes les données
          this.sectionsFiltrées = this.dataSection;
        }
      }).catch(err => { console.log(err) });
    },

    async getUserSection() {
      var donnee = new FormData();
      donnee.append('matricule', this.localUser.matricule);
      accountService.allUserSection(donnee).then(res => {
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("succès 45!", res.data.message);
          this.section = res.data.section;
          console.log("success yes...!", res.data);
        }
      }).catch(err => { console.log(err) });
    },

    async enregistre() {
      var donnee = new FormData();
      donnee.append('matricule', this.localUser.matricule);
      donnee.append('section', JSON.stringify(this.section));

      accountService.setSection(donnee).then((res) => {
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1..45.!", res.data.message);
          Swal.fire({
            title: 'Enregistré !!',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
          })
        }
      })
        .catch((err) => { console.log(err) });
    },

    filterSections() {
      this.sectionsFiltrées = this.filteredDataSection;
    },
  },
};
</script>

<style scoped>
  .custom-scroll {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
  }
  .custom-scroll::-webkit-scrollbar {
    width: 6px;
    background-color: #ccc;
    border-radius: 6px;
  }
  .custom-scroll::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 6px;
  }
  .custom-scroll::-webkit-scrollbar-thumb:hover {
    background-color: #555;
  }
  
</style>
