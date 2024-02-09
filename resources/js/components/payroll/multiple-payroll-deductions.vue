<template>
    <div class="card shadow-none" style="margin-top: 10px;">
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
                    <span class="text-muted">Payroll Schedule</span>
                    <select v-model="schedule" class="form-control form-control-sm">
                        <option :value="fifteenth">{{ moment(fifteenth).format('MMMM DD, YYYY') }}</option>
                        <option :value="thirtieth">{{ moment(thirtieth).format('MMMM DD, YYYY') }}</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <span class="text-muted">Action</span><br>
                    <button class="btn btn-default btn-sm ico-tab-mini" :disabled="isButtonDisabled" @click="getOtherDeductionData()"><i class="fas fa-eye ico-tab-mini"></i>View</button>

                    <div class="spinner-border text-primary float-right" :class="isDisplayed" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-bordered" id="response">
                    <thead>
                        <th class='text-center'>Employees</th>
                        <th class='text-center'>Deduction Description/Remarks</th>
                        <th class='text-center'>Deductible Amount</th>              
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in employees" :key="employee.id">
                            <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                            <td>
                                <input class="table-input" :class="tableInputTextColor" v-model="employee.DeductionDescription" @keyup.enter="inputEnter(employee.DeductionAmount, employee.id, employee.DeductionDescription)" @blur="inputEnter(employee.DeductionAmount, employee.id, employee.DeductionDescription)" type="text"/>
                            </td>
                            <td>
                                <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.DeductionAmount" @keyup.enter="inputEnter(employee.DeductionAmount, employee.id, employee.DeductionDescription)" @blur="inputEnter(employee.DeductionAmount, employee.id, employee.DeductionDescription)" type="number" step="any"/>
                            </td>
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
    table {
        font-size: .82em;
    }

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

    .table-input::-webkit-outer-spin-button,
    .table-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .table-input:focus  {
        outline: none;
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
    name : 'MultiplePayrollDeductions.multiple-payroll-deductions',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            department : 'OGM',
            employeeType : 'Regular',
            isDisplayed : 'gone',
            isButtonDisabled : false,
            moment : moment,
            employees : [],
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            fifteenth : moment().format('YYYY-MM-15'),
            thirtieth : moment().month()==1 ? moment().endOf('month').format('YYYY-MM-DD') : moment().format('YYYY-MM-30'),
            schedule : this.fifteenth
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
        getOtherDeductionData() {
            if (this.isNull(this.schedule)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please select payroll schedule!'
                })
            } else {
                this.employees = []

                axios.get(`${ axios.defaults.baseURL }/other_payroll_deductions/get-other-deduction-multiple-data`, {
                    params: {
                        Department : this.department,
                        Schedule : this.schedule,
                    }
                }).then(response => {
                    var size = response.data.length;
                    for (let i=0; i<size; i++) {
                        this.employees.push({
                            name : response.data[i]['LastName'] + ", " + response.data[i]['FirstName'],
                            id : response.data[i]['id'],
                            DeductionFor : response.data[i]['DeductionName'],
                            DeductionAmount : response.data[i]['Amount'],
                            DeductionSchedule : response.data[i]['ScheduleDate'],
                            DeductionDescription : response.data[i]['DeductionDescription'],
                        });
                        
                        this.isDisplayed = 'gone';
                        this.isButtonDisabled = false;
                        this.fillInputsDisabled = false
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error getting employee data!',
                    });
                    console.log(error)
                    this.isDisplayed = 'gone';
                    this.isButtonDisabled = false;
                    this.fillInputsDisabled = true
                });
            }            
        },
        inputEnter(value, employeeId, description) {
            if (this.isNull(this.schedule)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please select payroll schedule!'
                })
            } else {
                axios.get(`${ axios.defaults.baseURL }/other_payroll_deductions/update-other-deduction-data`, {
                    params: {
                        EmployeeId : employeeId,
                        Amount : value,
                        Schedule : this.schedule,
                        Description : description,
                    }
                }).then(response => {
                    // this.toast.fire({
                    //     icon : 'success',
                    //     text : 'Deduction saved!'
                    // })
                    this.showSaveFader()
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error saving deductions!',
                    });
                    console.log(error)
                });
            }
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