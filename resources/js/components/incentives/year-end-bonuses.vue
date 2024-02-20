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
                        <div class="col-lg-4">
                            <span class="text-muted">Action</span><br>
                            <button class="btn btn-default btn-sm ico-tab-mini" :disabled="isPreviewButtonDisabled" @click="getData()"><i class="fas fa-eye ico-tab-mini"></i>Preview</button>
                            <button class="btn btn-primary btn-sm" :disabled="isGenerateButtonDisabled" @click="submitBonus()"><i class="fas fa-check-circle ico-tab-mini"></i>Submit Bonus</button>
        
                            <div class="spinner-border text-success float-right" :class="loaderDisplay" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISIBILITY SETTINGS -->
        <div class="custom-control custom-switch" style="margin-left: 15px; margin-bottom: 20px;">
            <input type="checkbox" class="custom-control-input" :checked="is14MonthBreakdownShown" @change="toggle14thBreakdown()" id="show14th">
            <label class="custom-control-label text-muted" for="show14th" style="font-weight: normal">Show 14th-month Breakdown</label>
        </div>

        <!-- SHOW IF EXISTS -->
        <div class="col-lg-12" v-if="incentiveExists">
            <div class="exists">
                <span style="color: aliceblue;"><i class="fas fa-exclamation-triangle ico-tab-mini"></i>{{ incentiveSelected }} already exists (Status: <strong>{{ dataStatus }}</strong>)</span>
            </div>
            <a target="_blank" :href="baseURL + '/incentives/view-incentives/' + existingDataSetId" class="btn btn-default btn-sm" style="margin-left: 10px;"><i class="fas fa-eye ico-tab-mini"></i>View {{ incentiveSelected }} Instead</a>
        </div>

         <!-- TABLE -->
         <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-xs table-bordered" id="response">
                    <thead>
                        <tr>
                            <th rowspan="2" class='text-center' style="width: 240px;">Employees</th>
                            <th rowspan="2" class='text-center' style="width: 240px;">Position</th>
                                <!-- 1st Term -->
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['0'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['1'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['2'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['3'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['4'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['5'] }}</th>
                                <!-- 2nd Term -->
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['6'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['7'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['8'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['9'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['10'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['11'] }}</th>
                            <th rowspan="2" class="text-center">14th Month Pay</th>
                            <th rowspan="2" class="text-center">13th Month<br>Differential</th>
                            <th rowspan="2" class="text-center">Cash Gift
                                <br>
                                <input type="number" step="any" :disabled="isFixedAmountCashGiftDisabled" v-model="fixedAmountCashGift" @keyup.enter="spreadCashGiftFixedAmount(fixedAmountCashGift)" @blur="spreadCashGiftFixedAmount(fixedAmountCashGift)" style="width: 150px; display: inline;" class="form-control form-control-sm text-right" placeholder="Fixed Amount">
                                or
                                <button :disabled="isSalaryBasedCashGiftButtonDisabled" class="btn btn-default btn-sm" @click="salaryBasedCashGift()">{{ salaryBasedCashGiftLabel }}</button>
                            </th>
                            <th colspan="2" class="text-center">Leave Cash Conversions</th>
                            <th rowspan="2" class="text-center">Loyalty Award</th>
                            <th rowspan="2" class="text-center">AR-Others</th>
                            <th rowspan="2" class="text-center">BEMPC</th>
                            <th rowspan="2" class="text-center">NET PAY</th>
                        </tr>
                        <tr>
                            <!-- 1st Term -->
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <!-- 2nd Term -->
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th class="text-center">VL</th>
                            <th class="text-center">SL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in employees" :key="employee.id">
                            <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                            <td style="min-width: 250px;">{{ employee.Position }}</td>
                            <!-- 1st Term -->
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JanuaryFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JanuarySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.FebruaryFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.FebruarySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MarchFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MarchSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AprilFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AprilSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MayFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MaySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JuneFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JuneSecond) }}</td>
                            <!-- 2nd Term -->
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JulyFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JulySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AugustFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AugustSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.SeptemberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.SeptemberSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.OctoberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.OctoberSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.NovemberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.NovemberSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.DecemberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.DecemberSecond) }}</td>

                            <td class='text-right'>{{ toMoney(employee.FourteenthMonth) }}</td>
                            <td class='text-right'>{{ toMoney(employee.ThirteenthMonthDifferential) }}</td>
                            <td class='text-right'>{{ toMoney(employee.CashGift) }}</td>
                            <td class='text-right'>{{ toMoney(employee.VL) }}</td>
                            <td class='text-right'>{{ toMoney(employee.SL) }}</td>
                            <td class='text-right'>{{ toMoney(employee.LoyaltyAward) }}</td>
                            <td>
                                <input style="min-width: 80px;" class="table-input-sm text-right" :disabled="isFixedAmountDisabled" :class="tableInputTextColor" v-model="employee.AROthers" @keyup.enter="inputEnter(employee.AROthers, employee.id)" @blur="inputEnter(employee.AROthers, employee.id)" type="number" step="any"/>
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

export default {
    name : 'YearEndBonuses.year-end-bonuses',
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
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            isPreviewButtonDisabled : false,
            isGenerateButtonDisabled : true,
            loaderDisplay : 'gone',
            employees : [],
            incentiveExists : false,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            incentivesList : [], 
            incentiveSelected : 'Year-end Incentives',
            dataStatus : null,
            existingDataSetId : null,
            // 14th Month Pay
            monthHeaders : {
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
            },
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
            is14MonthBreakdownShown : false,
            // CASH GIFT
            fixedAmountCashGift : '',
            isFixedAmountCashGiftDisabled : false,
            isSalaryBasedCashGiftButtonDisabled : false,
            salaryBasedCashGiftMultiplier : 0,
            isCashGiftSalaryBased : false,
            salaryBasedCashGiftLabel : 'Salary Based',
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
            axios.get(`${ axios.defaults.baseURL }/other_payroll_deductions/update-data`, {
                params: {
                    EmployeeId : employeeId,
                    Amount : value,
                    ScheduleDate : null,
                    Type : this.incentiveSelected,
                    Description : null,
                }
            }).then(response => {                
                // FIND EMPTY IDs TO BE REPLACED BY A NEW ID IF NEW ENTRY
                // UPDATE VALUE REAL TIME
                var newValue = 0
                const empArray = this.employees.filter(obj => obj.id === employeeId)

                if (!this.isNull(empArray)) {
                    var fourteenth = empArray[0].FourteenthMonth
                    var cashGift =  empArray[0].CashGift
                    var vl = empArray[0].VL
                    var sl = empArray[0].SL
                    var loyaltyAward = empArray[0].LoyaltyAward
                    var thirteenthDifferential = empArray[0].ThirteenthMonthDifferential
                    var bempc = this.isNull(empArray[0].BEMPC) ? 0 : empArray[0].BEMPC

                    value = (!this.isNull(value) ? value : 0)
                    fourteenth = parseFloat(fourteenth)
                    cashGift = parseFloat(cashGift)
                    vl = parseFloat(vl)
                    sl = parseFloat(sl)
                    loyaltyAward = parseFloat(loyaltyAward)
                    thirteenthDifferential = parseFloat(thirteenthDifferential)
                    bempc = parseFloat(bempc)
                    newValue = (fourteenth + cashGift + vl + sl + loyaltyAward) - (parseFloat(value) + parseFloat(bempc) + thirteenthDifferential)
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
        // 14th Month Pay
        getBiMonthlyWage(salaryData, basicSalary, month) {
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
        toggle14thBreakdown() {
            return this.is14MonthBreakdownShown ? this.is14MonthBreakdownShown=false : this.is14MonthBreakdownShown=true
        },
        // CASH GIFT FUNCTIONS
        salaryBasedCashGift() {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'text',
                    html: `
                        <p style='text-align: left;'>Provide a salary <strong>Multiplier (Percentage)</strong> for this incentive/bonus. </p>
                        <div class='upload-jumbotron' style='display: inline-block;'>
                            <p style='text-align: left; font-size: .9em;'>This <strong>Multiplier</strong> will be multiplied on the employees base salary to produce the incentive/bonus.</p>
                            <p style='text-align: left; font-size: .9em;'><strong>Example: </strong></p>
                            <p style='text-align: left; font-size: .9em;'>
                                <span style='padding-left: 22px;'>Multiplier = <strong>1.5</strong> (150%)<span><br>
                                x<span style='border-bottom: 1px solid #888888; padding-left: 14px;'>Base Salary = <strong>36,750.00</strong><br></span>
                                <strong style='padding-left: 22px;'>Bonus = 55,125.00</strong>
                            </p>
                        </div>
                    `,
                    inputPlaceholder: 'Input Multiplier',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Salary-Based Cash Gift',
                    showCancelButton: true
                })

                if (text) {
                    this.fixedAmount14th = ''
                    if (this.isNull(text)) {
                        this.isCashGiftSalaryBased = false
                        this.salaryBasedCashGiftLabel = 'Salary Based'
                        this.toast.fire({
                            icon : 'info',
                            text : 'Please provide multiplier if you opt for Salary-Based!',
                        })
                    } else { 
                        try {
                            if (this.isNumber(text) || !isNaN(text)) {
                                this.isCashGiftSalaryBased = true
                                this.salaryBasedCashGiftMultiplier = parseFloat(text)
                                this.salaryBasedCashGiftLabel = 'Salary Based (x ' + text + ')'
                                this.spreadCashGiftSalaryBasedAmount()
                            } else {
                                this.isCashGiftSalaryBased = false
                                this.salaryBasedCashGiftLabel = 'Salary Based'
                                this.toast.fire({
                                    icon : 'info',
                                    text : 'Please provide a valid number!',
                                })
                            }
                        } catch (err) {
                            this.salaryBasedCashGiftLabel = 'Salary Based'
                            this.isCashGiftSalaryBased = false
                            Swal.fire({
                                icon : 'info',
                                title : 'Oops!',
                                text : err.message,
                            })
                        }
                    }
                }
            })()
        },
        spreadCashGiftSalaryBasedAmount() {
            let size = this.employees.length
            for(let i=0; i<size; i++) {
                var baseSalary = this.isNull(this.employees[i].SalaryAmount) ? 0 : parseFloat(this.employees[i].SalaryAmount)
                var bonusAmnt = baseSalary * this.salaryBasedCashGiftMultiplier
                this.employees[i].CashGift = bonusAmnt

                var arOthers = this.isNull(this.employees[i].AROthers) ? 0 : parseFloat(this.employees[i].AROthers)
                var bempc = this.isNull(this.employees[i].BEMPC) ? 0 : parseFloat(this.employees[i].BEMPC)
                var fourteenth = this.isNull(this.employees[i].FourteenthMonth) ? 0 : parseFloat(this.employees[i].FourteenthMonth)
                var netPay = (bonusAmnt + fourteenth) - (arOthers + bempc)

                this.employees[i].NetPay = netPay
            }
            this.showSaveFader()
        },
        spreadCashGiftFixedAmount(amount) {
            this.salaryBasedCashGiftLabel = 'Salary Based'
            this.isCashGiftSalaryBased = false
            let size = this.employees.length
            for(let i=0; i<size; i++) {
                this.employees[i].CashGift = amount

                var arOthers = this.employees[i].AROthers
                var bempc = this.employees[i].BEMPC
                var fourteenth = this.isNull(this.employees[i].FourteenthMonth) ? 0 : this.employees[i].FourteenthMonth
                var netPay = (amount + fourteenth) - (arOthers + bempc)

                this.employees[i].NetPay = netPay
            }
            this.showSaveFader()
        },
        getExistingIncentive(data) {
            var bonusAmount = data.SubTotal
            if (this.isNull(bonusAmount)) {
                return 0;
            } else {
                return parseFloat(bonusAmount);
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

            axios.get(`${ axios.defaults.baseURL }/incentives/get-year-end-incentives-data`, {
                params : {
                    Department : this.department, 
                    EmployeeType : this.employeeType,
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
                    this.isFixedAmountDisabled = false
                    this.isSalaryBased14thButtonDisabled = false
                } else {
                    this.existingDataSetId = response.data['IncentiveCheck'].id
                    if (this.isNull(response.data['IncentiveCheck'].Status)) {
                        this.dataStatus = 'Pending'
                        this.isFixedAmountDisabled = false
                        this.isSalaryBased14thButtonDisabled = false
                    } else {
                        if (response.data['IncentiveCheck'].Status === 'Locked') {
                            this.dataStatus = 'Locked'
                            this.isFixedAmountDisabled = true
                            this.isSalaryBased14thButtonDisabled = true
                        } else {
                            this.dataStatus = response.data['IncentiveCheck'].Status
                            this.isFixedAmountDisabled = false
                            this.isSalaryBased14thButtonDisabled = false
                        }
                    }
                    this.incentiveExists = true
                }
                
                let size = response.data['Employees'].length
                for(let i=0; i<size; i++) {
                    // GET EMPLOYEES
                    var datasets = {
                        name : response.data['Employees'][i]['LastName'] + ", " + response.data['Employees'][i]['FirstName'],
                        id : response.data['Employees'][i]['id'],
                        Position : response.data['Employees'][i]['Position'],
                        SalaryAmount : this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : response.data['Employees'][i]['SalaryAmount'],
                    }

                    /**
                     * ============================================================================
                     *  14th MONTH PAY
                     * ============================================================================
                     */
                    // GET PAYROLL DATA
                    var payrollData = response.data['Employees'][i]['SalaryData']
                    var basicSalary = this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : parseFloat(response.data['Employees'][i]['SalaryAmount'])
                    let dataGuideSize = this.term === moment().format('YYYY-05-01') ? 12 : this.dataSetGuides.length
                    var fourteenthMonthTotal = 0
                    for(let y=0; y<dataGuideSize; y++) {
                        var foundData = payrollData.filter(obj => obj.SalaryPeriod === this.dataSetGuides[y].value)
                        datasets[this.dataSetGuides[y].name] = this.getBiMonthlyWage(foundData, basicSalary, this.dataSetGuides[y].value)
                        fourteenthMonthTotal += this.getBiMonthlyWage(foundData, basicSalary, this.dataSetGuides[y].value)
                    }
                    datasets['FourteenthMonth'] = fourteenthMonthTotal

                    /**
                     * ============================================================================
                     *  Cash Gift
                     * ============================================================================
                     */
                    var cashGiftAmnt = 0
                    if (this.isCashGiftSalaryBased) {
                        cashGiftAmnt = (this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : parseFloat(response.data['Employees'][i]['SalaryAmount'])) * this.salaryBasedCashGiftMultiplier
                    } else {
                        cashGiftAmnt = this.isNull(response.data['Employees'][i]['ExistingIncentive']) ? this.fixedAmountCashGift : this.getExistingIncentive(response.data['Employees'][i]['ExistingIncentive'])
                    }

                    datasets['CashGift'] = cashGiftAmnt

                    var arOtherAmnt = this.getAROthers(response.data['Employees'][i]['AROthers'], response.data['Employees'][i]['id'])
                    datasets['AROthers'] = arOtherAmnt > 0 ? arOtherAmnt : ''

                    var bempcDeduction = this.isNumber(this.getBempcDeduction(response.data['Employees'][i]['BEMPC'])) ? this.getBempcDeduction(response.data['Employees'][i]['BEMPC']) : 0
                    datasets['BEMPC'] = bempcDeduction

                    var netPay = (cashGiftAmnt + fourteenthMonthTotal) - (arOtherAmnt + bempcDeduction)
                    datasets['NetPay'] = netPay > 0 ? parseFloat(netPay) : ''

                    this.employees.push(datasets)
                }
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting incentives list!',
                });
                console.log(error)
                this.loaderDisplay = 'gone';
            });         
        },
        submitBonus() {
            if (this.isNull(this.dataStatus)) {
                Swal.fire({
                    title: "Submit for Audit?",
                    text : 'Submit this ' + this.incentiveSelected + ' draft for audit? You can always regenerate this anytime as long as it has not yet been approved for finalization.',
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    confirmButtonColor: '#3a9971',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.saveBonus()
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
                            this.saveBonus()
                        }
                    })
                }
            }
        },
        saveBonus() {
            this.loaderDisplay = ''
            this.isGenerateButtonDisabled = true
            this.isPreviewButtonDisabled = true
            axios.post(`${ axios.defaults.baseURL }/incentives/save-custom-bonus`, {
                    Department : this.department,
                    EmployeeType : this.employeeType,
                    Incentive : this.incentiveSelected,
                    Data : this.employees,
                    Notes : this.isSalaryBased ? ('Salary-based with ' + this.salaryBasedMultiplier + ' multiplier') : null,
                    ReleaseType : this.releaseType,
            })
            .then(response => {
                this.loaderDisplay = 'gone'
                this.isGenerateButtonDisabled = true
                this.isPreviewButtonDisabled = false
                this.incentiveExists = false
                this.employees = []

                this.toast.fire({
                    text :  'Incentive data saved!',
                    icon : 'success'
                })
            })
            .catch(error => {
                this.isGenerateButtonDisabled = false
                this.isPreviewButtonDisabled = false
                this.incentiveExists = false

                Swal.fire({
                    icon : 'error',
                    title : 'Error submitting Incentive data!',
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
