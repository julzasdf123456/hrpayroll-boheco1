<template>
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="col-lg-8">
            <h4>Incentives Annual Projection</h4>
            <span class="text-muted">Projects future possible benefits and incentives for the computation of withholding taxes.</span>
        </div>
        <div class="col-lg-4">
            <select v-model="years" @change="getIncentives()" class="form-control form-control-sm float-right" style="width: 120px;">
                <option v-for="year in yearsData" :value="year">{{ year }}</option>
            </select>
        </div>

        <div class="col-lg-12" style="margin-top: 15px;">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title">Incentives, Benefits, and Bonus Projections for Year <strong>{{ years }}</strong></span>
                    <div class="card-tools">
                        <button v-if="isGenerateButtonShown" @click="generateFromDefault()" class="btn btn-tool btn-primary"><i class="fas fa-sort-amount-down ico-tab-mini"></i>Generate Projection</button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Incentive/Benefit</th>
                            <th class="text-right">Amount</th>
                            <th>Taxable</th>
                            <th class="text-right">Untaxable Threshold Amount</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="projection in projections" :key="projection.id">
                                <td>
                                    <input class="table-input" :class="tableInputTextColor" v-model="projection.Incentive" @keyup.enter="inputEnter(projection.Incentive, projection.id, 'Incentive')" @blur="inputEnter(projection.Incentive, projection.id, 'Incentive')" type="text"/>
                                </td>
                                <td class="text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="projection.Amount" @keyup.enter="inputEnter(projection.Amount, projection.id, 'Amount')" @blur="inputEnter(projection.Amount, projection.id, 'Amount')" type="number" step="any"/>
                                </td>
                                <td>
                                    <select v-model="projection.Taxable" class="form-control form-control-sm" @change="inputEnter(projection.Taxable, projection.id, 'IsTaxable')">
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </td>
                                <td class="text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="projection.MaxUntaxableThreshold" @keyup.enter="inputEnter(projection.MaxUntaxableThreshold, projection.id, 'MaxUntaxableThreshold')" @blur="inputEnter(projection.MaxUntaxableThreshold, projection.id, 'MaxUntaxableThreshold')" type="number" step="any"/>
                                </td>
                                <td class="text-right">
                                    <button @click="remove(projection.id)" class="btn btn-sm btn-link"><i class="text-danger fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button v-if="isAddNewShown" @click="addNewRow()" class="btn btn-sm btn-primary" style="margin-top: 15px; margin-left: 15px; margin-bottom: 20px;"><i class="fas fa-plus-circle ico-tab-mini"></i>Add New</button>

                    <button v-if="isProjectToAllShown" @click="projectToAllEmployees()" class="btn btn-sm btn-success float-right" style="margin-top: 15px; margin-right: 15px; margin-bottom: 20px;"><i class="fas fa-arrow-right ico-tab-mini"></i>Project to All Employees</button>
                </div>
            </div>
        </div>
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
            moment : moment,
            yearsData : [],
            years : null, /* moment().format("YYYY") */
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            defaultProjections : [
                {
                    Incentive : 'Anniversary Incentives',
                    Amount : 10000,
                    Taxable : true,
                    MaxUntaxableThreshold : 0,                    
                },
                {
                    Incentive : 'Award Incentive',
                    Amount : 10000,
                    Taxable : true,
                    MaxUntaxableThreshold : 0,                    
                },
                {
                    Incentive : 'Cash Gift',
                    Amount : 10000,
                    Taxable : true,
                    MaxUntaxableThreshold : 5000,                    
                },
                {
                    Incentive : 'Uniform Allowance',
                    Amount : 8000,
                    Taxable : true,
                    MaxUntaxableThreshold : 6000,                    
                },
                {
                    Incentive : 'Medical Allowance',
                    Amount : 11000,
                    Taxable : true,
                    MaxUntaxableThreshold : 10000,                    
                },
                {
                    Incentive : 'Rice and Laundry',
                    Amount : 30000,
                    Taxable : false,
                    MaxUntaxableThreshold : 0,                    
                },
                {
                    Incentive : 'Productivity Scheme (Performance Bonus)',
                    Amount : 10000,
                    Taxable : false,
                    MaxUntaxableThreshold : 10000,                    
                }
            ],
            projections : [],
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            isGenerateButtonShown : false,
            isAddNewShown : false,
            hasNew : false,
            isProjectToAllShown : false,
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
            return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        getIncentives() {
            this.projections = []
            this.isProjectToAllShown = false 
            axios.get(`${ axios.defaults.baseURL }/incentives_annual_projections/get-incentives-per-year`, {
                params: {
                    Year : this.years,
                }
            }).then(response => {                
                if (this.isNull(response.data)) {
                    this.isGenerateButtonShown = true
                    this.toast.fire({
                        icon : 'info',
                        text : 'No projections made for this year.'
                    })
                } else {
                    this.isGenerateButtonShown = false
                    this.isAddNewShown = true
                    var size = response.data.length
                    for(let i=0; i<size; i++) {
                        var datas = response.data[i]
                        this.projections.push({
                            id : datas['id'],
                            Incentive : datas['Incentive'],
                            Amount : datas['Amount'],
                            Taxable : datas['IsTaxable'],
                            MaxUntaxableThreshold : datas['MaxUntaxableAmount'] 
                        })
                    }
                }                
            })
            .catch(error => {
                this.isGenerateButtonShown = false
                this.isAddNewShown = false
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting projection data!',
                });
                console.log(error)
            });
        },
        generateFromDefault() {
            if (this.isNull(this.years)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Select year first!'
                })
            } else {
                this.projections = this.defaultProjections

                axios.get(`${ axios.defaults.baseURL }/incentives_annual_projections/insert-datas`, {
                    params: {
                        Data : this.projections,
                        Year : this.years,
                    }
                }).then(response => {
                    this.toast.fire({
                        icon : 'success',
                        text : 'Projections Generated!'
                    })

                    this.projections = []
                    var size = response.data.length
                    for(let i=0; i<size; i++) {
                        var datas = response.data[i]
                        this.projections.push({
                            id : datas['id'],
                            Incentive : datas['Incentive'],
                            Amount : datas['Amount'],
                            Taxable : datas['IsTaxable'],
                            MaxUntaxableThreshold : datas['MaxUntaxableAmount'] 
                        })
                    }

                    this.isGenerateButtonShown = false
                    this.isProjectToAllShown = true
                })
                .catch(error => {
                    this.isGenerateButtonShown = true
                    this.isProjectToAllShown = false
                    Swal.fire({
                        icon : 'error',
                        title : 'Error generating default projections!',
                    });
                    console.log(error)
                });
            }
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
        inputEnter(value, iddata, colToUpdate) {
            this.hasNew = false
            axios.get(`${ axios.defaults.baseURL }/incentives_annual_projections/update-data`, {
                params: {
                    id : iddata,
                    Data : value,
                    ColumnToUpdate : colToUpdate,
                    Year : this.years,
                }
            }).then(response => {                
                // FIND EMPTY IDs TO BE REPLACED BY A NEW ID IF NEW ENTRY
                this.isProjectToAllShown = true
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error updating projection data!',
                });
                console.log(error)
                this.isProjectToAllShown = false
            });
        },
        addNewRow() {
            if (!this.hasNew) {
                this.hasNew = true
                this.projections.push({
                    id : this.generateUniqueId(),
                    Incentive : null,
                    Amount : null,
                    Taxable : null,
                    MaxUntaxableThreshold : null,
                })
            }
            
        },
        remove(id) {            
            Swal.fire({
                title: "Remove this incentive projection?",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.get(`${ axios.defaults.baseURL }/incentives_annual_projections/remove`, {
                    params: {
                        id : id,
                    }
                }).then(response => {                
                    this.isProjectToAllShown = true
                    this.projections = this.projections.filter(obj => obj.id !== id)
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error deleting projection data!',
                    });
                    console.log(error)
                    this.isProjectToAllShown = false
                });
                }
            });
        },
        projectToAllEmployees() {
            Swal.fire({
                title: "Project to all employees?",
                showCancelButton: true,
                confirmButtonText: "Proceed",
                html: `
                    It will also project the following incentives/benefits:<br><br>
                    <ul>
                        <li style='text-align: left;'>13th Month Pay</li>
                        <li style='text-align: left;'>14th Month Pay</li>
                        <li style='text-align: left;'>Representation Allowances (if applicable)</li>
                        <li style='text-align: left;'>Longevity (if applicable)</li>
                    </ul>
                `,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title : 'Projecting Data',
                        text : 'This may take 30-50 seconds. Please wait...',
                        allowOutsideClick : false,
                        didOpen : () => {
                            Swal.showLoading()
                        }
                    })
                    axios.get(`${ axios.defaults.baseURL }/employee_incentive_annual_projections/project-all`, {
                        params: {
                            Data : this.projections,
                            Year : this.years,
                        }
                    }).then(response => {
                        Swal.close()
                        this.toast.fire({
                            icon : 'success',
                            text : 'Data projected to all employees!'
                        })
                    })
                    .catch(error => {
                        Swal.close()
                        Swal.fire({
                            icon : 'error',
                            title : 'Error projecting to all employees!',
                        });
                        console.log(error)
                    });
                }
            });
        },
    },
    created() {
        
    },
    mounted() {
        for(let i=0; i<10; i++) {
            this.yearsData.push(moment().subtract(i, 'year').format("YYYY"))
        }
    }
}

</script>