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
                <div class="col-lg-3">
                    <span class="text-muted">Action</span><br>
                    <button class="btn btn-default btn-sm ico-tab-mini" :disabled="isButtonDisabled" @click="getEmployees()"><i class="fas fa-eye ico-tab-mini"></i>Preview</button>

                    <div class="spinner-border text-primary float-right" :class="isDisplayed" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-sm table-bordered" id="response">
            <thead>
                <tr>
                    <th class='text-center' rowspan="2">Employees</th>
                    <th class='text-center' colspan="2">Pag-Ibig Contribution</th>
                    <th class='text-center' colspan="2">SSS Contribution</th>
                    <th class='text-center' colspan="2">PhilHealth Contribution</th>
                </tr>
                <tr>
                    <th>
                        <i class="fas fa-shield-alt"></i>
                        Employer
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; display: inline-block; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="pagIbigEmployer"
                        @keyup.enter="fillAmount('PagIbigEmployer', pagIbigEmployer)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                    <th>
                        <i class="fas fa-user-circle"></i>
                        Employee
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; display: inline-block; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="pagIbigEmployee"
                        @keyup.enter="fillAmount('PagIbigEmployee', pagIbigEmployee)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                    <th>
                        <i class="fas fa-shield-alt"></i>
                        Employer
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; display: inline-block; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="sssEmployer"
                        @keyup.enter="fillAmount('SSSEmployer', sssEmployer)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                    <th>
                        <i class="fas fa-user-circle"></i>
                        Employee
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; display: inline-block; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="sssEmployee"
                        @keyup.enter="fillAmount('SSSEmployee', sssEmployee)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                    <th>
                        <i class="fas fa-shield-alt"></i>
                        Employer
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; display: inline-block; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="philHealthEmployer"
                        @keyup.enter="fillAmount('PhilHealthEmployer', philHealthEmployer)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                    <th>
                        <i class="fas fa-user-circle"></i>
                        Employee
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; display: inline-block; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="philHealthEmployee"
                        @keyup.enter="fillAmount('PhilHealthEmployee', philHealthEmployee)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                </tr>                
            </thead>
            <tbody>
                <tr v-for="(employee, index) in employees" :key="employee.id">
                    <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.PagIbigEmployer" @keyup.enter="inputEnter(employee.PagIbigEmployer, employee.id, 'PagIbigEmployer')" @blur="inputEnter(employee.PagIbigEmployer, employee.id, 'PagIbigEmployer')" type="number" step="any"/>
                    </td>
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.PagIbigEmployee" @keyup.enter="inputEnter(employee.PagIbigEmployee, employee.id, 'PagIbigEmployee')" @blur="inputEnter(employee.PagIbigEmployee, employee.id, 'PagIbigEmployee')" type="number" step="any"/>
                    </td>
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.SSSEmployer" @keyup.enter="inputEnter(employee.SSSEmployer, employee.id, 'SSSEmployer')" @blur="inputEnter(employee.SSSEmployer, employee.id, 'SSSEmployer')" type="number" step="any"/>
                    </td>
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.SSSEmployee" @keyup.enter="inputEnter(employee.SSSEmployee, employee.id, 'SSSEmployee')" @blur="inputEnter(employee.SSSEmployee, employee.id, 'SSSEmployee')" type="number" step="any"/>
                    </td>
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.PhilHealthEmployer" @keyup.enter="inputEnter(employee.PhilHealthEmployer, employee.id, 'PhilHealthEmployer')" @blur="inputEnter(employee.PhilHealthEmployer, employee.id, 'PhilHealthEmployer')" type="number" step="any"/>
                    </td>
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.PhilHealthEmployee" @keyup.enter="inputEnter(employee.PhilHealthEmployee, employee.id, 'PhilHealthEmployee')" @blur="inputEnter(employee.PhilHealthEmployee, employee.id, 'PhilHealthEmployee')" type="number" step="any"/>
                    </td>
                </tr>
            </tbody>
        </table>
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
    name : 'Contributions.contributions',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            department : 'ESD',
            employeeType : 'Regular',
            isDisplayed : 'gone',
            isButtonDisabled : false,
            employees : [],
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            pagIbigEmployer : '',
            pagIbigEmployee : '',
            sssEmployer : '',
            sssEmployee : '',
            philHealthEmployer : '',
            philHealthEmployee : '',
            fillInputsDisabled : true,
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
        getEmployees() {
            this.employees = []
            this.pagIbigEmployer = ''
            this.pagIbigEmployee = ''
            this.sssEmployer = ''
            this.sssEmployee = ''
            this.philHealthEmployer = ''
            this.philHealthEmployee = ''

            axios.get(`${ axios.defaults.baseURL }/employee_payroll_sundries/get-contribution-data`, {
                params: {
                    Department : this.department,
                }
            }).then(response => {
                var size = response.data.length;
                for (let i=0; i<size; i++) {
                    this.employees.push({
                        name : response.data[i]['LastName'] + ", " + response.data[i]['FirstName'],
                        id : response.data[i]['EmployeeIdNumber'],
                        PagIbigEmployer : response.data[i]['PagIbigContributionEmployer'],
                        PagIbigEmployee : response.data[i]['PagIbigContribution'],
                        SSSEmployer : response.data[i]['SSSContributionEmployer'],
                        SSSEmployee : response.data[i]['SSSContribution'],
                        PhilHealthEmployer : response.data[i]['PhilHealthContributionEmployer'],
                        PhilHealthEmployee : response.data[i]['PhilHealth'],
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
        },
        test () {
            this.toast.fire({
                icon : 'success',
                text : 'focus out!'
            })
        },
        inputEnter(value, employeeId, type) {
            axios.get(`${ axios.defaults.baseURL }/employee_payroll_sundries/insert-contribution-data`, {
                params: {
                    EmployeeId : employeeId,
                    Amount : value,
                    Type : type,
                }
            }).then(response => {
                // this.toast.fire({
                //     icon : 'success',
                //     text : 'Saved!'
                // })
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error saving contributions!',
                });
                console.log(error)
            });
        },
        fillAmount(type, value) {
            this.isDisplayed = '';
            this.isButtonDisabled = true;
            this.fillInputsDisabled = true
            
            axios.get(`${ axios.defaults.baseURL }/employee_payroll_sundries/insert-all-contribution-data`, {
                params: {
                    Department : this.department,
                    Amount : value,
                    Type : type,
                }
            }).then(response => {
                this.isDisplayed = 'gone';
                this.isButtonDisabled = false;
                this.fillInputsDisabled = false

                this.toast.fire({
                    icon : 'success',
                    text : 'Saved!'
                })
                for(let i=0; i<this.employees.length; i++) {
                    if (type == 'PagIbigEmployer') {
                        this.employees[i].PagIbigEmployer = value
                    } else if (type == 'PagIbigEmployee') {
                        this.employees[i].PagIbigEmployee = value
                    } else if (type == 'SSSEmployer') {
                        this.employees[i].SSSEmployer = value
                    } else if (type == 'SSSEmployee') {
                        this.employees[i].SSSEmployee = value
                    } else if (type == 'PhilHealthEmployer') {
                        this.employees[i].PhilHealthEmployer = value
                    } else if (type == 'PhilHealthEmployee') {
                        this.employees[i].PhilHealthEmployee = value
                    }                
                }
            })
            .catch(error => {
                this.isDisplayed = 'gone';
                this.isButtonDisabled = false;
                this.fillInputsDisabled = true

                Swal.fire({
                    icon : 'error',
                    title : 'Error saving contributions!',
                });
                console.log(error)
            });

            
        }
    },
    created() {
        
    },
    mounted() {
       
    }
}

</script>