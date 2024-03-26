<template>
    <!-- TRIP TICKET HISTORIES -->
    <div class="section">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Your Trip Ticket History</p>
                    <p class="no-pads text-muted">List of your BOHECO I trips. Some of these trips might be filed by your coleagues and you're just a passenger.</p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 75% !important;" class="img-fluid" :src="imgsPath + 'trip-tickets.png'" alt="User profile picture">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 mb-4">
                <span class="text-muted">Start Filter From</span>
                <br>
                <flat-pickr v-model="startFrom" :config="pickerOptions" :readonly="false" class="form-control" style="width: 280px; display: inline-block;" @on-change="getTripTickets()"></flat-pickr>

                <a target="_blank" :href="baseURL + '/trip_tickets/my-trip-tickets/' + userid" class="btn btn-primary float-right">Manage All</a>
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
                                <p class="ellipsize-3 pl-4" style="height: 60px;" :title="trips.PurposeOfTravel">{{ isNull(trips.PurposeOfTravel) ? '-' : trips.PurposeOfTravel.replace(/;/g, '\n') }}</p>
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
                    <p class="no-pads text-md">Your Duty Offsets</p>
                    <p class="no-pads text-muted">List of your offsetted days. </p>
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

    <!-- TRAVEL ORDERS -->
    <div class="section">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Your Travel Order History</p>
                    <p class="no-pads text-muted">List of your BOHECO I official travels.</p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 100% !important;" class="img-fluid" :src="imgsPath + 'travels.png'" alt="User profile picture">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 mb-4">
                <span class="text-muted">Start Filter From</span>
                <flat-pickr v-model="startTravelFrom" :config="pickerOptions" :readonly="false" class="form-control" style="width: 280px;" @on-change="getTravels()"></flat-pickr>
            </div>
            <div class="col-lg-4 col-md-6" v-for="travel in travels.data" :key="travel.id">
                <div class="card shadow-none" style="height: 300px;">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <span class="badge bg-info mb-3">{{ travel.Status }}</span>
                                <p class="ellipsize-2 mb-1" style="font-size: 1.3em;">{{ travel.Purpose }}</p>
                                <span class="text-muted"><i class="fas fa-map-marker-alt ico-tab-mini"></i>{{ travel.Destination }}</span>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <span class="text-muted"><i class="fas fa-calendar ico-tab-mini"></i>Travel Days</span>
                                <ul>
                                    <li v-for="date in travel.Days">{{ moment(date.Day).format("MMM DD, YYYY (ddd)") }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-lg-12">
                <pagination :data="travels" :limit="6" @pagination-change-page="getTravels"></pagination>
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
            userid : document.querySelector("meta[name='user-id-current']").getAttribute('content'),
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
            travels : {},
            startTravelFrom : moment().format("YYYY-MM-DD"),
            imgsPath : axios.defaults.imgsPath,
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
        getTravels(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/travel_orders/get-travel-orders-ajax`, {
                params : {
                    page : page,
                    EmployeeId : this.employeeId,
                    Start : this.startTravelFrom,
                }
            })
            .then(response => {
                this.travels = response.data
                console.log(response.data)
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting travel orders!\n' + error.response.data
                })
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getTripTickets()
        this.getOffsets()
        this.getTravels()
    }
}

</script>