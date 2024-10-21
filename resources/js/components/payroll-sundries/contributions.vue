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
                    <select @change="getEmployees" v-model="department" class="form-control form-control-sm">
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
        <table class="table table-hover table-sm table-xs table-bordered" id="response">
            <thead>
                <tr>
                    <th class='text-center' rowspan="2">Employees</th>
                    <th class='text-center' colspan="2">Pag-Ibig Contribution</th>
                    <th class="text-center" rowspan="2">
                        Pag-Ibig MP2
                        <br>
                        <input title="Enter a fixed amount to all employees" 
                            type="number" step="any" 
                            class="form-control form-control-sm" 
                            style="width: 120px; float: right; height: 28px;" 
                            placeholder="Fill Amount"
                            v-model="mp2"
                            @keyup.enter="fillAmount('PagIbigMP2', mp2)"
                            :disabled="fillInputsDisabled">
                    </th>
                    <th class='text-center' colspan="2">SSS Contribution</th>
                    <th class='text-center' colspan="2">PhilHealth Contribution</th>
                    <th class="text-center">Rice & Laundry</th>
                </tr>
                <tr>
                    <th>
                        <i class="fas fa-shield-alt"></i>Employer
                        <input title="Enter a fixed amount to all employees" 
                            type="number" step="any" 
                            class="form-control form-control-sm" 
                            style="width: 120px; float: right; height: 28px;" 
                            placeholder="Fill Amount"
                            v-model="pagIbigEmployer"
                            @keyup.enter="fillAmount('PagIbigEmployer', pagIbigEmployer)"
                            :disabled="fillInputsDisabled">
                    </th>
                    <th>
                        <i class="fas fa-user-circle"></i>
                        Employee
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="pagIbigEmployee"
                        @keyup.enter="fillAmount('PagIbigEmployee', pagIbigEmployee)"
                        :disabled="fillInputsDisabled"
                        >
                    </th>
                    <th>
                        <i class="fas fa-shield-alt"></i>
                        Employer
                        <!-- <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="sssEmployer"
                        @keyup.enter="fillAmount('SSSEmployer', sssEmployer)"
                        :disabled="fillInputsDisabled"
                        > -->
                        <button @click="autoFillSss('SSSEmployer')" :disabled="fillInputsDisabled" class="btn btn-sm float-right btn-default"><i class="fas fa-arrow-down ico-tab-mini"></i>Auto-Fill</button>
                    </th>
                    <th>
                        <i class="fas fa-user-circle"></i>
                        Employee
                        <!-- <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="sssEmployee"
                        @keyup.enter="fillAmount('SSSEmployee', sssEmployee)"
                        :disabled="fillInputsDisabled"
                        > -->
                        <button @click="autoFillSss('SSSEmployee')" :disabled="fillInputsDisabled" class="btn btn-sm float-right btn-default"><i class="fas fa-arrow-down ico-tab-mini"></i>Auto-Fill</button>
                    </th>
                    <th>
                        <i class="fas fa-shield-alt"></i>
                        Employer
                        <!-- <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="philHealthEmployer"
                        @keyup.enter="fillAmount('PhilHealthEmployer', philHealthEmployer)"
                        :disabled="fillInputsDisabled"
                        > -->
                        <button @click="autoFillPhilHealth('PhilHealthEmployer')" :disabled="fillInputsDisabled" class="btn btn-sm float-right btn-default"><i class="fas fa-arrow-down ico-tab-mini"></i>Auto-Fill</button>
                    </th>
                    <th>
                        <i class="fas fa-user-circle"></i>
                        Employee
                        <!-- <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="width: 120px; float: right; height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="philHealthEmployee"
                        @keyup.enter="fillAmount('PhilHealthEmployee', philHealthEmployee)"
                        :disabled="fillInputsDisabled"
                        > -->
                        <button @click="autoFillPhilHealth('PhilHealthEmployee')" :disabled="fillInputsDisabled" class="btn btn-sm float-right btn-default"><i class="fas fa-arrow-down ico-tab-mini"></i>Auto-Fill</button>
                    </th>
                    <th>
                        <input title="Enter a fixed amount to all employees" 
                        type="number" step="any" 
                        class="form-control form-control-sm" 
                        style="height: 28px;" 
                        placeholder="Fill Amount"
                        v-model="riceAndLaundry"
                        @keyup.enter="fillAmount('RiceAndLaundry', riceAndLaundry)"
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
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.PagIbigMP2" @keyup.enter="inputEnter(employee.PagIbigMP2, employee.id, 'PagIbigMP2')" @blur="inputEnter(employee.PagIbigMP2, employee.id, 'PagIbigMP2')" type="number" step="any"/>
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
                    <td>
                        <input class="table-input text-right" :class="tableInputTextColor" v-model="employee.RiceAndLaundry" @keyup.enter="inputEnter(employee.RiceAndLaundry, employee.id, 'RiceAndLaundry')" @blur="inputEnter(employee.RiceAndLaundry, employee.id, 'RiceAndLaundry')" type="number" step="any"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>saved!</p>
    </div>
</template>

<style>
    .table-xs {
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
            riceAndLaundry : '',
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
                        Salary : response.data[i]['BasicSalary'],
                        RiceAndLaundry : response.data[i]['RiceAllowance'],
                        PagIbigMP2 : response.data[i]['PagIbigMP2'],
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
                this.showSaveFader()
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error saving contributions!',
                });
                console.log(error)
            });
        },
        sssSalaryBracketEmployer(employee) {
            var salary = parseFloat(employee.Salary)
            var er = 0;
            if (salary < 4250) {
                er = 390
            } else if(salary >=4250 && salary <= 4749.99) {
                er = 437.50
            } else if(salary >=4750 && salary <= 5249.99) {
                er = 485
            } else if(salary >=5250 && salary <= 5749.99) {
                er = 532.5
            } else if(salary >=5750 && salary <= 6249.99) {
                er = 580
            } else if(salary >=6250 && salary <= 6749.99) {
                er = 627.5
            } else if(salary >=6750 && salary <= 7249.99) {
                er = 675
            } else if(salary >=7250 && salary <= 7749.99) {
                er =722.5
            } else if(salary >=7750 && salary <= 8249.99) {
                er = 770
            } else if(salary >=8250 && salary <= 8749.99) {
                er = 817.5
            } else if(salary >=8750 && salary <= 9249.99) {
                er = 865
            } else if(salary >=9250 && salary <= 9749.99) {
                er = 912.5
            } else if(salary >=9750 && salary <= 10249.99) {
                er = 960
            } else if(salary >=10250 && salary <= 10749.99) {
                er = 1007.5
            } else if(salary >=10750 && salary <= 11249.99) {
                er = 1055
            } else if(salary >=11250 && salary <= 11749.99) {
                er = 1102.5
            } else if(salary >=11750 && salary <= 12249.99) {
                er = 1150
            } else if(salary >=12250 && salary <= 12749.99) {
                er = 1197.5
            } else if(salary >=12750 && salary <= 13249.99) {
                er = 1245
            } else if(salary >=13250 && salary <= 13749.99) {
                er = 1292.5
            } else if(salary >=13750 && salary <= 14249.99) {
                er = 1340
            } else if(salary >=14250 && salary <= 14749.99) {
                er = 1387.5
            } else if(salary >=14750 && salary <= 15249.99) {
                er = 1455
            } else if(salary >=15250 && salary <= 15749.99) {
                er = 1502.5
            } else if(salary >=15750 && salary <= 16249.99) {
                er = 1550
            } else if(salary >=16250 && salary <= 16749.99) {
                er = 1597.5
            } else if(salary >=16750 && salary <= 17249.99) {
                er = 1645
            } else if(salary >=17250 && salary <= 17749.99) {
                er = 1692.5
            } else if(salary >=17750 && salary <= 18249.99) {
                er = 1740
            } else if(salary >=18250 && salary <= 18749.99) {
                er = 1787.5
            } else if(salary >=18750 && salary <= 19249.99) {
                er = 1835
            } else if(salary >=19250 && salary <= 19749.99) {
                er = 1882.5
            } else if(salary >=19750 && salary <= 20249.99) {
                er = 1930
            } else if(salary >=20250 && salary <= 20749.99) {
                er = 1977.5
            } else if(salary >=20750 && salary <= 21249.99) {
                er = 2025
            } else if(salary >=21250 && salary <= 21749.99) {
                er = 2072.5
            } else if(salary >=21750 && salary <= 22249.99) {
                er = 2120
            } else if(salary >=22250 && salary <= 22749.99) {
                er = 2157.5
            } else if(salary >=22750 && salary <= 23249.99) {
                er = 2215
            } else if(salary >=23250 && salary <= 23749.99) {
                er = 2262.5
            } else if(salary >=23750 && salary <= 24249.99) {
                er = 2310
            } else if(salary >=24250 && salary <= 24749.99) {
                er = 2357.5
            } else if(salary >=24750 && salary <= 25249.99) {
                er = 2405
            } else if(salary >=25250 && salary <= 25749.99) {
                er = 2452.5
            } else if(salary >=25750 && salary <= 26249.99) {
                er = 2500
            } else if(salary >=26250 && salary <= 26749.99) {
                er = 2547.5
            } else if(salary >=26750 && salary <= 27249.99) {
                er = 2595
            } else if(salary >=27250 && salary <= 27749.99) {
                er = 2642.5
            } else if(salary >=27750 && salary <= 28249.99) {
                er = 2690
            } else if(salary >=28250 && salary <= 28749.99) {
                er = 2737.5
            } else if(salary >=28750 && salary <= 29249.99) {
                er = 2785
            } else if(salary >=29250 && salary <= 29749.99) {
                er = 2832.5
            } else {
                er = 2880
            }

            return er;
        },
        sssSalaryBracketEmployee(employee) {
            var salary = parseFloat(employee.Salary)
            var er = 0;
            if (salary < 4250) {
                er = 180
            } else if(salary >=4250 && salary <= 4749.99) {
                er = 202.5
            } else if(salary >=4750 && salary <= 5249.99) {
                er = 225
            } else if(salary >=5250 && salary <= 5749.99) {
                er = 247.5
            } else if(salary >=5750 && salary <= 6249.99) {
                er = 270
            } else if(salary >=6250 && salary <= 6749.99) {
                er = 292.5
            } else if(salary >=6750 && salary <= 7249.99) {
                er = 315
            } else if(salary >=7250 && salary <= 7749.99) {
                er = 337.5
            } else if(salary >=7750 && salary <= 8249.99) {
                er = 360
            } else if(salary >=8250 && salary <= 8749.99) {
                er = 382.5
            } else if(salary >=8750 && salary <= 9249.99) {
                er = 405
            } else if(salary >=9250 && salary <= 9749.99) {
                er = 427.5
            } else if(salary >=9750 && salary <= 10249.99) {
                er = 450
            } else if(salary >=10250 && salary <= 10749.99) {
                er = 472.5
            } else if(salary >=10750 && salary <= 11249.99) {
                er = 495
            } else if(salary >=11250 && salary <= 11749.99) {
                er = 517.5
            } else if(salary >=11750 && salary <= 12249.99) {
                er = 540
            } else if(salary >=12250 && salary <= 12749.99) {
                er = 562.5
            } else if(salary >=12750 && salary <= 13249.99) {
                er = 585
            } else if(salary >=13250 && salary <= 13749.99) {
                er = 607.5
            } else if(salary >=13750 && salary <= 14249.99) {
                er = 630
            } else if(salary >=14250 && salary <= 14749.99) {
                er = 652.5
            } else if(salary >=14750 && salary <= 15249.99) {
                er = 675
            } else if(salary >=15250 && salary <= 15749.99) {
                er = 697.5
            } else if(salary >=15750 && salary <= 16249.99) {
                er = 720
            } else if(salary >=16250 && salary <= 16749.99) {
                er = 742.5
            } else if(salary >=16750 && salary <= 17249.99) {
                er = 765
            } else if(salary >=17250 && salary <= 17749.99) {
                er = 787.5
            } else if(salary >=17750 && salary <= 18249.99) {
                er = 810
            } else if(salary >=18250 && salary <= 18749.99) {
                er = 832.5
            } else if(salary >=18750 && salary <= 19249.99) {
                er = 855
            } else if(salary >=19250 && salary <= 19749.99) {
                er = 877.5
            } else if(salary >=19750 && salary <= 20249.99) {
                er = 900
            } else if(salary >=20250 && salary <= 20749.99) {
                er = 922.5
            } else if(salary >=20750 && salary <= 21249.99) {
                er = 945
            } else if(salary >=21250 && salary <= 21749.99) {
                er = 967.5
            } else if(salary >=21750 && salary <= 22249.99) {
                er = 990
            } else if(salary >=22250 && salary <= 22749.99) {
                er = 1012.5
            } else if(salary >=22750 && salary <= 23249.99) {
                er = 1035
            } else if(salary >=23250 && salary <= 23749.99) {
                er = 1057.5
            } else if(salary >=23750 && salary <= 24249.99) {
                er = 1080
            } else if(salary >=24250 && salary <= 24749.99) {
                er = 1102.5
            } else if(salary >=24750 && salary <= 25249.99) {
                er = 1125
            } else if(salary >=25250 && salary <= 25749.99) {
                er = 1147.5
            } else if(salary >=25750 && salary <= 26249.99) {
                er = 1170
            } else if(salary >=26250 && salary <= 26749.99) {
                er = 1192.5
            } else if(salary >=26750 && salary <= 27249.99) {
                er = 1215
            } else if(salary >=27250 && salary <= 27749.99) {
                er = 1237.5
            } else if(salary >=27750 && salary <= 28249.99) {
                er = 1260
            } else if(salary >=28250 && salary <= 28749.99) {
                er = 1282.5
            } else if(salary >=28750 && salary <= 29249.99) {
                er = 1305
            } else if(salary >=29250 && salary <= 29749.99) {
                er = 1327.5
            } else {
                er = 1350
            }

            return er;
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

                this.showSaveFader()
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
                    }  else if (type == 'RiceAndLaundry') {
                        this.employees[i].RiceAndLaundry = value
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
        },
        getPhilHealthContribution(employee) {
            var salary = parseFloat(employee.Salary)
            return salary * .05
        },
        autoFillSss(type) {
            this.isDisplayed = '';
            this.isButtonDisabled = true;
            this.fillInputsDisabled = true

            for(let i=0; i<this.employees.length; i++) {
                if (type == 'SSSEmployer') {
                    this.employees[i].SSSEmployer = this.sssSalaryBracketEmployer(this.employees[i])
                } else if (type == 'SSSEmployee') {
                    this.employees[i].SSSEmployee = this.sssSalaryBracketEmployee(this.employees[i])
                }               
            }
            
            axios.post(`${ axios.defaults.baseURL }/employee_payroll_sundries/insert-all-contribution-array-data`, {
                    Data : this.employees,
                    Type : type,
            }).then(response => {
                this.isDisplayed = 'gone';
                this.isButtonDisabled = false;
                this.fillInputsDisabled = false

                this.showSaveFader()
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
        },      
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        autoFillPhilHealth(type) {
            this.isDisplayed = '';
            this.isButtonDisabled = true;
            this.fillInputsDisabled = true

            for(let i=0; i<this.employees.length; i++) {
                if (type == 'PhilHealthEmployer') {
                    this.employees[i].PhilHealthEmployer = this.round(this.getPhilHealthContribution(this.employees[i])/2)
                } else if (type == 'PhilHealthEmployee') {
                    this.employees[i].PhilHealthEmployee = this.round(this.getPhilHealthContribution(this.employees[i])/2)
                }               
            }
            
            axios.post(`${ axios.defaults.baseURL }/employee_payroll_sundries/insert-all-contribution-array-data`, {
                    Data : this.employees,
                    Type : type,
            }).then(response => {
                this.isDisplayed = 'gone';
                this.isButtonDisabled = false;
                this.fillInputsDisabled = false

                this.showSaveFader()
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
        this.getEmployees()
    }
}

</script>