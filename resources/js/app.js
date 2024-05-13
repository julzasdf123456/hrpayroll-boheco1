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
import FilesList from "./components/employees/files-list.vue"
import LeaveBalancesBatchEdit from "./components/employees/leave-balances-batch-edit.vue"
import Reeve from "./components/reeve/reeve.vue"
import GeneratePayroll from "./components/payroll/generate-payroll.vue"
import MultiplePayrollDeductions from "./components/payroll/multiple-payroll-deductions.vue"
import AddonsAndDeductions from "./components/payroll/addons-and-deductions.vue"
import WithholdingTaxes from "./components/payroll/withholding-taxes.vue"
import Contributions from "./components/payroll-sundries/contributions.vue"
import IncentivesAnnualProjection from "./components/bonuses/incentives-annual-projection.vue"
import ThirteenthMonthPay from "./components/incentives/thirteenth-month-pay.vue"
import OtherBonuses from "./components/incentives/other-bonuses.vue"
import YearEndBonuses from "./components/incentives/year-end-bonuses.vue"
import AllLeave from "./components/my-account/all-leave.vue"
import AttachBohecoAccount from "./components/my-account/attach-boheco-account.vue"
import PersonalInfo from "./components/my-account/personal-info.vue"
import StaffManagement from "./components/my-account/staff-management.vue"
import AttendanceIndex from "./components/my-account/attendance-index.vue"
import Overtime from "./components/my-account/overtime.vue"
import SuperViewAttendance from "./components/my-account/super-view-attendance.vue"
import AddDependents from "./components/my-account/add-dependents.vue"
import WithholdingTaxesView from "./components/my-account/withholding-taxes-view.vue"
import TreeView from "./components/positions/tree-view.vue"
import EmployeeFinder from "./components/common/employee-finder.vue"
import AllGRS from "./components/trip-tickets/all-grs.vue"

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
app.component('staff-management', StaffManagement);
app.component('leave-balances-batch-edit', LeaveBalancesBatchEdit);
app.component('attendance-index', AttendanceIndex);
app.component('overtime', Overtime);
app.component('super-view-attendance', SuperViewAttendance);
app.component('add-dependents', AddDependents);
app.component('withholding-taxes-view', WithholdingTaxesView);
app.component('tree-view',TreeView);
app.component('employee-finder',EmployeeFinder);
app.component('all-grs', AllGRS);

app.mount("#app");