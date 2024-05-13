<template>
    <div class="row">
        <!-- CONFIG -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-3">
                            <span class="text-muted">Vehicle</span>
                            <select v-model="vehicleSelected" class="form-control form-control-sm">
                                <option value="All">All</option>
                                <option v-for="vehicle in vehicles" :key="vehicle.id" :value="vehicle.VehicleName">{{ vehicle.VehicleName }}</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <span class="text-muted">TT Options</span>
                            <select v-model="withTripTicket" class="form-control form-control-sm">
                                <option value="With Trip Tickets Only">With Trip Tickets Only</option>
                                <option value="Show All">Show All</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <span class="text-muted">From</span>
                            <flat-pickr v-model="from" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <span class="text-muted">To</span>
                            <flat-pickr v-model="to" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <span class="text-muted">Actions</span>
                            <br>
                            <button @click="getAllGRS" class="btn btn-primary btn-sm"><i class="fas fa-filter ico-tab-mini"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULTS -->
        <div class="col-lg-12 table-responsive">
            <table class="table table-hover table-sm table-bordered">
                <thead>
                    <th>Trip Ticket ID</th>
                    <th>Purpose</th>
                    <th>Vehicle</th>
                    <th>Fuel Type</th>
                    <th>Liters</th>
                    <th>Created on</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="grs in results.data">
                        <td class="v-align">{{ grs.TripTicketId }}</td>
                        <td class="v-align">{{ grs.Purpose }}</td>
                        <td class="v-align">{{ grs.Vehicle }}</td>
                        <td class="v-align">{{ grs.TypeOfFuel }}</td>
                        <td class="v-align">{{ grs.TotalLiters }}</td>
                        <td class="v-align">{{ moment(grs.created_at).format('MMM DD, YYYY') }}</td>
                        <td class="text-right v-align">
                            <button @click="printGRS(grs.TripTicketId, grs.id)" class="btn btn-sm btn-link text-warning"><i class="fas fa-print"></i></button>
                            <!-- <button class="btn btn-sm btn-link text-danger"><i class="fas fa-trash"></i></button> -->
                        </td>
                    </tr>
                </tbody>
            </table>

            <pagination :data="results" :limit="30" @pagination-change-page="getAllGRS"></pagination>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'AllGRS.all-grs',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            moment : moment,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
                // You can configure more options here as per Flatpickr documentation
            },
            vehicles : [],
            vehicleSelected : 'All',
            withTripTicket : 'Show All',
            from : '',
            to : moment().format('YYYY-MM-DD'),
            results : {}
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
        getRequisites() {
            axios.get(`${ axios.defaults.baseURL }/trip_ticket_g_rs/get-all-grs-requisites`)
            .then(response => {
                this.vehicles = response.data['Vehicles']
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting requisites!',
                });
            })
        },
        getAllGRS(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/trip_ticket_g_rs/get-all-grs`, {
                params : {
                    Vehicle : this.vehicleSelected,
                    WithTT : this.withTripTicket,
                    From : this.from,
                    To : this.to,
                }
            })
            .then(response => {
                console.log(response.data)
                this.results = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting GRS data!',
                });
            })
        },
        printGRS(ttId, grsId) {
            if (this.isNull(ttId)) {
                window.location.href = `${ axios.defaults.baseURL }/trip_ticket_g_rs/print-grs-no-tt/${grsId}`
            } else {
                window.location.href = `${ axios.defaults.baseURL }/trip_ticket_g_rs/print-grs/${ttId}/${grsId}`
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getRequisites()
        this.getAllGRS()
    }
}

</script>