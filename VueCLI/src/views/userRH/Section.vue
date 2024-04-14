<template>
   <div class="card-sec mx-auto d-flex justify-content-center align-items-center  m-4 mt-10">
      <div class="col-md-12 m-0 p-0">
         <div class="table-wrapper m-0 p-0">
            <div class="table-title mb-3">
               <div class="row ">
                  <div class="d-flex justify-content-center justify-content-center">
                     <h2 class="ml-lg-2 text-white">Selectionner les Sections </h2>
                  </div>
               </div>
            </div>
            <form class="row g-3 p-3">
               <div class=" scrollable-list">
                  <ul class="list-group " v-for="(data, index) in dataSection" :key="index">
                     <li>
                        <div class="col-12">
                           <input type="checkbox" class="check mr-4" :value="data.section_code" v-model="section"
                              placeholder="name@example.com">
                           <label for="floatingInput"><strong>({{ data.section_code }})-</strong> {{ data.soa_libelle
                           }}</label>
                        </div>
                     </li>
                  </ul>
               </div>

               <hr class="dropdown-divider" />
               <div class="d-flex align-items-md-start">
                  <button class="btn btn-lg btn-outline-primary" @click.prevent="enregistre()" type="button">Enregistrer</button>
                  <RouterLink to="/user/list" class="btn btn-lg btn-danger" type="button">Annuler</RouterLink>
               </div>
            </form>
         </div>
      </div>

   </div>
</template>

<script>

import { accountService } from "@/_service";
import Swal from 'sweetalert2';
export default {
   name: 'UserAdd',

   data() {
      return {
         dataSection: [],
         section: [],
         matricule: ''
      }
   },
   mounted() {
      this.getSection();
   },
   methods: {
      enregistre() {
         var donnee = new FormData();
         this.matricule = this.$route.params.id;
         console.log( 'nombre length section',this.section);
         console.log( 'nom matricule',  this.matricule);

         if (this.section != '' && this.matricule != '') {

               donnee.append('matricule', this.matricule);
               donnee.append('section', JSON.stringify(this.section));

                     accountService.setSection(donnee).then((res) => {
                        if (res.data.error) {
                           console.log("error 1...!", res.data.message);

                        } else {
                           Swal.fire({
                                    title: 'Enregistré !!',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500,
                                 })
                                 this.$router.push('/user/list')
                        }
                     }).catch((err) => { console.log(err) });
                  
                     
         } else {
            alert('Veuillez remplir les cases  à cocher')
         }

      },
      getSection() {
         accountService.allSection().then(res => {
            if (res.data.error) {
               console.log("error 1...!", res.data.message);
            } else {
               console.log("success 1...!", res.data.message);
               this.dataSection = res.data.dataSection;
               console.log(this.dataSection);
            }
         }).catch(err => { console.log(err) });
      }

   }
}
</script>
<style>
.card-sec {
   width: 70%;
}

.scrollable-list {
   max-height: 500px;
   overflow: auto;
}</style>