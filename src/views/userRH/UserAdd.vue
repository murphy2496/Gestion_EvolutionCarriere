<template>
   <div class="card " >
      <div class="col-md-12">
         <div class="table-wrapper">
            <div class="table-title mb-3">
               <div class="row ">
                  <div class="d-flex justify-content-center justify-content-center">
                     <h2 class="ml-lg-2 text-white">Formulaire de création d'un nouvel utilisateur </h2>
                  </div>
               </div>
            </div>
            <form class="row g-3">
               <div class="col-12">
                  <div class="form-floating mb-3">
                     <input type="text" class="form-control" v-model="user.matricule" id="floatingInput"
                        placeholder="">
                     <label for="floatingInput"> Matricule</label>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-floating">
                     <input type="text" class="form-control" style="text-transform:capitalize;" v-model="user.nom" id="floatingPassword"
                        placeholder="">
                     <label for="floatingPassword">Nom</label>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-floating mb-3">
                     <input type="text" class="form-control" v-model.trim="user.prenom" id="floatingInput"
                        placeholder="">
                     <label for="floatingInput">Prenom</label>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-floating">
                     <input type="password" class="form-control" :class="{ 'is-invalid': this.valid }"  
                        v-model="user.motdepasse" id="floatingInput" placeholder="">
                     <label for="floatingInput">Mot de passe</label>
                     <div class="invalid-feedback">Verifiez votre mot de pas si correct! </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-floating mb-3">
                     <input type="password" class="form-control" :class="{ 'is-invalid': this.valid }" v-model="pwdConfirm"
                        id="floatingInput" placeholder="">
                     <label for="floatingInput">Confirmer mot de passe</label>
                     <div class="invalid-feedback">Verifiez votre mot de passe si correct! </div>
                  </div>
               </div>
               <!-- <div class="col-md-6 mb-3">
                  <div class="form-floating">
                     <select class="form-select" id="floatingSelect" v-model="user.role"
                        aria-label="Floating label select example">
                        <option selected value="RH">RH</option>
                     </select>
                     <label for="floatingSelect">Rôle</label>
                  </div>
               </div> -->
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
import sousHeader from "@/components/sousHeader.vue";

export default {
   name: 'UserAdd',
   components:{
      sousHeader
   },
   data() {
      return {
         user: {
            matricule: '',
            nom: '',
            prenom: '',
            motdepasse: '',
            role: 'RH',
         },
         pwdConfirm: '',
         valid:false,
       
      }
   },
   methods: {
      enregistre() {
         let PRENOM = this.user.prenom.charAt(0).toUpperCase() + this.user.prenom.slice(1);
         var donnee = new FormData();
         if (this.user.matricule != '' && this.user.nom != '' && this.user.prenom != '' && this.user.motdepasse != '' && this.user.role != '' && this.pwdConfirm != '') {
            
            if (this.user.motdepasse == this.pwdConfirm) {
               donnee.append('matricule', this.user.matricule);
               donnee.append('nom', this.user.nom.toUpperCase());
               donnee.append('prenom', PRENOM);
               donnee.append('role', this.user.role);
               donnee.append('password', this.user.motdepasse);

               accountService.setUser(donnee).then((res) => {
                  if (res.data.error) {
                     console.log("error 1...!", res.data.message);

                  } else {
                     console.log("success 1...!", res.data.message);
                     this.$router.push(`/user/section/${this.user.matricule}`)
                  }
               }).catch((err) => { console.log(err) });

            } else {
               this.valid = true;
            }


         } else {
            alert('Veuillez remplir les champs du formulaire')
            console.log('remplire formulaire d authentification')
            //this.valid = true;
         }
         // #####################################

      }
   }
}
</script>