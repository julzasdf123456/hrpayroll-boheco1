<template>
    <div class="card shadow-none mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-md">OT Summary</p>
                </div>
                <div class="col-lg-3 col-md-6 mt-2">
                    <span class="text-muted">Choose Year</span>
                    <select v-model="yearSelect" class="form-control" @change="getOvertimes()">
                        <option v-for="year in years" :key="year">{{ year }}</option>
                    </select>
                </div>
                <div class="col-lg-9 col-md-6">
                    <div class="spinner-border text-primary float-right" :class="showLoader" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div class="col-lg-12 mt-3 table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Total Hours</th>
                            <th>OT Purpose</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="ot in overTimes.data" :key="ot.id">
                                <td class="v-align">
                                    {{ ot.TypeOfDay }}
                                    <br>
                                    <span class="text-muted">{{ ot.Multiplier }}x multiplier</span>
                                </td>
                                <td class="v-align">
                                    {{ moment(ot.DateofOT).format("MMM DD, YYYY (ddd)") }}
                                    <br>
                                    <span class="text-muted">{{ isNull(ot.From) ? '-' : moment(ot.From.split(".")[0], "HH:mm:ss").format("hh:mm A") }}</span>
                                </td>
                                <td class="v-align">
                                    {{ moment(ot.DateOTEnded).format("MMM DD, YYYY (ddd)") }}
                                    <br>
                                    <span class="text-muted">{{ isNull(ot.To) ? '-' : moment(ot.To.split(".")[0], "HH:mm:ss").format("hh:mm A") }}</span>
                                </td>
                                <td class="v-align">
                                    <strong>{{ isNull(ot.TotalHours) ? '-' : ot.TotalHours  }}</strong>
                                    <br>
                                    <span class="text-muted">{{ ot.MaxHourThreshold }} hours max limit</span>
                                </td>
                                <td class="v-align">
                                    {{ ot.PurposeOfOT }}
                                </td>
                                <td class="v-align text-right">
                                    <span class="badge bg-info">{{ ot.Status }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="col-lg-12">
                        <pagination :data="overTimes" :limit="10" @pagination-change-page="getOvertimes"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            
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
    name : 'Overtime.overtime',
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
            },
            years : [],
            yearSelect : moment().format("YYYY"),
            overTimes : {},
            showLoader : 'gone'
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
        getOvertimes(page = 1) {
            this.showLoader = ''
            axios.get(`${ axios.defaults.baseURL }/overtimes/get-overtimes-by-employee`, {
                params : {
                    page : page,
                    EmployeeId : this.employeeId,
                    Year : this.yearSelect,
                }
            })
            .then(response => {
                this.overTimes = response.data
                this.showLoader = 'gone'
            })
            .catch(error => {
                this.showLoader = 'gone'
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting overtime data!\n' + error.response.data
                })
            })
        },
    },
    created() {
        
    },
    mounted() {
        for(let i=0; i<10; i++) {
            this.years.push(moment().subtract(i, 'year').format("YYYY"))
        }
        this.getOvertimes()
    }
}

</script>