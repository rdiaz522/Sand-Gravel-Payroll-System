/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');
 window.Vue = require('vue').default;
 import Swal from 'sweetalert2';
 import Multiselect from 'vue-multiselect';
 import VueTimepicker from 'vue2-timepicker';
 Vue.use(VueTimepicker);
 Vue.component('multiselect', Multiselect);
 Vue.use(require('vue-resource'));
 Vue.prototype.$SWAL = Swal;
 Vue.prototype.$BASE_URL = window.location.origin;
 Vue.prototype.$EMPLOYEES = '/employees';
 Vue.prototype.$EMPLOYEETYPE = '/employeeTypes';
 Vue.prototype.$POSITION = '/position';
 Vue.prototype.$LOCATION = '/location';
 Vue.prototype.$BREAKHOURS = '/breakhours';
 Vue.prototype.$EMPLOYEETIMELOGS = '/timelogs';
 Vue.prototype.$CASHADVANCEDEDUCTION = '/cashadvancededuction';
 Vue.prototype.$CASHDEDUCTION = '/cashdeductions';
 Vue.prototype.$CONTRIBUTION = '/contribution';
 Vue.prototype.$REPORTS = '/reports';
 Vue.prototype.$EXPENSES = '/expenses';
 Vue.prototype.$USERS = '/users';
 Vue.prototype.$SHOW_LOADING = () => {  
     $('#cover-spin').show();
 }
 
 Vue.prototype.$HIDE_LOADING = () => {
     $('#cover-spin').hide();
 }
 
 Vue.prototype.$SHOW_MESSAGE = (header,message,type) => {
     Swal.fire(header, message, type);
 }
 const swalWithBootstrapButtons = Swal.mixin({
     customClass: {
         confirmButton: 'btn btn-success',
         cancelButton: 'btn btn-danger'
     },
     buttonsStyling: false
     })
 
 Vue.prototype.$WARNING_MESSAGE = swalWithBootstrapButtons;
 
// Vue.component('app-component', require('./components/Models/AppComponent.vue').default);

 /**
  * The following block of code may be used to automatically register your
  * Vue components. It will recursively scan this directory for the Vue
  * components and automatically register them with their "basename".
  *
  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
  */
 
 // const files = require.context('./', true, /\.vue$/i)
 // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
 
 /**
  * Next, we will create a fresh Vue application instance and attach it to
  * the page. Then, you may begin adding components to this application
  * or customize the JavaScript scaffolding to fit your unique needs.
  */
 
import VueRouter from 'vue-router'
import Vue from 'vue';
   
  Vue.use(VueRouter)
     
  const routes = [
      { 
          path: '/users',
          name: 'Home',
          component: require('./components/Models/AppComponent.vue').default
      },
      { 
        path: '/payroll',
        name: 'Payroll',
        component: require('./components/Models/AppPayrollComponent.vue').default
     },
     { 
        path: '/settings',
        name: 'Setting',
        component: require('./components/Models/Settings/AppSettingsComponent.vue').default
     },
     { 
        path: '/cashadvance',
        name: 'CashAdvance',
        component: require('./components/Models/CashAdvance/AppCashAdvanceComponent.vue').default
     },
     { 
        path: '/cashdeduction',
        name: 'CashDeduction',
        component: require('./components/Models/CashDeduction/AppCashDeductionComponent.vue').default
     },
     { 
        path: '/cashcontribution',
        name: 'Cash Contribution',
        component: require('./components/Models/Contribution/AppContributionComponent.vue').default
     },
     { 
        path: '/report',
        name: 'Reports',
        component: require('./components/Models/Reports/AppReportComponent.vue').default
     },
     { 
        path: '/expense',
        name: 'Expenses',
        component: require('./components/Models/Expenses/AppExpensesComponent.vue').default
     },
  ]
    
  const router = new VueRouter({
      mode: 'history',
      routes 
  })
 
 const app = new Vue({
     el: '#app',
     router
 });
 