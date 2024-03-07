<template>
    <div class="section">
        <div class="row">
            <div class="col-lg-11 col-md-9">
                <p class="no-pads text-md">Your Trip Ticket History</p>
                <p class="no-pads text-muted">List of your BOHECO I trips. Some of these trips might be filed by your coleagues and you're just a passenger.</p>
            </div>
            <div class="col-lg-1 col-md-3 center-contents">
                <img style="width: 100% !important;" class="img-fluid" src="../../../../public/imgs/trip-tickets.png" alt="User profile picture">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 mb-4">
                <span class="text-muted">Start Filter From</span>
                <flat-pickr v-model="startFrom" :config="pickerOptions" class="form-control form-control-sm" style="width: 280px;" @on-change="getTripTickets()"></flat-pickr>
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
    name : 'PersonalInfo.personal-info',
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
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
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
            startFrom : moment().format("YYYY-MM-DD")
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
        }
    },
    created() {
        
    },
    mounted() {
        this.getTripTickets()
    }
}

</script>