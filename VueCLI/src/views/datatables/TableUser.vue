<template>
    <div>
        <v-data-table :headers="headers" :items="dataUser" item-value="id" :items-per-page="6">
            <template #[`item.actions`]="{ item }">
                <div>
                    <!--<v-icon color="info" @click.prevent="editItem(item)">mdi-book-edit</v-icon>-->
                    <v-icon color="error" @click.prevent="deleteItem(item)">mdi-delete</v-icon>
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-icon color="green" v-bind="props"> mdi-dots-vertical </v-icon>
                        </template>
                        <v-list style="padding: 1px;">
                            <v-list-item style="padding: 5px;">
                                <button  @click="showSection(item)" style="padding: 1px ;">Section</button>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>
        </v-data-table>
        <ModalSection ref="ModalRef" :selectedUser="selectedUser"></ModalSection>
    </div>
</template>

<script>
import { onMounted, ref, nextTick } from 'vue';
import ModalSection from '../userRH/ModalSection.vue';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Swal from "sweetalert2";
import { accountService } from "@/_service";

export default {
  data() {
    return {
      headers: [
        { title: "Matricule", key: "matricule" },
        { title: "Nom", key: "nom" },
        { title: "Prénom", key: "prenom" },
        { title: "Rôle", key: "role" },
        { title: "", key: "actions", sortable: false },
      ],
      dataUser: [],
    };
  },
  components: {
    ModalSection,
  },
  mounted() {
    this.getAllUser();
  },
  methods: {
    async getAllUser () {
      try {
        const res = await accountService.onAllUser();
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);
          this.dataUser = res.data.dataUser;
          console.log(res.data.dataUser);
        }
      } catch (err) {
        console.error(err);
      }
    },
    async editItem (item) {
      localStorage.setItem("edit-user", JSON.stringify(item.columns));
      this.$router.push("/user/edit");
    },
    async deleteItem (item) {
      var donnee = new FormData();
      donnee.append("id", item.columns.matricule);

      try {
        const result = await Swal.fire({
          icon: "question",
          text: `Voulez-vous supprimer ${item.columns.nom} ${item.columns.prenom} ?`,
          cancelButtonText: "Annuler",
          showCancelButton: true,
          confirmButtonColor: "blue",
          cancelButtonColor: "#e9061c",
          confirmButtonText: "Oui, Supprimer-le!",
        });

        if (result.isConfirmed) {
          const res = await accountService.deleteUser(donnee);

          if (res.data.error) {
            Swal.fire({
              title: "Suppression!",
              title: "Erreur !!",
              icon: "error",
              showConfirmButton: false,
              timer: 1000,
            });
          } else {
            Swal.fire({
              title: "Suppression!",
              title: "Supprimé",
              icon: "success",
              showConfirmButton: false,
              timer: 1000,
            });
            this.getAllUser();
          }
        }
      } catch (err) {
        console.log(err);
      }
    },
  },
  setup() {
    let myModal;
    let selectedUser = ref(null);

    onMounted(() => {
      myModal = new bootstrap.Modal(document.getElementById('section'));
    });

    const ModalRef = ref(null);
    const showSection = async (item) => {
      selectedUser.value = item.raw;
      await nextTick(); // Attendre que Vue mette à jour les données
      ModalRef.value.openModal();
    };

    return {
      ModalRef,
      showSection,
      selectedUser,
    };
  },
};
</script>

<style></style>
  