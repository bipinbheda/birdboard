// import './bootstrap';

import Vue from 'vue';
import VModal from 'vue-js-modal';
// import axios from 'axios';


// const axios = require('axios').default;
// let axios = require('axios')




Vue.use(VModal);

Vue.component('new-project-modal', require('./components/NewProjectModal.vue').default);

new Vue({
    el: '#app'
});
