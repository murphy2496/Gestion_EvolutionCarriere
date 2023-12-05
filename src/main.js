import { createApp } from 'vue'
import { loadFonts } from './plugins/webfontloader'

import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import Vuelidate from 'vuelidate'


import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';
import 'mdbootstrap/css/bootstrap.min.css';
import 'mdbootstrap/js/bootstrap.min.js';


import 'popper.js';
import 'jquery';

import './css/style.css';
import './css/custom.css';

loadFonts()

createApp(App)
  .use(router)
  .use(store)
  .use(Vuelidate)
  .use(vuetify)
  .mount('#app')
