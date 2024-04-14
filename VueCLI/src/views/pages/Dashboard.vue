
<template>
    <div class="card  m-0 p-4  ">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 ">
            
            <div class="col ">
                <div class="border border-success card-stats shadow">
                    <div class="card-header pr-0">
                        <div class="icon icon-success">
                            <v-icon style="top: 0;" size="40">mdi-ansible</v-icon>
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="category"> Avancement expiré</p>
                        <h5 class="card-title">{{ getRowA1 }} Agents</h5>
                    </div>
                    <div class="card-footer ">
                        <div class="stats ">
                            <RouterLink to="/avancement" @click.prevent="actionAvenantTard()"
                                class="text-black nav-link  d-flex justify-content-between align-items-center p-0">
                                Voir tout
                                <v-icon style="top: 0;" size="30">mdi-chevron-right</v-icon>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ">
                <div class="border border-success card-stats shadow">
                    <div class="card-header pr-0">
                        <div class="icon icon-success">
                            <v-icon style="top: 0;" size="40">mdi-ansible</v-icon>
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="category">Bientôt expiré</p>
                        <h5 class="card-title">{{ getRowA2 }} Agents</h5>
                    </div>
                    <div class="card-footer ">
                        <div class="stats ">
                            <RouterLink to="/avancement" @click.prevent="actionAvenant6M()"
                                class="text-black nav-link  d-flex justify-content-between align-items-center p-0">
                                Voir tout
                                <v-icon style="top: 0;" size="30">mdi-chevron-right</v-icon>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col ">
                <div class="border border-info card-stats shadow">
                    <div class="card-header pr-0">
                        <div class="icon icon-info">
                            <v-icon style="top: 0;" size="40">mdi-alpha-c-box</v-icon>
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="category">Contrat achevé</p>
                        <h5 class="card-title">{{ getRowC1 }} Agents</h5>
                    </div>
                    <div class="card-footer ">
                        <div class="stats ">
                            <RouterLink to="/contractuel" @click.prevent="actionContratTard()"
                                class="text-black nav-link  d-flex justify-content-between align-items-center p-0">
                                Voir tout
                                <v-icon style="top: 0;" size="30">mdi-chevron-right</v-icon>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ">
                <div class="border border-info card-stats shadow">
                    <div class="card-header pr-0">
                        <div class="icon icon-info">
                            <v-icon style="top: 0;" size="40">mdi-alpha-c-box</v-icon>
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="category">Bientôt expiré</p>
                        <h5 class="card-title">{{ getRowC2 }} Agents</h5>
                    </div>
                    <div class="card-footer ">
                        <div class="stats ">
                            <RouterLink to="/contractuel" @click.prevent="actionContrat6M()"
                                class="text-black nav-link  d-flex justify-content-between align-items-center p-0">
                                Voir tout
                                <v-icon style="top: 0;" size="30">mdi-chevron-right</v-icon>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col ">
                <div class="border border-warning card-stats shadow">
                    <div class="card-header pr-0">
                        <div class="icon icon-warning">
                            <v-icon style="top: 0;" size="40">mdi-alpha-r-box</v-icon>
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="category">Retraite</p>
                        <h5 class="card-title">{{ getRowR1 }} Agents</h5>
                    </div>
                    <div class="card-footer ">
                        <div class="stats ">
                            <RouterLink to="/retraite" @click.prevent="actionRetraiteTard()"
                                class="text-black nav-link  d-flex justify-content-between align-items-center p-0">
                                Voir tout
                                <v-icon style="top: 0;" size="30">mdi-chevron-right</v-icon>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <Graphe />
</template>

<script>

import { mapActions , mapGetters} from "vuex";
import Graphe from "@/components/Graphe.vue";
export default {
    name: 'DashBoard',
    components: {
        Graphe,
    }, 
    data(){
        return{
            RowA1:null,
        }
    },
    mounted() {
        this.actionRowCount();
        
    },
    computed: {
        ...mapGetters(['getRowA1', 'getRowA2','getRowC1', 'getRowC2', 'getRowR1', 'getRowR2'])
    },

    methods: {
        ...mapActions([
            'actionAvenantTout', 'actionAvenant6M', 'actionAvenantTard',
            'actionContratTout', 'actionContrat6M', 'actionContratTard',
            'actionRetraite1A', 'actionRetraiteTard',
            'actionRowCount'
        ]),

    }
}
</script>
<style>
.text-black,
.stats {
    width: 100%;
}

.coll {
    width: 200px;
}</style>