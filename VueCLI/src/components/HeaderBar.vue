<template>
  <!-- Navbar -->

  <div class="top-navbar">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid ">
        <div class="xp-breadcrumbbar d-flex text-center">
          <ol class="breadcrumb align-items-center">           
            <li>             
                <v-icon style="top: 0;margin-right: 10px;" size="35">mdi-chevron-left</v-icon>
            </li>
            <li class="breadcrumb-item" v-for="item in breadcrumbs" :key="item" :href="item.active ? '' : item.path"
              :active="item.active">
              {{ item.name }}

            </li>
            <li>
              
              <h6 style="margin: 0px 0px 0px 150px;" class="date">Dernière mise à jour :  <span >{{ storedDate }}</span></h6>
             
            </li>
          </ol>
        </div>

        <button class="d-inline-block d-lg-none ml-auto more-button " type="button" data-toggle="collapse"
          data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <v-icon style="top: 0;" size="35">mdi-dots-vertical</v-icon>

        </button>
         
        <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none"
          id="navbarSupportedContent">
          <ul class="nav navbar-nav ml-auto">
          
            <li class="dropdown nav-item border-user">
              <a href="#" class="nav-link text-white d-flex justify-content-around align-items-center "
                data-toggle="dropdown">
                <v-icon size="30" class="mr-3">mdi-account-circle</v-icon>
                <u>{{ this.nomConnecter }}</u>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <RouterLink class="dropdown-item d-flex justify-content-around align-items-center " to="/profile">
                    <v-icon> mdi-account</v-icon>Mon Profile</RouterLink>
                </li>
                <li v-if="ajouter">
                  <RouterLink class="dropdown-item d-flex justify-content-around align-items-center " to="/user/list">
                    <v-icon> mdi-account-group</v-icon>Nouvelle RH
                  </RouterLink>
                </li>
                <li v-if="ajouter">
                  <RouterLink class="dropdown-item d-flex justify-content-around align-items-center " to="/Import">
                    <v-icon>mdi-file-excel</v-icon> Import Data
                  </RouterLink>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                  <a class="dropdown-item d-flex justify-content-around align-items-center " href="#"
                    @click.prevent="deconnecter()">
                    <v-icon> mdi-logout</v-icon>Se déconnecter</a>
                </li>


              </ul>
            </li>

          </ul>
        </div>


        <!-- Avatar -->

      </div>
    </nav>
  </div>

  <!-- Navbar -->
</template>

<script>
import { onMounted, ref } from 'vue'
import router from '@/router'
import { accountService } from "@/_service";
//import Swal from 'sweetalert2';
export default {
  name: "HeaderBar",
  setup() {
    const breadcrumbs = ref()

    const getBreadcrumbs = () => {
      return router.currentRoute.value.matched.map((route) => {
        return {
          active: route.path === router.currentRoute.value.fullPath,
          name: route.name,
          path: `${router.options.history.base}${route.path}`,
        }
      })
    }

    router.afterEach(() => {
      breadcrumbs.value = getBreadcrumbs()
    })

    onMounted(() => {
      breadcrumbs.value = getBreadcrumbs()
    })

    return {
      breadcrumbs,
    }
  },
  data() {
    return {
      ajouter: false,
      nomConnecter: '',
      storedDate: '',
    };
  },
  mounted() {
    const user = JSON.parse(localStorage.getItem("user-info"));
    this.nomConnecter = user[0].nom + ' ' + user[0].prenom;
    if (user[0].role == 'admin') {
      this.ajouter = true;
    }

    this.displayStoredDate();
  },

  methods: {

    deconnecter() {
      localStorage.clear();
      this.$router.push({ name: 'Login' });
    },

    async displayStoredDate() {
      accountService.dernierImport().then(res => {
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          //console.log("succès 1..!", res.data);
          this.storedDate = res.data.Date_dernier_import;
        }
      }).catch(err => { console.log(err) });
    },

  },

}
</script>

<style>
.csv {
  font-size: 16px;
  display: flex;
  cursor: pointer;
  align-items: center;
  border: 1px solid #007bff;
  background-color: #007bff;
}

.csv:hover {
  border: 1px solid #4485ca;
  background-color: #36806c;

}

.border-user {
  border: 1px solid #198754;
}

.border-user:hover {
  background-color: #0f8364;
}
.mrg{
    margin: 0 5px;
}

.date {
  font-size: 18px;
  background: linear-gradient(135deg, rgb(157, 243, 157), #d8ddd8);
  -webkit-background-clip: text;
  color: transparent;
  background-clip: text;
 
}

</style>