<template>
    <!-- TRIP TICKET HISTORIES -->
    <div class="section">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Trip Ticket History</p>
                    <p class="no-pads text-muted">List of this employee's BOHECO I trips.</p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 75% !important;" class="img-fluid" :src="imgsPath + 'trip-tickets.png'" alt="User profile picture">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 mb-4">
                <span class="text-muted">Start Filter From</span>
                <flat-pickr v-model="startFrom" :config="pickerOptions" :readonly="false" class="form-control" style="width: 280px;" @on-change="getTripTickets()"></flat-pickr>
            </div>
            <div class="col-lg-4 col-md-6" v-for="trips in tripTickets.data" :key="trips.id">
                <div class="card shadow-none" style="height: 350px;">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <span class="badge bg-info mb-3">{{ trips.Status }}</span>
                                <p class="text-muted ellipsize no-pads"><i class="fas fa-calendar ico-tab-mini"></i>{{ moment(trips.DateOfTravel).format("ddd, MMM DD, YYYY") }}</p>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <p class="text-muted ellipsize no-pads"><i class="fas fa-tools ico-tab-mini"></i>Travel Purpose</p>
                                <p class="ellipsize-3 pl-4" style="height: 60px;" :title="trips.PurposeOfTravel">{{ trips.PurposeOfTravel.replace(/;/g, '\n') }}</p>
                            </div>
                            <div class="col-lg-12 mt-1">
                                <p class="text-muted ellipsize no-pads"><i class="fas fa-map-marker-alt ico-tab-mini"></i>Destination(s)</p>
                                <p class="ellipsize-2 pl-4" :title="trips.Destinations">{{ trips.Destinations.replace(/,/g, ' • ') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-success">
                        <span title="Vehicle"><i class="fas fa-car ico-tab"></i>{{ trips.Vehicle }}</span>
                        <br>
                        <span title="Driver"><i class="fas fa-user-secret ico-tab"></i>{{ trips.DriverFirstName + " " + trips.DriverLastName }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <pagination :data="tripTickets" :limit="6" @pagination-change-page="getTripTickets"></pagination>
            </div>
        </div>
        
    </div>

    <!-- OFFSETS -->
    <div class="section">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Duty Offsets</p>
                    <p class="no-pads text-muted">List of this employee's offsetted days. </p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 80% !important;" class="img-fluid" :src="imgsPath + 'offsets.png'" alt="User profile picture">
            </div>
        </div>

        <div class="card shadow-none mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-md no-pads">Offset Summary</p>
                        <span class="text-muted">Sorted from latest to oldest</span>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <span class="text-muted">Start Filter From</span>
                        <flat-pickr v-model="startOffsetFrom" :config="pickerOptions" :readonly="false" class="form-control" style="width: 280px;" @on-change="getOffsets()"></flat-pickr>
                    </div>
                   <div class="col-lg-12 table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <th>Date of Offset</th>
                                <th>Reason</th>
                                <th>Date of Duty</th>
                                <th>Duty Purpose</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr v-for="offset in offsets.data" :key="offset.id">
                                    <td>{{ moment(offset.DateOfOffset).format("MMM DD, YYYY (ddd)") }}</td>
                                    <td>{{ offset.OffsetReason }}</td>
                                    <td>{{ moment(offset.DateOfDuty).format("MMM DD, YYYY (ddd)") }}</td>
                                    <td>{{ offset.PurposeOfDuty }}</td>
                                    <td class="text-right">
                                        <span class="badge bg-info">{{ offset.Status }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-lg-12">
                            <pagination :data="offsets" :limit="12" @pagination-change-page="getOffsets"></pagination>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LEAVE -->
    <div class="section">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Leave Summary</p>
                    <p class="no-pads text-muted">List of this employee's leave. </p>

                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 80% !important;" class="img-fluid" :src="imgsPath + 'leave-history.png'" alt="User profile picture">
            </div>
        </div>

        <div class="card shadow-none mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <span class="text-muted">Filter Leave</span>
                        <select v-model="leaveType" class="form-control" @change="getLeaveData">
                            <option value="All">All</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Sick">Sick</option>
                            <option value="Special">Special</option>
                            <option value="Paternity">Paternity</option>
                            <option value="Maternity">Maternity</option>
                            <option value="MaternityForSoloMother">Maternity for Solo Mom</option>
                            <option value="SoloParent">Solo Parent</option>
                        </select>
                    </div>
                
                    <div class="col-lg-12 mt-4 table-responsive">
                        <table class="table table-md table-hover">
                            <thead>
                                <th style="width: 32px;"></th>
                                <th>Date Filed</th>
                                <th>Reason</th>
                                <th class="text-right"># of Days</th>
                                <th class="text-center">Status</th>
                            </thead>
                            <tbody>
                                <tr v-for="leave in leaveData.data" :key="leave.id" @click="leaveView(leave.id)" style="cursor: pointer;">
                                    <td :title="leave.LeaveType"><i class="fas" :class="getIconType(leave.LeaveType)"></i></td>
                                    <td>{{ moment(leave.created_at).format("MMMM DD, YYY") }}</td>
                                    <td>{{leave.Content }}</td>
                                    <td class="text-right">{{leave.TotalDays }}</td>
                                    <td class="text-center"><span class="badge" :class="getStatusBadgeColor(leave.Status)">{{leave.Status }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                
                        <pagination :data="leaveData" :limit="10" @pagination-change-page="getLeaveData"></pagination>
                    </div>
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
    name : 'AttendanceIndex.attendance-index',
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
            employeeId : document.querySelector("meta[name='employee-id-current']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
                // You can configure more options here as per Flatpickr documentation
            },
            tripTickets : {},
            startFrom : moment().format("YYYY-MM-DD"),
            offsets : {},
            startOffsetFrom : moment().format("YYYY-MM-DD"),
            leaveData : {},
            leaveType : 'All',
            imgsPath : axios.defaults.imgsPath
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
        getTripTickets(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/trip_tickets/get-trip-tickets-by-employee`, {
                params : {
                    page : page,
                    EmployeeId : this.employeeId,
                    StartDate : this.startFrom,
                }
            })
            .then(response => {
                this.tripTickets = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting trip ticket data!\n' + error.response.data
                })
            })
        },
        getOffsets(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/offset_applications/get-offsets-by-employee`, {
                params : {
                    page : page,
                    EmployeeId : this.employeeId,
                    StartDate : this.startOffsetFrom,
                }
            })
            .then(response => {
                this.offsets = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting offset data!\n' + error.response.data
                })
            })
        },
        getStatusBadgeColor(status) {
            if (this.isNull(status)) {
                return 'bg-info'
            } else if (status === 'Filed') {
                return 'bg-primary'
            } else if (status == 'REJECTED') {
                return 'bg-danger'
            } else {
                return 'bg-success'
            }
        },
        getIconType(leaveType) {
            if (leaveType === 'Vacation') {
                return 'fa-umbrella-beach'
            } else if (leaveType === 'Sick') {
                return 'fa-clinic-medical'
            } else if (leaveType === 'Special') {
                return 'fa-birthday-cake'
            } else if (leaveType === 'Paternity') {
                return 'fa-male'
            } else if (leaveType === 'Maternity' | leaveType === 'MaternityForSoloMother') {
                return 'fa-female'
            } else if (leaveType === 'SoloParent') {
                return 'fa-suitcase-rolling'
            } else {
                return 'fa-circle'
            }
        },
        getLeaveData(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/leave_balances/get-leave-data`, {
                params : {
                    page : page,
                    LeaveType : this.leaveType,
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.leaveData = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error fetching leave data'
                })
            })
        },
        leaveView(id) {
            window.location.href = `${ axios.defaults.baseURL }/my_account/view-leave/` + id
        },
    },
    created() {
        
    },
    mounted() {
        this.getTripTickets()
        this.getOffsets()
        this.getLeaveData()
    }
}

</script>