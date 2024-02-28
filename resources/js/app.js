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
import Contributions from "./components/payroll-sundries/contributions.vue"
import MultiplePayrollDeductions from "./components/payroll/multiple-payroll-deductions.vue"
import IncentivesAnnualProjection from "./components/bonuses/incentives-annual-projection.vue"
import AddonsAndDeductions from "./components/payroll/addons-and-deductions.vue"
import WithholdingTaxes from "./components/payroll/withholding-taxes.vue"
import ThirteenthMonthPay from "./components/incentives/thirteenth-month-pay.vue"
import OtherBonuses from "./components/incentives/other-bonuses.vue"
import YearEndBonuses from "./components/incentives/year-end-bonuses.vue"
import AllLeave from "./components/my-account/all-leave.vue"
import AttachBohecoAccount from "./components/my-account/attach-boheco-account.vue"
import FilesList from "./components/employees/files-list.vue"
import PersonalInfo from "./components/my-account/personal-info.vue"

const app = createApp({
    
});

app.use(jQuery);

app.component('employees-search', EmployeesSearch);
app.component('reeve', Reeve);
app.component('generate-payroll', GeneratePayroll);
app.component('contributions', Contributions);
app.component('multiple-payroll-deductions', MultiplePayrollDeductions);
app.component('incentives-annual-projection', IncentivesAnnualProjection);
app.component('addons-and-deductions', AddonsAndDeductions);
app.component('withholding-taxes', WithholdingTaxes);
app.component('thirteenth-month-pay', ThirteenthMonthPay);
app.component('other-bonuses', OtherBonuses);
app.component('year-end-bonuses', YearEndBonuses);
app.component('all-leave', AllLeave);
app.component('attach-boheco-account', AttachBohecoAccount);
app.component('files-list', FilesList);
app.component('personal-info', PersonalInfo);

app.mount("#app");