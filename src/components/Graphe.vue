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
      <div class="card" style="min-height: 485px">
        <div class="card-header card-header-text">
          <h4 class="card-title">Activities</h4>
        </div>
        <div class="card-content">
          <div class="streamline">
            <div class="sl-item sl-primary">
              <div class="sl-content">
                <small class="text-muted">5 mins ago</small>
                <p>Williams has just joined Project X</p>
              </div>
            </div>
            <div class="sl-item sl-danger">
              <div class="sl-content">
                <small class="text-muted">25 mins ago</small>
                <p>Jane has sent a request for access to the project folder</p>
              </div>
            </div>
            <div class="sl-item sl-success">
              <div class="sl-content">
                <small class="text-muted">40 mins ago</small>
                <p>Kate added you to her team</p>
              </div>
            </div>
            <div class="sl-item">
              <div class="sl-content">
                <small class="text-muted">45 minutes ago</small>
                <p>John has finished his task</p>
              </div>
            </div>
            <div class="sl-item sl-warning">
              <div class="sl-content">
                <small class="text-muted">55 mins ago</small>
                <p>Jim shared a folder with you</p>
              </div>
            </div>
            <div class="sl-item">
              <div class="sl-content">
                <small class="text-muted">60 minutes ago</small>
                <p>John has finished his task</p>
              </div>
            </div>
          </div>
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
    return {};
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