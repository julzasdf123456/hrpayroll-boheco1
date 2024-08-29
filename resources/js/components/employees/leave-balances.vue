<template>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-12">
                    <a class="btn btn-default btn-sm float-right ml-1" title="Print" :href="origin + '/hr/public/index.php/leave_balances/print-balances/' + department"><i class="fas fa-print"></i></a>
                    <select v-model="department" @change="getEmployees()" class="form-control form-control-sm float-right" style="width: 160px;">
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
                            <table class="table table-sm table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2">Employees</th>
                                        <th class="text-center" colspan="3">Vacation</th>
                                        <th class="text-center" colspan="3">Sick</th>
                                        <th class="text-center" rowspan="2">Special</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Days</th>
                                        <th class="text-center">Hours</th>
                                        <th class="text-center">Mins</th>
                                        <th class="text-center">Days</th>
                                        <th class="text-center">Hours</th>
                                        <th class="text-center">Mins</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in employees" :key="item.id">
                                        <td>{{ item.name }}</td>
                                        <td class="text-right"><strong>{{ getDays(item.Vacation) }}</strong> <span class="text-muted text-sm"> days</span></td>
                                        <td class="text-right"><strong>{{ getHours(item.Vacation) }}</strong> <span class="text-muted text-sm"> hrs</span></td>
                                        <td class="text-right"><strong>{{ getMins(item.Vacation) }}</strong> <span class="text-muted text-sm">mins</span></td>
                                        <td class="text-right"><strong>{{ getDays(item.Sick) }}</strong> <span class="text-muted text-sm"> days</span></td>
                                        <td class="text-right"><strong>{{ getHours(item.Sick) }}</strong> <span class="text-muted text-sm"> hrs</span></td>
                                        <td class="text-right"><strong>{{ getMins(item.Sick) }}</strong> <span class="text-muted text-sm">mins</span></td>
                                        <td class="text-right"><strong>{{ Math.round(item.Special) }}</strong> <span class="text-muted text-sm"> days</span></td>
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
    name : 'LeaveBalances.leave-balances',
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
            origin : window.location.origin,
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
                // this.toast.fire({
                //     icon : 'success',
                //     text : 'Saved!'
                // })
                this.showSaveFader()
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error saving leave balance!',
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
        },
        getDays(mins) {
            try {
                var days = Math.trunc(mins / 8 / 60)

                return days
            } catch (err) {
                console.log(err)
                return 0
            }
        },
        getHours(mins) {
            try {
                mins = parseFloat(mins)
                const days = Math.trunc(mins / 8 / 60)

                const exactDaysInMins = days * 8 * 60
                var excessMins = mins - (exactDaysInMins).toFixed(2)
                var hours = excessMins / 60

                return Math.round(hours)
            } catch (err) {
                console.log(err)
                return 0
            }
        },
        getMins(mins) {
            try {
                mins = parseFloat(mins)
                const days = Math.trunc(mins / 8 / 60)

                const exactDaysInMins = days * 8 * 60
                var excessMins = mins - (exactDaysInMins).toFixed(2)
                var hours = Math.trunc(excessMins / 60)

                var totalMins = exactDaysInMins + (hours * 60)
                
                return mins - totalMins
            } catch (err) {
                console.log(err)
                return 0
            }
        },
    },
    created() {
        
    },
    mounted() {
       this.getEmployees()
    }
}

</script>