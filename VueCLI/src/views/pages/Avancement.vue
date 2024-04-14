<template>
    <div class="card mt-0  p-3">
        <div class="row g-5">
            <div class="col-md-6">
                <h4 class="toUpper">{{ getTitre }}</h4>
            </div>
            <div class="col-md-6">
                <div class="xp-searchbar">
                    <form>
                        <div class="input-group" style="width:100%;">
                            <input type="date" class="form-control" v-model="date.ancien" placeholder="Date Debut">
                            <input type="date" class="form-control" v-model="date.recent" placeholder="Date Fin">
                            <div class="input-group-append">
                                <button class="btn" @click.prevent="recherche()" id="button-addon2">RECHERCHE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-xxl">
        <div class="card p-3">
            <DatatableAvenant />
        </div>
    </div>
</template>

<script>
import { mapGetters , mapActions } from "vuex";
import sousHeader from "@/components/sousHeader.vue";
import DatatableAvenant from '@/views/datatables/DatatableAvenant.vue';
export default {
    name: 'Avancement',
    components: {
        DatatableAvenant, sousHeader,
    },
    data(){
        return{
            date:{
                ancien: null,
                 recent: null
            }
           
        }
    },
    computed: {
        ...mapGetters(['getTitre']),
    },
    methods:{
        ...mapActions(['actionRechercheAvenant']),
        recherche(){
         this.actionRechercheAvenant(this.date);
        }
    }

}
</script>