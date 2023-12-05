import { createStore } from 'vuex'
import { accountService } from "@/_service";

export default createStore({
  state: {
    dataAvenant: null,
    dataContrat: null,
    dataRetraite: null,
    title:null,
    rowAvenant1:null,
    rowAvenant2:null,
    rowContrat1:null,
    rowContrat2:null,
    rowRetraite1:null,
    rowRetraite2:null,
  },
  getters: {
    getAvenant(state) {
      return state.dataAvenant
    },
    getContrat(state) {
      return state.dataContrat
    },
    getRetraite(state) {
      return state.dataRetraite
    },
    getTitre(state){
      return state.title
    },
    getRowA1(state){
      return state.rowAvenant1
    },
    getRowA2(state){
      return state.rowAvenant2
    },
    getRowC1(state){
      return state.rowContrat1
    },
    getRowC2(state){
      return state.rowContrat2
    },
    getRowR1(state){
      return state.rowRetraite1
    },
    getRowR2(state){
      return state.rowRetraite2
    }
  },
  mutations: {
    setAvenant(state, value) {
      state.dataAvenant = value; 
    },
    setContrat(state, value) {
      state.dataContrat = value;
    },
    setRetraite(state, value) {
      state.dataRetraite = value;
    },
    setTitle(state, value) {
      state.title = value;
    },

    // count row for Avancement
    setRowAvenant1(state, value){
      state.rowAvenant1 = value;
    },
    setRowAvenant2(state, value){
      state.rowAvenant2 = value;
    },

    // count row for Contractuel
    setRowContrat1(state, value){
      state.rowContrat1 = value;
    }, 
    setRowContrat2(state, value){
      state.rowContrat2 = value;
    },

    // count row for Retraite
    setRowRetraite1(state, value){
      state.rowRetraite1 = value;
    },
    setRowRetraite2(state, value){
      state.rowRetraite2 = value;
    }


  },
  actions: {

    //Actions commit dans mutations des Avancement   
    async actionAvenantTout({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allAvenantComplet(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.dataAgents);
          commit('setTitle', 'Liste de tous les agents');
          commit('setAvenant', res.data.dataAgents)
          
        }
      } catch (err) {
        console.log(err);
      }
    },
    async actionAvenant6M({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allAvenant6M(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);
          commit('setTitle', 'Liste agents avancement expirant dans 6 mois');
          commit('setAvenant', res.data.dataAgents);
          
        }
      } catch (err) {
        console.log(err);
      }
    },
    async actionAvenantTard({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allAvenantTard(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);

          commit('setTitle', 'Les agents dont avancement est déjà expiré');
          commit('setAvenant', res.data.dataAgents);
          
        }
      } catch (err) {
        console.log(err);
      }
    },

  
    //Actions commit dans mutations des Contrat
    async actionContratTout({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allContratComplet(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);

          commit('setTitle', 'Liste de tous les agents contractuels');
          commit('setContrat', res.data.dataAgents);
        }
      } catch (err) {
        console.log(err);
      }
    },
    async actionContrat6M({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allContrat6M(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);
          console.log("row 1:", res.data.count);
          commit('setTitle', 'Liste des agents qui doit renouveller son contrat');
          commit('setContrat', res.data.dataAgents);
       
        }
      } catch (err) {
        console.log(err);
      }
    },
    async actionContratTard({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allContratTard(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);
          console.log("row 1:", res.data.count);
          commit('setTitle', 'Les agents dont le contrat est déjà expiré');
          commit('setContrat', res.data.dataAgents);
         
        }
      } catch (err) {
        console.log(err);
      }
    },


    //Actions commit dans mutations des Retraite
    async actionRetraite1A({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allRetraite1A(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);
          console.log("row 1:", res.data.count);
          commit('setTitle', 'liste des agents dont la retraite approche');
          commit('setRetraite', res.data.dataAgents);
          
        }
      } catch (err) {
        console.log(err);
      }
    },
    async actionRetraiteTard({ commit }) {
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allRetraiteTard(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);
        } else {
          console.log("success 1...!", res.data.message);
          commit('setTitle', 'Liste des agents qui doit prendre sa retraite');
          commit('setRetraite', res.data.dataAgents);
          
        }
      } catch (err) {
        console.log(err);
      }
    },


    //Actions commit dans mutations des rowcount
    async actionRowCount({ commit }){
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();

      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);

      try {
        const res = await accountService.allRowCount(donnee);
       
          commit('setRowAvenant1', res.data.count1[0]);
          commit('setRowAvenant2', res.data.count2[0]);
          commit('setRowContrat1', res.data.count3[0]);
          commit('setRowContrat2', res.data.count4[0]);
          commit('setRowRetraite1', res.data.count5[0]);
          commit('setRowRetraite2', res.data.count6[0]);
        
      } catch (err) {
        console.log(err);
      }
    },
    async actionRechercheAvenant({ commit} ,date){
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();
      console.log(date.recent);
      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);
      donnee.append('ancien', date.ancien);
      donnee.append('recent', date.recent);
      
      try {
        const res = await accountService.rechercheDateAvenant(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.dataAgents);
          commit('setAvenant', res.data.dataAgents)
        } else {
          console.log("success 1...!", res.data.dataAgents);  
          commit('setAvenant', res.data.dataAgents)
        }
        
      } catch (err) {
        console.log('erreur exception',err);
      }
    },
    async actionRechercheContrat({ commit} ,date){
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();
      console.log(date.recent);
      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);
      donnee.append('ancien', date.ancien);
      donnee.append('recent', date.recent);
      
      try {
        const res = await accountService.rechercheDateContrat(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message); 
          commit('setContrat', res.data.dataAgents);
        } else {
          console.log("success 1...!", res.data.message);  
          commit('setContrat', res.data.dataAgents);
        }
        
        
      } catch (err) {
        console.log('erreur exception',err);
      }
    },
    async actionRechercheRetraite({ commit} ,date){
      const user = JSON.parse(localStorage.getItem("user-info"));
      var donnee = new FormData();
      console.log(date.recent);
      donnee.append('role', user[0].role);
      donnee.append('matricule', user[0].matricule);
      donnee.append('ancien', date.ancien);
      donnee.append('recent', date.recent);
      
      try {
        const res = await accountService.rechercheDateRetraite(donnee);
        if (res.data.error) {
          console.log("error 1...!", res.data.message);   
          commit('setRetraite', res.data.dataAgents);
        } else {
          console.log("success 1...!", res.data.message);  
          commit('setRetraite', res.data.dataAgents);
        }
        
        
      } catch (err) {
        console.log('erreur exception',err);
      }
    },
    
    
  },
  modules: {
  }
})
