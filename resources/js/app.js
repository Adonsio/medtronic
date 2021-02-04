/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import VueAWN from "vue-awesome-notifications"

// Your custom options
let options = {};

Vue.use(VueAWN, options);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('order-component', require('./components/OrderComponent.vue').default);
Vue.component('order-individual-component', require('./components/OrderIndividualComponent.vue').default);
Vue.component('edit-order', require('./components/EditOrder.vue').default);
Vue.component('edit-individual-order', require('./components/EditIndividualOrder.vue').default);
Vue.component('outstanding-delivery', require('./components/OutstandingDelivery.vue').default);
Vue.component('analyse', require('./components/Analyse.vue').default);
Vue.component('invoice-table', require('./components/InvoiceTable.vue').default);
Vue.component('chart', require('./components/Chart.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
