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
                            <span class="text-muted">Select Incentive</span>
                            <select v-model="incentiveSelected" class="form-control form-control-sm">
                                <option v-for="incentive in incentivesList" :key="incentive.id" :value="incentive.Incentive">{{ incentive.Incentive }}</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <span class="text-muted">Release in:</span>
                            <div class="input-group-radio-sm">
                                <input type="radio" id="Partial" value="Partial" v-model="releaseType" class="custom-radio-sm">
                                <label for="Partial" class="custom-radio-label-sm">Partial</label>
            
                                <input type="radio" id="Full" value="Full" v-model="releaseType" class="custom-radio-sm">
                                <label for="Full" class="custom-radio-label-sm">Full</label>
                            </div>
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
                        <th rowspan="2" class='text-center' style="width: 240px;">Employees</th>
                        <th rowspan="2" class='text-center' style="width: 240px;">Position</th>
                        <th rowspan="2" class='text-center'>Basic Salary</th>
                        <th rowspan="2" class="text-center">Bonus/Incentive Amount
                            <br>
                            <input type="number" step="any" :disabled="isFixedAmountDisabled" v-model="fixedAmount" @keyup.enter="spreadFixedAmount(fixedAmount)" @blur="spreadFixedAmount(fixedAmount)" style="width: 150px; display: inline;" class="form-control form-control-sm text-right" placeholder="Fixed Amount">
                            or
                            <button :disabled="isSalaryBasedButtonDisabled" class="btn btn-default btn-sm" @click="salaryBased()">{{ salaryBasedLabel }}</button>
                        </th>
                        <th rowspan="2" class="text-center">AR-Others</th>
                        <th rowspan="2" class="text-center">BEMPC</th>
                        <th rowspan="2" class="text-center">NET PAY</th>
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in employees" :key="employee.id">
                            <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                            <td style="min-width: 250px;">{{ employee.Position }}</td>
                            <td style="min-width: 80px;" class="text-right">{{ toMoney(parseFloat(employee.SalaryAmount)) }}</td>
                            <td class='text-right'>{{ toMoney(employee.BonusAmount) }}</td>
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
    name : 'OtherBonuses.other-bonuses',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            employeeType : 'Regular',
            department : 'ESD',
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
            incentiveSelected : '',
            dataStatus : null,
            existingDataSetId : null,
            fixedAmount : '',
            isFixedAmountDisabled : false,
            isSalaryBasedButtonDisabled : false,
            salaryBasedMultiplier : 0,
            isSalaryBased : false,
            salaryBasedLabel : 'Salary Based',
            releaseType : "Full"
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
                    var subTotal = empArray[0].BonusAmount
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
        getIncentivesList() {
            this.incentivesList = []
            axios.get(`${ axios.defaults.baseURL }/incentives/get-incentives-list`)
            .then(response => {
                this.loaderDisplay = 'gone'
                this.isGenerateButtonDisabled = false
                this.isPreviewButtonDisabled = false
                
                this.incentivesList = response.data
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
        salaryBased() {
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
                    title: 'Salary-Based Incentive',
                    showCancelButton: true
                })

                if (text) {
                    this.fixedAmount = ''
                    if (this.isNull(text)) {
                        this.isSalaryBased = false
                        this.salaryBasedLabel = 'Salary Based'
                        this.toast.fire({
                            icon : 'info',
                            text : 'Please provide multiplier if you opt for Salary-Based!',
                        })
                    } else { 
                        try {
                            if (this.isNumber(text) || !isNaN(text)) {
                                this.isSalaryBased = true
                                this.salaryBasedMultiplier = parseFloat(text)
                                this.salaryBasedLabel = 'Salary Based (x ' + text + ')'
                                this.spreadSalaryBasedAmount()
                            } else {
                                this.isSalaryBased = false
                                this.salaryBasedLabel = 'Salary Based'
                                this.toast.fire({
                                    icon : 'info',
                                    text : 'Please provide a valid number!',
                                })
                            }
                        } catch (err) {
                            this.salaryBasedLabel = 'Salary Based'
                            this.isSalaryBased = false
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
        spreadSalaryBasedAmount() {
            let size = this.employees.length
            for(let i=0; i<size; i++) {
                var baseSalary = this.isNull(this.employees[i].SalaryAmount) ? 0 : parseFloat(this.employees[i].SalaryAmount)
                var bonusAmnt = baseSalary * this.salaryBasedMultiplier
                this.employees[i].BonusAmount = bonusAmnt

                var arOthers = this.isNull(this.employees[i].AROthers) ? 0 : parseFloat(this.employees[i].AROthers)
                var bempc = this.isNull(this.employees[i].BEMPC) ? 0 : parseFloat(this.employees[i].BEMPC)
                var netPay = bonusAmnt - (arOthers + bempc)

                this.employees[i].NetPay = netPay
            }
            this.showSaveFader()
        },
        spreadFixedAmount(amount) {
            this.salaryBasedLabel = 'Salary Based'
            this.isSalaryBased = false
            let size = this.employees.length
            for(let i=0; i<size; i++) {
                this.employees[i].BonusAmount = amount

                var arOthers = this.employees[i].AROthers
                var bempc = this.employees[i].BEMPC
                var netPay = amount - (arOthers + bempc)

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
            if (this.isNull(this.incentiveSelected)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select incentive'
                })
            } else {
                this.employees = []
                this.loaderDisplay = ''
                this.incentiveExists = false
                this.isGenerateButtonDisabled = true
                this.isPreviewButtonDisabled = true
                this.existingDataSetId = null
                this.dataStatus = null

                axios.get(`${ axios.defaults.baseURL }/incentives/get-custom-incentives-data`, {
                    params : {
                        Department : this.department, 
                        EmployeeType : this.employeeType,
                        Incentive : this.incentiveSelected,
                        ReleaseType : this.releaseType,
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
                        this.isSalaryBasedButtonDisabled = false
                    } else {
                        this.existingDataSetId = response.data['IncentiveCheck'].id
                        if (this.isNull(response.data['IncentiveCheck'].Status)) {
                            this.dataStatus = 'Pending'
                            this.isFixedAmountDisabled = false
                            this.isSalaryBasedButtonDisabled = false
                        } else {
                            if (response.data['IncentiveCheck'].Status === 'Locked') {
                                this.dataStatus = 'Locked'
                                this.isFixedAmountDisabled = true
                                this.isSalaryBasedButtonDisabled = true
                            } else {
                                this.dataStatus = response.data['IncentiveCheck'].Status
                                this.isFixedAmountDisabled = false
                                this.isSalaryBasedButtonDisabled = false
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

                        var bonusAmnt = 0
                        if (this.isSalaryBased) {
                            bonusAmnt = (this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : parseFloat(response.data['Employees'][i]['SalaryAmount'])) * this.salaryBasedMultiplier
                        } else {
                            bonusAmnt = this.isNull(response.data['Employees'][i]['ExistingIncentive']) ? this.fixedAmount : this.getExistingIncentive(response.data['Employees'][i]['ExistingIncentive'])
                        }
                        datasets['BonusAmount'] = bonusAmnt

                        var arOtherAmnt = this.getAROthers(response.data['Employees'][i]['AROthers'], response.data['Employees'][i]['id'])
                        datasets['AROthers'] = arOtherAmnt > 0 ? arOtherAmnt : ''

                        var bempcDeduction = this.isNumber(this.getBempcDeduction(response.data['Employees'][i]['BEMPC'])) ? this.getBempcDeduction(response.data['Employees'][i]['BEMPC']) : 0
                        datasets['BEMPC'] = bempcDeduction

                        var netPay = bonusAmnt - (arOtherAmnt + bempcDeduction)
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
            }         
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
        this.getIncentivesList()
    }
}

</script>
