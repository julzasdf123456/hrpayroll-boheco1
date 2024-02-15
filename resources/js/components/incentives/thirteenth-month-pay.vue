<template>
    <div class="row" style="margin-left: 10px;">
        <!-- FORM -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <span class="text-muted">Employee Type</span>
                            <select v-model="employeeType" class="form-control form-control-sm">
                                <option value="Regular">Regular</option>
                                <option value="Probationary">Probationary</option>
                                <option value="Contractual">Contractual</option>
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <span class="text-muted">Department</span>
                            <select v-model="department" class="form-control form-control-sm">
                                <option value="ESD">ESD</option>
                                <option value="ISD">ISD</option>
                                <option value="OGM">OGM</option>
                                <option value="OSD">OSD</option>
                                <option value="PGD">PGD</option>
                                <option value="SEEAD">SEEAD</option>
                                <option value="SUB-OFFICE">SUB-OFFICE</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <span class="text-muted">Term</span>
                            <select v-model="term" class="form-control form-control-sm">
                                <option :value="may">{{ moment(may).format('MMMM YYYY') }}</option>
                                <option :value="october">{{ moment(october).format('MMMM YYYY') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-7">
                            <span class="text-muted">Action</span><br>
                            <button class="btn btn-default btn-sm ico-tab-mini" :disabled="isPreviewButtonDisabled" @click="getData()"><i class="fas fa-eye ico-tab-mini"></i>Preview</button>
                            <button class="btn btn-primary btn-sm" :disabled="isGenerateButtonDisabled" @click="submit13thMonth()"><i class="fas fa-check-circle ico-tab-mini"></i>Submit 13th Month</button>
        
                            <div class="spinner-border text-success float-right" :class="loaderDisplay" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SHOW IF EXISTS -->
        <div class="col-lg-12" v-if="incentiveExists">
            <div class="exists">
                <span style="color: aliceblue;"><i class="fas fa-exclamation-triangle ico-tab-mini"></i>13th Month Pay data already exists (Status: <strong>{{ dataStatus }}</strong>)</span>
            </div>
            <a target="_blank" :href="baseURL + '/incentives/view-incentives/' + existingDataSetId" class="btn btn-default btn-sm" style="margin-left: 10px;"><i class="fas fa-eye ico-tab-mini"></i>View 13th Month Data Instead</a>
        </div>

        <!-- TABLE -->
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-xs table-bordered" id="response">
                    <thead>
                        <tr>
                            <th rowspan="2" class='text-center' style="width: 240px;">Employees</th>
                            <th rowspan="2" class='text-center' style="width: 240px;">Position</th>
                            <th rowspan="2" class='text-center'>Basic Salary</th>
                            <th rowspan="2" class='text-center'>Term Wage</th>
                            <!-- 1st Term -->
                            <th colspan="2" class='text-center'>{{ monthHeaders['0'] }}</th>
                            <th colspan="2" class='text-center'>{{ monthHeaders['1'] }}</th>
                            <th colspan="2" class='text-center'>{{ monthHeaders['2'] }}</th>
                            <th colspan="2" class='text-center'>{{ monthHeaders['3'] }}</th>
                            <th colspan="2" class='text-center'>{{ monthHeaders['4'] }}</th>
                            <th colspan="2" class='text-center'>{{ monthHeaders['5'] }}</th>
                            <!-- 2nd Term -->
                            <th colspan="2" class='text-center' v-if="isSecondTermShown">{{ monthHeaders['6'] }}</th>
                            <th colspan="2" class='text-center' v-if="isSecondTermShown">{{ monthHeaders['7'] }}</th>
                            <th colspan="2" class='text-center' v-if="isSecondTermShown">{{ monthHeaders['8'] }}</th>
                            <th colspan="2" class='text-center' v-if="isSecondTermShown">{{ monthHeaders['9'] }}</th>
                            <th colspan="2" class='text-center' v-if="isSecondTermShown">{{ monthHeaders['10'] }}</th>
                            <th colspan="2" class='text-center' v-if="isSecondTermShown">{{ monthHeaders['11'] }}</th>
                            <th rowspan="2" class='text-center' v-if="isSecondTermShown">May Incntv Amnt.</th>
                            <!-- TOTAL -->
                            <th rowspan="2" class="text-center">Sub-Total</th>
                            <th rowspan="2" class="text-center">AR-Others</th>
                            <th rowspan="2" class="text-center">BEMPC</th>
                            <th rowspan="2" class="text-center">NET PAY</th>
                        </tr>
                        <tr>
                            <!-- 1st Term -->
                            <th class='text-center'>1st</th>
                            <th class='text-center'>2nd</th>
                            <th class='text-center'>1st</th>
                            <th class='text-center'>2nd</th>
                            <th class='text-center'>1st</th>
                            <th class='text-center'>2nd</th>
                            <th class='text-center'>1st</th>
                            <th class='text-center'>2nd</th>
                            <th class='text-center'>1st</th>
                            <th class='text-center'>2nd</th>
                            <th class='text-center'>1st</th>
                            <th class='text-center'>2nd</th>
                            <!-- 2nd Term -->
                            <th class='text-center' v-if="isSecondTermShown">1st</th>
                            <th class='text-center' v-if="isSecondTermShown">2nd</th>
                            <th class='text-center' v-if="isSecondTermShown">1st</th>
                            <th class='text-center' v-if="isSecondTermShown">2nd</th>
                            <th class='text-center' v-if="isSecondTermShown">1st</th>
                            <th class='text-center' v-if="isSecondTermShown">2nd</th>
                            <th class='text-center' v-if="isSecondTermShown">1st</th>
                            <th class='text-center' v-if="isSecondTermShown">2nd</th>
                            <th class='text-center' v-if="isSecondTermShown">1st</th>
                            <th class='text-center' v-if="isSecondTermShown">2nd</th>
                            <th class='text-center' v-if="isSecondTermShown">1st</th>
                            <th class='text-center' v-if="isSecondTermShown">2nd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in employees" :key="employee.id">
                            <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                            <td style="min-width: 250px;">{{ employee.Position }}</td>
                            <td class="text-right">{{ employee.SalaryAmount }}</td>
                            <td class="text-right">{{ employee.TermWage }}</td>
                            <!-- 1st Term -->
                            <td class='text-right'>{{ toMoney(employee.JanuaryFirst) }}</td>
                            <td class='text-right'>{{ toMoney(employee.JanuarySecond) }}</td>
                            <td class='text-right'>{{ toMoney(employee.FebruaryFirst) }}</td>
                            <td class='text-right'>{{ toMoney(employee.FebruarySecond) }}</td>
                            <td class='text-right'>{{ toMoney(employee.MarchFirst) }}</td>
                            <td class='text-right'>{{ toMoney(employee.MarchSecond) }}</td>
                            <td class='text-right'>{{ toMoney(employee.AprilFirst) }}</td>
                            <td class='text-right'>{{ toMoney(employee.AprilSecond) }}</td>
                            <td class='text-right'>{{ toMoney(employee.MayFirst) }}</td>
                            <td class='text-right'>{{ toMoney(employee.MaySecond) }}</td>
                            <td class='text-right'>{{ toMoney(employee.JuneFirst) }}</td>
                            <td class='text-right'>{{ toMoney(employee.JuneSecond) }}</td>
                            <!-- 2nd Term -->
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.JulyFirst) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.JulySecond) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.AugustFirst) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.AugustSecond) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.SeptemberFirst) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.SeptemberSecond) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.OctoberFirst) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.OctoberSecond) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.NovemberFirst) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.NovemberSecond) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.DecemberFirst) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.DecemberSecond) }}</td>
                            <td class='text-right' v-if="isSecondTermShown">{{ toMoney(employee.PreviousTermAmount) }}</td>
                            <!-- TOTALS -->
                            <td class='text-right'>{{ toMoney(employee.SubTotal) }}</td>
                            <td>
                                <input style="min-width: 80px;" class="table-input-sm text-right" :class="tableInputTextColor" v-model="employee.AROthers" @keyup.enter="inputEnter(employee.AROthers, employee.id)" @blur="inputEnter(employee.AROthers, employee.id)" type="number" step="any"/>
                            </td>
                            <td class='text-right'>{{ toMoney(employee.BEMPC) }}</td>
                            <td class='text-right'>{{ toMoney(employee.NetPay) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>saved!</p>
    </div>
</template>

<style>
    td .table-input {
        padding: 0px !important;
    }

    .table-input {
        margin: 0px;
        background-color: transparent;
        width: 100%;
        border: 0px;
        height: 26px;
        font-weight: bold;
        font-size: 1.1em;
    }

    .table-input-sm {
        margin: 0px;
        background-color: transparent;
        width: 100%;
        border: 0px;
        height: 22px;
        font-weight: bold;
        font-size: 1em;
    }

    .table-input::-webkit-outer-spin-button,
    .table-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .table-input:focus,
    .table-input-sm:focus  {
        outline: none;
    }

    .table-xs {
        font-size: .82em;
    }
</style>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';
import { parse } from 'vue/compiler-sfc';

export default {
    name : 'ThirteenthMonthPay.thirteenth-month-pay',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            employeeType : 'Regular',
            department : 'OGM',
            year : moment().format("YYYY"),
            may : moment().format('YYYY-05-01'),
            october : moment().format('YYYY-10-01'),
            term : moment().format('YYYY-05-01'),
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            isPreviewButtonDisabled : false,
            isGenerateButtonDisabled : true,
            loaderDisplay : 'gone',
            employees : [],
            monthHeaders : [],
            dataSetGuides : [
                {
                    name : 'JanuaryFirst',
                    value : moment(moment().format('YYYY') + "-01-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JanuarySecond',
                    value : moment(moment().format('YYYY') + "-01-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'FebruaryFirst',
                    value : moment(moment().format('YYYY') + "-02-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'FebruarySecond',
                    value : moment(moment().format('YYYY') + "-02-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'MarchFirst',
                    value : moment(moment().format('YYYY') + "-03-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'MarchSecond',
                    value : moment(moment().format('YYYY') + "-03-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'AprilFirst',
                    value : moment(moment().format('YYYY') + "-04-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'AprilSecond',
                    value : moment(moment().format('YYYY') + "-04-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'MayFirst',
                    value : moment(moment().format('YYYY') + "-05-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'MaySecond',
                    value : moment(moment().format('YYYY') + "-05-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'JuneFirst',
                    value : moment(moment().format('YYYY') + "-06-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JuneSecond',
                    value : moment(moment().format('YYYY') + "-06-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'JulyFirst',
                    value : moment(moment().format('YYYY') + "-07-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JulySecond',
                    value : moment(moment().format('YYYY') + "-07-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'AugustFirst',
                    value : moment(moment().format('YYYY') + "-08-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'AugustSecond',
                    value : moment(moment().format('YYYY') + "-08-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'SeptemberFirst',
                    value : moment(moment().format('YYYY') + "-09-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'SeptemberSecond',
                    value : moment(moment().format('YYYY') + "-09-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'OctoberFirst',
                    value : moment(moment().format('YYYY') + "-10-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'OctoberSecond',
                    value : moment(moment().format('YYYY') + "-10-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'NovemberFirst',
                    value : moment(moment().format('YYYY') + "-11-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'NovemberSecond',
                    value : moment(moment().format('YYYY') + "-11-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'DecemberFirst',
                    value : moment(moment().format('YYYY') + "-12-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'DecemberSecond',
                    value : moment(moment().format('YYYY') + "-12-15").endOf('month').format('YYYY-MM-DD'),
                },
            ],
            isSecondTermShown : false,
            incentiveExists : false,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            dataStatus : null,
            existingDataSetId : null
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
        },
        toMoney(value) {
            if (this.isNumber(value)) {
                return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
            } else {
                return '-'
            }
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        generateRandomString(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }

            return result;
        },
        generateUniqueId() {
            return moment().valueOf() + "-" + this.generateRandomString(32);
        },
        inputEnter(value, employeeId) {
            var type = this.term === moment().format('YYYY-05-01') ? '13th Month Pay - 1st Half' : '13th Month Pay - 2nd Half'
            axios.get(`${ axios.defaults.baseURL }/other_payroll_deductions/update-data`, {
                params: {
                    EmployeeId : employeeId,
                    Amount : value,
                    ScheduleDate : this.term,
                    Type : type,
                    Description : type
                }
            }).then(response => {                
                // FIND EMPTY IDs TO BE REPLACED BY A NEW ID IF NEW ENTRY
                // UPDATE VALUE REAL TIME
                var newValue = 0
                const empArray = this.employees.filter(obj => obj.id === employeeId)

                if (!this.isNull(empArray)) {
                    var subTotal = empArray[0].SubTotal
                    var bempc = empArray[0].BEMPC

                    value = (this.isNumber(value) ? value : 0)
                    subTotal = parseFloat(subTotal)
                    bempc = parseFloat(bempc)
                    newValue = subTotal - (parseFloat(value) + parseFloat(bempc))
                }
                this.employees = this.employees.map(obj => {
                    if (obj.id === employeeId) {
                        return { ...obj, NetPay: newValue }; // Update the name property
                    } else {
                        return obj;
                    }
                })
                this.showSaveFader()
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error adding AR-Others!',
                });
                console.log(error)
            });
        },
        isBeforeAbs(timeToCheck, baseTime) {
            baseTime = moment(baseTime).format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBefore(baseTime);
        },
        getBiMonthlyWage(salaryData, basicSalary, term, month) {
            salaryData = salaryData[0]
            if (this.isNull(salaryData)) {
                if (moment(month).isBefore(moment().format('YYYY-MM-DD'))) {
                    // IF THERE ARE NO PAYROLL RECORDED BEFORE May or October TERMS
                    return 0
                } else {
                    // GET PROJECTION VIA SALARY DATA
                    if (basicSalary < 1) {
                        return 0
                    } else {
                        var bi = basicSalary / 2
                        if (bi < 1) {
                            return 0
                        } else {
                            return this.round(bi / 12)
                        }
                    }
                }
            } else {
                // GET ACTUAL
                var termWage = this.isNull(salaryData.TermWage) ? 0 : parseFloat(salaryData.TermWage)
                var deductions = this.isNull(salaryData.AbsentAmount) ? 0 : parseFloat(salaryData.AbsentAmount)
                var dif = termWage - deductions
                if (dif < 1) {
                    return 0
                } else {
                    return this.round(dif / 12)
                }
            }
        },
        getAROthers(arOthersData, employeeId) {
            var arObj = arOthersData.filter(obj => obj.EmployeeId === employeeId)

            if (this.isNull(arObj)) {
                return 0
            } else {
                var amount = arObj[0].Amount
                if (this.isNull(amount)) {
                    return 0
                } else {
                    return parseFloat(amount)
                }
            }
        },
        getBempcDeduction(data) {
            if (this.isNull(data)) {
                return 0
            } else {
                var amnt = data[0].Amount

                if (this.isNull(amnt)) {
                    return 0
                } else {
                    return parseFloat(amnt)
                }
            }
        },
        getData() {
            this.employees = []
            this.loaderDisplay = ''
            this.incentiveExists = false
            this.isGenerateButtonDisabled = true
            this.isPreviewButtonDisabled = true
            this.existingDataSetId = null
            this.dataStatus = null

            if (this.term === moment().format('YYYY-05-01')) {
                // MAY HEADERS
                this.monthHeaders = {
                    0 : 'January',
                    1 : 'February',
                    2 : 'March',
                    3 : 'April',
                    4 : 'May',
                    5 : 'June',
                }
                this.isSecondTermShown = false
            } else {
                // OCTOBER HEADERS
                this.monthHeaders = {
                    0 : 'January',
                    1 : 'February',
                    2 : 'March',
                    3 : 'April',
                    4 : 'May',
                    5 : 'June',
                    6 : 'July',
                    7 : 'August',
                    8 : 'September',
                    9 : 'October',
                    10 : 'November',
                    11 : 'December',
                }
                this.isSecondTermShown = true
            }

            axios.get(`${ axios.defaults.baseURL }/incentives/get-thirteenth-month-data`, {
                params : {
                    Department : this.department,
                    EmployeeType : this.employeeType,
                    Term : this.term
                }
            })
            .then(response => {
                this.loaderDisplay = 'gone'
                this.isGenerateButtonDisabled = false
                this.isPreviewButtonDisabled = false

                // CHECK IF EXISTS
                if (this.isNull(response.data['IncentiveCheck'])) {
                    this.incentiveExists = false
                    this.dataStatus = null
                } else {
                    this.existingDataSetId = response.data['IncentiveCheck'].id
                    if (this.isNull(response.data['IncentiveCheck'].Status)) {
                        this.dataStatus = 'Pending'
                    } else {
                        if (response.data['IncentiveCheck'].Status === 'Locked') {
                            this.dataStatus = 'Locked'
                        } else {
                            this.dataStatus = response.data['IncentiveCheck'].Status
                        }
                    }
                    this.incentiveExists = true
                }

                // PROCESS EMPLOYEES
                var size = response.data['Employees'].length;
                for(let i=0; i<size; i++) {
                    // GET EMPLOYEES
                    var datasets = {
                        name : response.data['Employees'][i]['LastName'] + ", " + response.data['Employees'][i]['FirstName'],
                        id : response.data['Employees'][i]['id'],
                        Position : response.data['Employees'][i]['Position'],
                        SalaryAmount : this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : this.toMoney(parseFloat(response.data['Employees'][i]['SalaryAmount'])),
                        TermWage : this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : this.toMoney(parseFloat(response.data['Employees'][i]['SalaryAmount'])/2)
                    }

                    // GET PAYROLL DATA
                    var payrollData = response.data['Employees'][i]['SalaryData']
                    var basicSalary = this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : parseFloat(response.data['Employees'][i]['SalaryAmount'])
                    let dataGuideSize = this.term === moment().format('YYYY-05-01') ? 12 : this.dataSetGuides.length
                    var subTotal = 0
                    for(let y=0; y<dataGuideSize; y++) {
                        var foundData = payrollData.filter(obj => obj.SalaryPeriod === this.dataSetGuides[y].value)
                        datasets[this.dataSetGuides[y].name] = this.getBiMonthlyWage(foundData, basicSalary, this.term, this.dataSetGuides[y].value)
                        subTotal += this.getBiMonthlyWage(foundData, basicSalary, this.term, this.dataSetGuides[y].value)
                    }

                    // IF October/2nd Term, Display May/First Term incentive
                    if (this.term !== moment().format('YYYY-05-01')) {
                        datasets['PreviousTermAmount'] = this.isNull(response.data['Employees'][i]['FirstTerm']['NetPay']) ? 0 : parseFloat(response.data['Employees'][i]['FirstTerm']['NetPay'])
                        subTotal = subTotal - (this.isNull(response.data['Employees'][i]['FirstTerm']['NetPay']) ? 0 : parseFloat(response.data['Employees'][i]['FirstTerm']['NetPay']))
                    } else {
                        datasets['PreviousTermAmount'] = 0
                    }

                    datasets['SubTotal'] = subTotal

                    var arOtherAmnt = this.getAROthers(response.data['Employees'][i]['AROthers'], response.data['Employees'][i]['id'])
                    datasets['AROthers'] = arOtherAmnt > 0 ? arOtherAmnt : ''

                    var bempcDeduction = this.isNumber(this.getBempcDeduction(response.data['Employees'][i]['BEMPC'])) ? this.getBempcDeduction(response.data['Employees'][i]['BEMPC']) : 0
                    datasets['BEMPC'] = bempcDeduction

                    var netPay = subTotal - (arOtherAmnt + bempcDeduction)
                    datasets['NetPay'] = netPay > 0 ? parseFloat(netPay) : '-'

                    this.employees.push(datasets);
                }
            })
            .catch(error => {
                this.isGenerateButtonDisabled = true;
                this.isPreviewButtonDisabled = false

                Swal.fire({
                    icon : 'error',
                    title : 'Error submitting payroll data!',
                });
                console.log(error)
                this.loaderDisplay = 'gone';
            });
        },
        submit13thMonth() {
            if (this.isNull(this.dataStatus)) {
                Swal.fire({
                    title: "Submit for Audit?",
                    text : 'Submit this 13th month pay draft for audit? You can always regenerate this anytime as long as it has not yet been approved for finalization.',
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    confirmButtonColor: '#3a9971',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.saveThirteenthMonth()
                    }
                })
            } else {
                if (this.dataStatus === 'Locked') {
                    Swal.fire({
                        icon : 'info',
                        title: "Data is Already Locked",
                        text : 'There is already an existing LOCKED dataset containing these data. Regenerating is no longer allowed.'
                    })
                } else {
                    Swal.fire({
                        icon : 'warning',
                        title: "Re-submit and Override Existing?",
                        text : 'There is already an existing dataset containing these data. Are you sure you want to override it?',
                        showCancelButton: true,
                        confirmButtonText: "Proceed Override",
                        confirmButtonColor: '#e03822',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.saveThirteenthMonth()
                        }
                    })
                }
            }
        },
        saveThirteenthMonth() {
            this.loaderDisplay = ''
            this.isGenerateButtonDisabled = true
            this.isPreviewButtonDisabled = true
            axios.post(`${ axios.defaults.baseURL }/incentives/save-thirteenth-month`, {
                    Department : this.department,
                    EmployeeType : this.employeeType,
                    Term : this.term,
                    Data : this.employees
            })
            .then(response => {
                this.loaderDisplay = 'gone'
                this.isGenerateButtonDisabled = true
                this.isPreviewButtonDisabled = false
                this.incentiveExists = false
                this.employees = []

                this.toast.fire({
                    text :  '13th month data saved!',
                    icon : 'success'
                })
            })
            .catch(error => {
                this.isGenerateButtonDisabled = false
                this.isPreviewButtonDisabled = false
                this.incentiveExists = false

                Swal.fire({
                    icon : 'error',
                    title : 'Error submitting 13th month data!',
                });
                console.log(error)
                this.loaderDisplay = 'gone'
            });
        },
        showSaveFader() {
            var message = document.getElementById('msg-display');

            // Show the message
            message.style.opacity = 1;

            // Wait for 3 seconds
            setTimeout(function() {
                // Fade out the message
                message.style.opacity = 0;
            }, 1500);
        }
    },
    created() {
        
    },
    mounted() {
        
    }
}

</script>