
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueHighcharts from 'vue-highcharts';
import Highcharts from 'highcharts';

// load these modules as your need
import loadStock from 'highcharts/modules/stock.js';
import loadMap from 'highcharts/modules/map.js';
import loadGantt from 'highcharts/modules/gantt.js';
import loadDrilldown from 'highcharts/modules/drilldown.js';
// some charts like solid gauge require `highcharts-more.js`, you can find it in official document.
import loadHighchartsMore from 'highcharts/highcharts-more.js';
import loadSolidGauge from 'highcharts/modules/solid-gauge.js';

require('highcharts-vue');
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import HighchartsVue from 'highcharts-vue';
import BootstrapVue from 'bootstrap-vue';
import {ServerTable, ClientTable, Event} from 'vue-tables-2';
import VueSwal from 'vue-swal'

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import '../../public/fonts/feather/feather.min.css'
import '../../public/css/theme.min.css'

Vue.use(HighchartsVue);
Vue.use(BootstrapVue);
Vue.use(ClientTable, {}, false, 'bootstrap4', 'default');
Vue.use(ServerTable, {}, false, 'bootstrap4', 'default');
Vue.use(VueSwal)

loadStock(Highcharts);
loadMap(Highcharts);
loadGantt(Highcharts);
loadDrilldown(Highcharts);
loadHighchartsMore(Highcharts);
loadSolidGauge(Highcharts);

Vue.use(VueHighcharts, { Highcharts });

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('welcome-component', require('./components/WelcomeComponent.vue').default);
Vue.component('national-dashboard-component', require('./components/NationalDashboardComponent.vue').default);
Vue.component('county-dashboard-component', require('./components/CountyDashboardComponent.vue').default);
Vue.component('uploader-component', require('./components/UploaderComponent.vue').default);
Vue.component('temporary-data-component', require('./components/TemporaryDataComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
