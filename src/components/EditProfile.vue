<template>
    <!-- Modal -->
    <div class="modal fade text-dark" id="modalCenter"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-dark" id="exampleModalLongTitle">INFORMATION DE L'UTILISATEUR</h2>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row g-3">
                        <!-- matricule -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" v-model="user.matricule" id="floatingInput"
                                    placeholder="">
                                <label for="floatingInput"> Matricule</label>
                            </div>
                        </div>
                        <!-- mot de passe ancienne -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" :class="{ 'is-invalid': this.validAncien }"
                                    v-model="motdepasse.ancien" id="floatingInput" placeholder="">
                                <label for="floatingInput">Ancien mot de passe</label>
                                <div class="invalid-feedback">Verifiez votre mot de pas si correct! </div>
                            </div>
                        </div>
                        <!-- nom -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" style="text-transform:capitalize;"
                                    v-model="user.nom" id="floatingPassword" placeholder="">
                                <label for="floatingPassword">Nom</label>
                            </div>
                        </div>

                        <!-- mot de passe nouvelle -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" :class="{ 'is-invalid': this.valid }"
                                    v-model="motdepasse.nouveaux" id="floatingInput" placeholder="">
                                <label for="floatingInput">Nouveaux mot de passe</label>
                                <div class="invalid-feedback">Verifiez votre mot de pas si correct! </div>
                            </div>
                        </div>
                        <!-- prenom -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" v-model.trim="user.prenom" id="floatingInput"
                                    placeholder="">
                                <label for="floatingInput">Prenom</label>
                            </div>
                        </div>
                        <!-- mot de passe de comfirmation -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" :class="{ 'is-invalid': this.valid }"
                                    v-model="motdepasse.confirme" id="floatingInput" placeholder="">
                                <label for="floatingInput">Confirmation mot de passe</label>
                                <div class="invalid-feedback">Verifiez votre mot de pas si correct! </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                    <button type="button" @click.prevent="enregistrer()" class="btn btn-success">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { accountService } from "@/_service";
import $ from 'jquery';
export default {
    name: 'EditProfile',
    props: {
        dataInfo: Object,
        getUserInfo: Function,
    },
    data() {
        return {
        
            user: {
                matricule: '',
                nom: '',
                prenom: '',
                password: '',
                role: '',
            },
            motdepasse: {
                ancien: '',
                nouveaux: '',
                confirme: ''
            },
            valid: false,
            validAncien: false,
        }
    },
    mounted() {
        
    },
    methods: {
        async openModal() {
            $('#modalCenter').modal('show');
            if (this.dataInfo) {
                this.user = {
                    matricule: this.dataInfo.matricule,
                    nom: this.dataInfo.nom,
                    prenom: this.dataInfo.prenom,
                    password: this.dataInfo.motdepasse,
                    role: this.dataInfo.role,
                };
                console.log(this.user)
            }
        }, 

        async closeModal() {
            $('#modalCenter').modal('hide');
        },

        async updateLocalStorage() {
            const updatedUserInfo = [{
                matricule: this.user.matricule,
                nom: this.user.nom,
                prenom: this.user.prenom,
                password: this.user.password,
                role: this.user.role,
            }];
            localStorage.setItem("user-info", JSON.stringify(updatedUserInfo));
        },

        async enregistrer() {
            const users = JSON.parse(localStorage.getItem("user-info"));
            var donnee = new FormData();

            if (this.motdepasse.ancien == '' && this.motdepasse.nouveaux == '' && this.motdepasse.confirme == '') {
                donnee.append('id_mat', users[0].matricule);
                donnee.append('matricule', this.user.matricule);
                donnee.append('nom', this.user.nom);
                donnee.append('prenom', this.user.prenom);
                try {
                    const res = await accountService.setUserEdit(donnee);
                    if (res.data.error) {
                        console.log("error 1...!", res.data.message);
                    } else {
                        console.log("success yes...!", res.data);
                        this.updateLocalStorage();
                        this.closeModal();
                        this.getUserInfo();
                    }
                } catch (err) {
                    console.log(err);
                }
            } else {
                donnee.append('id_mat', users[0].matricule);
                donnee.append('matricule', this.user.matricule);
                donnee.append('nom', this.user.nom);
                donnee.append('prenom', this.user.prenom);
                donnee.append('password', this.motdepasse.nouveaux);
                if (this.motdepasse.ancien == this.user.password) {
                    if (this.motdepasse.nouveaux == this.motdepasse.confirme) {
                        try {
                            const res = await accountService.setUserEdit(donnee);
                            if (res.data.error) {
                                console.log("error 1...!", res.data.message);
                            } else {
                                console.log("success ok...!", res.data);
                                this.updateLocalStorage();
                                this.closeModal();
                                this.getUserInfo();
                            }
                        } catch (err) {
                            console.log(err);
                        }
                    } else {
                        this.valid = true;
                        this.validAncien = false;
                    }
                } else {
                    this.validAncien = true;
                }

            }

        },

    }
}
</script>
<style></style>