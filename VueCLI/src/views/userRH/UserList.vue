<template>
   <div class="card " >
      <div class="col-md-12">
         <div class="table-wrapper">
            <div class="table-title ">
               <div class="row ">
                  <div class="col-sm-6 p-0 d-flex justify-content-lg-start justify-content-center">
                     <h2 class="ml-lg-2 text-white">Gérer les utilisateurs</h2>
                  </div>
                  <div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
                     <RouterLink to="/user/add" class="btn btn-success d-flex justify-content-around align-items-center"
                        data-toggle="modal">
                        <v-icon style=" top:0;">mdi-plus-circle</v-icon> <span>Nouvelle utilisateur</span></RouterLink>
                   
                  </div>
               </div>
            </div>
            <table class="table table-striped table-hover">
               <userTable/>
            </table>
         </div>
      </div>

   </div>
</template>

<script>

import userTable from "../datatables/TableUser.vue";
//import sousHeader from "@/components/sousHeader.vue";

export default {
   name: 'UserList',
   components:{
      userTable,//sousHeader
   },
   methods:{

   }

}
</script>


<style>
.main-content {
   padding: 0;
}

.btn {
   color: #fff;
   float: right;
   font-size: 12px;
   border: none;
   min-width: 40px;
   border-radius: 2px;
   border: none;
   outline: none !important;
   margin-left: 10px;
}

.table-title{
		   background:#353b48;
		   color:#fff;
		   position:sticky;
		   top:0;
		   width:100%;
		   left:0;
		   padding:10px 30px;
		   border-radius:0px 0px 0 0;
	   }
.table-wrapper {
   background: #fff;
   padding: 5px 25px;
   margin: 10px 0px 30px 0px;
   width: 100%;
   overflow: auto;
   border-radius: 3px;
   box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
}</style>