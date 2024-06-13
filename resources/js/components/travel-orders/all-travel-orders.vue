<template>
    <div class="row p-2">
        <div class="col-lg-12 mb-3">
            <select v-model="year" class="form-control form-control-sm float-right" style="width: 150px;">
                <option v-for="y in yearsData" :value="y">{{ y }}</option>
            </select>
        </div>

        <div class="col-lg-4 col-md-6 mt-3" v-for="to in travelOrders.data" :key="to.id">
            <div class="card shadow-none" style="height: 100%;">
                <div class="card-body">
                    <div class="timeline mb-0">
                        <div style="margin-bottom: 0px !important; padding-bottom: 5px !important;">
                            <i style="font-size: .8em !important;" class="fas fa-map-marker-alt bg-gray"></i>
                            <div class="timeline-item shadow-none" style="margin-top: 0px !important; padding-top: 0px !important;">
                                <div class="timeline-body" style="margin-top: 0px !important; padding-top: 0px !important;">
                                    <span>{{ moment(to.DateFiled).format("MMMM DD, YYYY (ddd)") }}</span>
                                    <br>
                                    <span class="text-muted text-sm">{{ to.Destination }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 0px !important; padding-bottom: 5px !important;" title="Purpose">
                            <i style="font-size: .8em !important;" class="fas fa-hammer bg-gray"></i>
                            <div class="timeline-item shadow-none" style="margin-top: 0px !important; padding-top: 0px !important;">
                                <div class="timeline-body" style="margin-top: 0px !important; padding-top: 0px !important;">
                                    <p class="no-pads">{{ to.Purpose }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 0px !important; padding-bottom: 5px !important;" title="Dates">
                            <i style="font-size: .8em !important;" class="fas fa-calendar bg-gray"></i>
                            <div class="timeline-item shadow-none" style="margin-top: 0px !important; padding-top: 0px !important;">
                                <div class="timeline-body" style="margin-top: 0px !important; padding-top: 0px !important;">
                                    <p class="no-pads" v-html="toDays(to.Days)"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 0px !important; padding-bottom: 5px !important;" title="Employees">
                            <i style="font-size: .8em !important;" class="fas fa-user bg-gray"></i>
                            <div class="timeline-item shadow-none" style="margin-top: 0px !important; padding-top: 0px !important;">
                                <div class="timeline-body" style="margin-top: 0px !important; padding-top: 0px !important;">
                                    <p class="no-pads" v-html="toEmployees(to.Employees)"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-link btn-sm float-right"><i class="fas fa-trash text-gray"></i></button>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <pagination :data="travelOrders" :limit="20" @pagination-change-page="getTravelOrders"></pagination>
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
    name : 'AllTravelOrders.all-travel-orders',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
            },
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            yearsData : [],
            year : moment().format("YYYY"), /* moment().format("YYYY") */
            travelOrders : {},
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
        getTravelOrders(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/travel_orders/get-travel-orders-yearly`, {
                params: {
                    Year : this.year,
                    page : page
                }
            }).then(response => {
                this.travelOrders = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting travel orders!',
                });
                console.log(error)
            });
        },
        toDays(data) {
            var dates = data.split(',')
            var scheds = "<ul>"

            for(let i=0; i<dates.length; i++) {
                if (!this.isNull(dates[i]) && (i < dates.length-1)) {
                    scheds += "<li>" + moment(dates[i]).format("MMM DD, YYYY (ddd)") + "</li>"
                }
            }

            scheds += "</ul>"

            return scheds
        },
        toEmployees(data) {
            if (this.isNull(data)) {
                return ""
            } else {
                var datas = data.split(":")
                var employees = "<ul>"

                for(let i=0; i<datas.length; i++) {
                    if (!this.isNull(datas[i]) && (i < datas.length-1)) {
                        employees += "<li>" + datas[i] + "</li>"
                    }
                }

                employees += "</ul>"

                return employees
            }
        }
    },
    created() {
        
    },
    mounted() {
        for(let i=0; i<10; i++) {
            this.yearsData.push(moment().subtract(i, 'year').format("YYYY"))
        }

        this.getTravelOrders()
    }
}

</script>