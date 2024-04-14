<template>
  <div class="row p-4">
    <div class="col">
      <div class="card" style="min-height: 485px">
        <div class="card-header card-header-text">
          <h4 class="card-title">Statistique des Agents par catégorie</h4>
        </div>
        <div class="card-content table-responsive">
          <canvas
            id="goodCanvas1"
          ></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-5 col-md-12">
    <div class="card" style="min-height: 485px; max-height: 200px;">
      <div class="card-header card-header-text">
        <h4 class="card-title">Nombres d'agents par section </h4>
      </div>
      <div class="card-content">
        <div class="streamline">
          <div class="sl-item sl-primary" v-for="(section, index) in paginatedSections" :key="index">
            <div class="sl-content">
              <p>{{ section.section_code }} - {{ section.soa_libelle }}</p>
              <strong>{{ section.nombre_agents }}</strong>
            </div>
          </div>
        </div>
      </div>
      <div class="pagination-buttons">
        <button @click="prevPage" :disabled="currentPage === 1" class="prev">Précédent</button>
        <span>{{ currentPage }} / {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="next">Suivant</button>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';
import { onMounted } from "vue";
import { accountService } from "@/_service";
export default {
  name: "Graphe",
  data() {
    return {
      sections: [],
      role: "",
      currentPage: 1,
      itemsPerPage: 3, 
    };
  },

  computed: {
    paginatedSections() {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return this.sections.slice(startIndex, endIndex);
    },
    totalPages() {
      return Math.ceil(this.sections.length / this.itemsPerPage);
    },
  },

  mounted() {
    this.getAgentSection();
    this.function();
  },

  methods : {
    
    async function() {
      const user = JSON.parse(localStorage.getItem("user-info"));
      this.role = user[0].role;
    },

    async getAgentSection() {
      accountService.AgentSection().then(res => {
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("succès 25!");

          if (this.role === "admin") {

            this.sections = res.data.agent_section;
          } else if (this.role === "RH") {
            const use = JSON.parse(localStorage.getItem("user-info"));
            var donnee = new FormData();
            donnee.append('matricule', use[0].matricule);

            accountService.allUserSection(donnee).then(reponse => {
              if (reponse.data.error) {
                console.log("error 105...!");
              } else {
                console.log("succès 105!");

                this.sections = res.data.agent_section.filter(agentSection => {
                  return reponse.data.section.includes(agentSection.section_code);
                });
              }
            }).catch(err => { console.log(err) });

          }

        }
      }).catch(err => { console.log(err) });
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },

  },

  setup() {
    const getData = async () => {
      const dataUsers = [];
      const labels = [];
      try {
        const res = await accountService.getNbChart();
        for (let i = 0; i < res.data.dataUser.length; i++) {
          labels.push(res.data.dataUser[i].categorie_code);
        }
        for (let i = 0; i < res.data.dataUser.length; i++) {
          var tab = parseInt(res.data.dataUser[i].nombre);
          dataUsers.push(tab);
        }
        console.log(dataUsers);
        console.log(labels);
      } catch (err) {
        console.error(err);
      }
      const data = {
        labels: labels,
        datasets: [
          {
            label: "Nombre des agents dans catégorie",
            data: dataUsers,
            borderWidth: 1,
            backgroundColor: [
              "#12ABC4",
              "#17FF00",
              "#FF8364",
              "#128364",
              "#128FF4",
              "#128A54",
              "#128364",
              "#1C7364",
              "#129964",
              "#F8364F",
              "#12DC64",
              "#1283EE",
              "#1EE964",
              "#128364",
            ],
            borderColor: ["#12ABC4"],
          },
        ],
      };
      const config = {
        type: "line",
        data: data,
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      };
      const myChart = new Chart(document.getElementById("goodCanvas1"), config);
      return myChart;
    };

    onMounted(() => {
      getData();
    });
  },
};
</script>
<style scoped>
.pagination-buttons {
  position: relative;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: white; 
  padding: 10px;
  border-top: 1px solid #ccc;
}

.pagination-buttons button {
  padding: 5px 10px;
  cursor: pointer;
  outline: none; 
}

.pagination-buttons button {
  padding: 5px 10px;
  cursor: pointer;
  color: #3498db;
}

</style>