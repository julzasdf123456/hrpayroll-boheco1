<template>
    <div class="row">
        <!-- ON LEAVE -->
        <div class="col-lg-6 col-md-12 mt-2" v-if="(onLeave.length < 1 ? false : true)">
            <div class="card shadow-none" style="height: 100%;">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-file-export ico-tab"></i>Employees on Leave Today</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Employee</th>
                            <th class="text-center">Leave Type</th>
                            <th>Reason</th>
                        </thead>
                        <tbody>
                            <tr v-for="employee in onLeave" :key="employee.id">
                                <td><strong>{{ employee.LastName + ', ' + employee.FirstName + (isNull(employee.Suffix) ? '' : employee.Suffix + ' ') + (isNull(employee.MiddleName) ? '' : employee.MiddleName) }}</strong></td>
                                <td class="text-center"><span class="badge " :class="leaveTypeColor(employee.LeaveType)">{{ employee.LeaveType }}</span></td>
                                <td>{{ employee.Content }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

        <!-- ON A TRIP TICKET -->
        <div class="col-lg-6 col-md-12 mt-2" v-if="(onTrip.length < 1 ? false : true)">
            <div class="card shadow-none" style="height: 100%;">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-car ico-tab"></i>Employees with Trips Today</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Requisitioner</th>
                            <th>Driver</th>
                            <th>Passengers</th>
                            <th>Destination</th>
                        </thead>
                        <tbody>
                            <tr v-for="trips in onTrip" :key="trips.id">
                                <td>
                                    <strong>{{ trips.LastName + ', ' + trips.FirstName + (isNull(trips.Suffix) ? '' : trips.Suffix + ' ') }}</strong>
                                    <br>
                                    <span class="badge " :class="statusColor(trips.Status)">{{ trips.Status }}</span>
                                </td>
                                <td>
                                    {{ trips.DriverLastName + ', ' + trips.DriverFirstName + (isNull(trips.DriverSuffix) ? '' : trips.DriverSuffix + ' ') }} 
                                    <br>
                                    <span class="text-muted">{{ trips.Vehicle }}</span>
                                </td>
                                <td>
                                    <ul>
                                        <li v-for="passenger in trips.Passengers">{{ passenger.FirstName + ' ' + passenger.LastName }}</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li v-for="destination in trips.Destinations">{{ destination.DestinationAddress }}</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

        <!-- ON OFFSET -->
        <div class="col-lg-6 col-md-12 mt-2" v-if="(onOffset.length < 1 ? false : true)">
            <div class="card shadow-none" style="height: 100%;">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-random ico-tab"></i>Employees Offsetting Dayoff Today</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Employee</th>
                            <th>Date of Duty</th>
                            <th>Purpose of Duty</th>
                        </thead>
                        <tbody>
                            <tr v-for="employee in onOffset" :key="employee.id">
                                <td><strong>{{ employee.LastName + ', ' + employee.FirstName + (isNull(employee.Suffix) ? '' : employee.Suffix + ' ') + (isNull(employee.MiddleName) ? '' : employee.MiddleName) }}</strong></td>
                                <td>{{ moment(employee.DateOfOffset).format("MMMM DD, YYYY") }}</td>
                                <td>{{ employee.PurposeOfDuty }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
        
        <!-- ON TRAVEL -->
        <div class="col-lg-6 col-md-12 mt-2" v-if="(onTravel.length < 1 ? false : true)">
            <div class="card shadow-none" style="height: 100%;">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-plane-departure ico-tab"></i>Employees on an Official Travel Today</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Employee</th>
                            <th>Travel Destination</th>
                            <th>Travel Purpose</th>
                        </thead>
                        <tbody>
                            <tr v-for="employee in onTravel" :key="employee.id">
                                <td><strong>{{ employee.LastName + ', ' + employee.FirstName + (isNull(employee.Suffix) ? '' : employee.Suffix + ' ') + (isNull(employee.MiddleName) ? '' : employee.MiddleName) }}</strong></td>
                                <td>{{ employee.Destination }}</td>
                                <td>{{ employee.Purpose }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'EmployeeFinder.employee-finder',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            files : {},
            onLeave : [],
            onTrip : [],
            onOffset : [],
            onTravel : [],
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
        leaveTypeColor(type) {
            if (type === 'Vacation') {
                return 'bg-success'
            } else if (type === 'Sick') {
                return 'bg-danger'
            } else if (type === 'Special') {
                return 'bg-info'
            } else {
                return 'bg-primary'
            }
        },  
        statusColor(status) {
            if (status === 'APPROVED') {
                return 'bg-success'
            } else if (status === 'FILED') {
                return 'bg-warning'
            } else {
                return 'bg-info'
            }
        }, 
        getEmployeesOnLeave() {
            this.files = {}
            axios.get(`${ axios.defaults.baseURL }/employees/get-employees-onleave-today`)
            .then(response => {
                this.onLeave = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employees on leave today!\n' + error.response.data
                })
            })
        }, 
        getEmployeesOnTrip() {
            this.files = {}
            axios.get(`${ axios.defaults.baseURL }/employees/get-employees-ontrip-today`)
            .then(response => {
                this.onTrip = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employees on trip today!\n' + error.response.data
                })
            })
        }, 
        getEmployeesOnOffset() {
            this.files = {}
            axios.get(`${ axios.defaults.baseURL }/employees/get-employees-onoffset-today`)
            .then(response => {
                this.onOffset = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employees on offset today!\n' + error.response.data
                })
            })
        },
        getEmployeesOnTravel() {
            this.files = {}
            axios.get(`${ axios.defaults.baseURL }/employees/get-employees-ontravel-today`)
            .then(response => {
                this.onTravel = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employees on travel today!\n' + error.response.data
                })
            })
        },
    },
    created() {
        
    },
    mounted() {
        this.getEmployeesOnLeave()
        this.getEmployeesOnTrip()
        this.getEmployeesOnOffset()
        this.getEmployeesOnTravel()
    }
}

</script>