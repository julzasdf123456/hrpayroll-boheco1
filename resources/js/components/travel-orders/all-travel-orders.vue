<template>
    <div class="row p-2">
        <div class="col-lg-12 mb-3">
            <select v-model="year" class="form-control form-control-sm float-right" style="width: 150px;" @change="getTravelOrders">
                <option v-for="y in yearsData" :value="y">{{ y }}</option>
            </select>
        </div>

        <div class="col-lg-4 col-md-6 mt-3" v-for="to in travelOrders.data" :key="to.id">
            <div class="card shadow-none" style="height: 100%;">
                <div class="card-header border-0 pt-2 pb-0 mb-0">
                    <span class="badge" :class="isNull(to.Status) ? 'bg-warning' : 'bg-success'">{{ isNull(to.Status) ? 'PENDING' : to.Status }}</span>

                    <div class="card-tools">
                        <button @click="deleteOrder(to.id)" class="btn btn-link btn-sm float-right"><i class="fas fa-trash text-gray"></i></button>
                    </div>
                </div>
                <div class="card-body mt-0 pt-0">
                    <p class="mt-3">{{ to.Purpose }}</p>
                    
                    <span>{{ moment(to.DateFiled).format("MMMM DD, YYYY (ddd)") }}</span>
                    <br>
                    <span class="text-muted text-sm"><i class="fas fa-map-marker-alt ico-tab-mini"></i>{{ to.Destination }}</span>
                    
                    <div class="divider my-3"></div>

                    <span class="text-muted text-sm">Days Covered</span>
                    <p class="no-pads" v-html="toDays(to.Days)"></p>

                    <span class="text-muted text-sm">Employees</span>
                    <p class="no-pads" v-html="toEmployees(to.Employees)"></p>
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
                        employees += "<li><strong>" + datas[i] + "</strong></li>"
                    }
                }

                employees += "</ul>"

                return employees
            }
        },
        deleteOrder(id) {
            Swal.fire({
                title: "Delete Travel Order?",
                text : `This will also delete the employee's travel order attendance. This can't be undone`,
                showCancelButton: true,
                confirmButtonText: "Delete",
                confirmButtonColor: '#e03822',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`${ axios.defaults.baseURL }/travelOrders/${id}`)
                    .then(response => {
                        this.travelOrders.data = this.travelOrders.data.filter(obj => obj.id !== id)
                        this.toast.fire({
                            icon : 'success',
                            text : 'Travel order removed!'
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error removing travel order!\n' + error.response.data
                        })
                    })
                }
            })
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