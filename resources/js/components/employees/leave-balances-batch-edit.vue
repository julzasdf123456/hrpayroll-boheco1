<template>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Employees Leave Balances Configuration</h4>
                    <span class="text-muted">Fast configuration of employees' leave balances.</span>
                </div>
                <div class="col-sm-6">
                    <select v-model="department" @change="getEmployees()" class="form-control form-control-sm float-right" style="width: 200px;">
                        <option value="All">All</option>
                        <option value="ESD">ESD</option>
                        <option value="ISD">ISD</option>
                        <option value="OGM">OGM</option>
                        <option value="OSD">OSD</option>
                        <option value="PGD">PGD</option>
                        <option value="SEEAD">SEEAD</option>
                        <option value="SUB-OFFICE">SUB-OFFICE</option>
                    </select>
                </div>

                <!-- RESULTS -->
                <div class="col-lg-12 mt-4">
                    <div class="card shadow-none">
                        <div class="card-header">
                            <span class="card-title">Employees List</span>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <th>Employee</th>
                                    <th>Vacation</th>
                                    <th>Sick</th>
                                    <th>Special</th>
                                    <th>Maternity</th>
                                    <th>Maternity<br>(Solor Mother)</th>
                                    <th>Paternity</th>
                                    <th>Solo parent</th>
                                </thead>
                                <tbody>
                                    <tr v-for="item in employees" :key="item.id">
                                        <td>{{ item.name }}</td>
                                        <td>
                                            <div style="display: flex; flex-direction: row;">
                                                <input type="number" step="any" class="text-right form-control form-control-sm" 
                                                    v-model="item.Vacation"
                                                    @keyup.enter="inputEnter(item.Vacation, item.id, 'Vacation')"
                                                    @blur="inputEnter(item.Vacation, item.id, 'Vacation')">

                                                <button @click="addLeaveHours(item.Vacation, item.id, 'Vacation')" class="btn btn-xs btn-default" title="Add vacation leave hours"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: row;">
                                                <input type="number" step="any" class="text-right form-control form-control-sm" 
                                                v-model="item.Sick"
                                                @keyup.enter="inputEnter(item.Sick, item.id, 'Sick')"
                                                @blur="inputEnter(item.Sick, item.id, 'Sick')">

                                                <button @click="addLeaveHours(item.Sick, item.id, 'Sick')" class="btn btn-xs btn-default" title="Add sick leave hours"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="text-right form-control form-control-sm" 
                                            v-model="item.Special"
                                            @keyup.enter="inputEnter(item.Special, item.id, 'Special')"
                                            @blur="inputEnter(item.Special, item.id, 'Special')">
                                        </td>
                                        <td>
                                            <input type="number" step="any" v-if="!item.IsMale" class="text-right form-control form-control-sm" 
                                            v-model="item.Maternity"
                                            @keyup.enter="inputEnter(item.Maternity, item.id, 'Maternity')"
                                            @blur="inputEnter(item.Maternity, item.id, 'Maternity')">
                                        </td>
                                        <td>
                                            <input type="number" step="any" v-if="!item.IsMale"  class="text-right form-control form-control-sm" 
                                            v-model="item.MaternityForSoloMother"
                                            @keyup.enter="inputEnter(item.MaternityForSoloMother, item.id, 'MaternityForSoloMother')"
                                            @blur="inputEnter(item.MaternityForSoloMother, item.id, 'MaternityForSoloMother')">
                                        </td>
                                        <td>
                                            <input type="number" step="any" v-if="item.IsMale"  class="text-right form-control form-control-sm" 
                                            v-model="item.Paternity"
                                            @keyup.enter="inputEnter(item.Paternity, item.id, 'Paternity')"
                                            @blur="inputEnter(item.Paternity, item.id, 'Paternity')">
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="text-right form-control form-control-sm" 
                                            v-model="item.SoloParent"
                                            @keyup.enter="inputEnter(item.SoloParent, item.id, 'SoloParent')"
                                            @blur="inputEnter(item.SoloParent, item.id, 'SoloParent')">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>saved!</p>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'LeaveBalancesBatchEdit.leave-balances-batch-edit',
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
            employees : [],
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
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
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        getEmployees() {
            this.employees = []

            axios.get(`${ axios.defaults.baseURL }/leave_balances/get-merge-data`, {
                params: {
                    Department : this.department,
                }
            }).then(response => {
                var size = response.data.length;
                for (let i=0; i<size; i++) {
                    this.employees.push({
                        name : response.data[i]['LastName'] + ", " + response.data[i]['FirstName'],
                        id : response.data[i]['id'],
                        EmployeeId : response.data[i]['EmployeeId'],
                        Vacation : response.data[i]['Vacation'],
                        Sick : response.data[i]['Sick'],
                        Special : response.data[i]['Special'],
                        Maternity : response.data[i]['Maternity'],
                        MaternityForSoloMother : response.data[i]['MaternityForSoloMother'],
                        Paternity : response.data[i]['Paternity'],
                        SoloParent : response.data[i]['SoloParent'],
                        IsMale : response.data[i]['Gender']==='Male' ? true : false
                    });
                    
                    this.isDisplayed = 'gone';
                }
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting employee data!',
                });
                console.log(error)
                this.isDisplayed = 'gone';
            });
        },
        test () {
            this.toast.fire({
                icon : 'success',
                text : 'focus out!'
            })
        },
        inputEnter(value, id, type) {
            axios.post(`${ axios.defaults.baseURL }/leave_balances/update-value`, {
                Value : value,
                id : id,
                Type : type,
            }).then(response => {
                this.showSaveFader()
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error saving leave balance!',
                });
                console.log(error.response)
            })
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
        },
        async addLeaveHours(value, id, type) {
            const { value: numberInput } = await Swal.fire({
                title: `Add ${ type } Leave`,
                input: 'text',
                inputPlaceholder: `Add ${ type } Leave in HOURS`,
                inputAttributes: {
                    type: 'number',
                    min: '0', // Optional: Set minimum value
                    step: '1', // Optional: Set step value
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                preConfirm: (value) => {
                    if (!value || isNaN(value)) {
                        Swal.showValidationMessage('Please enter a valid number')
                        return false;
                    }
                },
            })

            if (numberInput) {
                // update value
                value = parseFloat(value)
                const addedHours = parseFloat(numberInput)
                const newVal = this.computeAddedHours(value, addedHours)

                axios.post(`${ axios.defaults.baseURL }/leave_balances/update-value`, {
                    Value : newVal,
                    id : id,
                    Type : type,
                    AddedValue : addedHours,
                }).then(response => {
                    this.showSaveFader()

                    if (type === 'Vacation') {
                        this.employees = this.employees.map(obj => 
                            obj.id === id 
                                ? { ...obj, Vacation : newVal }
                                : obj 
                        )
                    } else if (type === 'Sick') {
                        this.employees = this.employees.map(obj => 
                            obj.id === id 
                                ? { ...obj, Sick : newVal }
                                : obj 
                        )
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error saving leave balance!',
                    });
                    console.log(error.response)
                })
            }
        },
        computeAddedHours(oldVal, addedValInHours) {
            const addInMins = addedValInHours * 60

            return oldVal + addInMins
        }
    },
    created() {
        
    },
    mounted() {
        this.getEmployees()
    }
}

</script>