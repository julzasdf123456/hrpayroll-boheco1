import './bootstrap';

/**
 * DATE RANGE PICKER
 */
import jQuery from 'jquery';
import $ from 'jquery';
import 'bootstrap';
import moment from 'moment';
import daterangepicker from 'daterangepicker';

/**
 * VUE VITE
 */

import { createApp } from 'vue/dist/vue.esm-bundler.js';

import EmployeesSearch from "./components/employees/search.vue"
import Reeve from "./components/reeve/reeve.vue"
import GeneratePayroll from "./components/payroll/generate-payroll.vue"

const app = createApp({});

app.use(jQuery);

app.component('employees-search', EmployeesSearch);
app.component('reeve', Reeve);
app.component('generate-payroll', GeneratePayroll);

app.mount("#app");