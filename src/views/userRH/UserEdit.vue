<template>
   <div class="card " >
      <div class="col-md-12">
         <div class="table-wrapper">
            <div class="table-title mb-3">
               <div class="row ">
                  <div class="d-flex justify-content-center justify-content-center">
                     <h2 class="ml-lg-2 text-white">Information d'un Utilisateur </h2>
                  </div>
               </div>
            </div>
            <form class="row g-3">
               <div class="col-12">
                  <div class="form-floating mb-3">
                     <input type="text" class="form-control" v-model="user.matricule" id="floatingInput"
                        placeholder="name@example.com" autocomplete="address-level7" >
                     <label for="floatingInput"> Matricule</label>
                  </div>
               </div>
               <div class="col-12">
                  <div class="form-floating">
                     <input type="text" class="form-control"  v-model="user.nom" id="floatingPassword"
                        placeholder="Password" autocomplete="address-level4" >
                     <label for="floatingPassword">Nom</label>
                  </div>
               </div>
               <div class="col-12">
                  <div class="form-floating mb-3">
                     <input type="text" class="form-control" v-model.trim="user.prenom" id="floatingInput"
                        placeholder="name@example.com" autocomplete="address-level6" >
                     <label for="floatingInput">Prenom</label>
                  </div>
               </div>
              
               <div class="d-flex align-items-md-start">
                  <button class="btn btn-lg btn-primary" @click.prevent="getModif()" type="button">Enregistrer</button>
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
export default{
   name:'UserEdit',
   components:{
      sousHeader
   },
   data() {
      return {
         user: {
            matricule: '',
            nom: '',
            prenom: '',
            role: '',
         },     
      }
   },
   mounted(){
      this.setModif();
   },
   methods:{
      setModif(){
         const userEdit= JSON.parse(localStorage.getItem('edit-user'));
         console.log(userEdit);
         this.user.matricule=userEdit.matricule;
         this.user.nom=userEdit.nom;
         this.user.prenom=userEdit.prenom;
         this.user.role=userEdit.role;

      },
      getModif(){
         const userEdit= JSON.parse(localStorage.getItem('edit-user'));
         var donnee = new FormData();
         if (this.user.matricule != '' && this.user.nom != '' && this.user.prenom != ''  && this.user.role != '' ) {

               donnee.append('id_mat', userEdit.matricule);
               donnee.append('matricule', this.user.matricule);
               donnee.append('nom', this.user.nom.toUpperCase());
               donnee.append('prenom', this.user.prenom);
               donnee.append('role', this.user.role);


               accountService.editUser(donnee).then((res) => {
                  if (res.data.error) {
                     console.log("error 1...!", res.data.message);

                  } else {
                     console.log("success 1...!", res.data.message);
                     localStorage.removeItem('edit-user');
                     this.$router.push('/user/list')
                  }
               }).catch((err) => { console.log(err) });

           


         } else {
            console.log('remplire formulaire d authentification')
            this.validTout = true;
         }
         
      }
   }
}
</script>