<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <th>Employee</th>
                        <th>Duty Purpose/Reason</th>
                        <th>Type of Day</th>
                        <th>Start of OT</th>
                        <th>End of OT</th>
                        <th>Max OT Hours<br>Allowed</th>
                        <th>Total OT<br>Hours</th>
                        <th>OT Amount</th>
                        <th>Remarks/Notes</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="q in queue">
                            <td class="v-align">{{ q.LastName + ', ' + q.FirstName }}</td>
                            <td class="v-align">{{ q.PurposeOfOT }}</td>
                            <td class="v-align">
                                <select class="form-control form-control-sm" v-model="q.TypeOfDay" @change="updateAll(q)">
                                    <option value=""></option>
                                    <option value="Rest Day">Rest Day</option>
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Regular Holiday + Rest Day">Regular Holiday + Rest Day</option>
                                    <option value="Special Non-Working Holiday">Special Non-Working Holiday</option>
                                    <option value="Special Non-Working Holiday + Rest Day">Special Non-Working Holiday + Rest Day</option>
                                </select>
                            </td>
                            <td class="v-align">
                                {{ moment(q.DateOfOT).format('ddd, MMM DD, YYYY') }}
                                <br>
                                <div style="display: flex; flex-direction: row; column-gap: 2px;">
                                    <input @change="addTimeManually('Start', q)" type='time' class='form-control form-control-sm' v-model="q.From"/>
                                    <button @click="fetchFromBio('Start', q)" title='Fetch from biometrics' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>
                                </div>
                            </td>
                            <td class="v-align">
                                {{ moment(q.DateOTEnded).format('ddd, MMM DD, YYYY') }}
                                <br>
                                <div style="display: flex; flex-direction: row; column-gap: 2px;">
                                    <input @change="addTimeManually('Start', q)" type='time' class='form-control form-control-sm' v-model="q.To"/>
                                    <button @click="fetchFromBio('End', q)" title='Fetch from biometrics' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>
                                </div>
                            </td>
                            <td class="v-align">
                                <input @keyup="updateAll(q)" type='number' step="any" class='form-control form-control-sm' v-model="q.MaxHourThreshold"/>
                            </td>
                            <td class="v-align">
                                <input @keyup="updateTotalHours(q)" type='number' step="any" class='form-control form-control-sm' v-model="q.TotalHours"/>
                            </td>
                            <td class="v-align text-right">
                                ₱ {{ toMoney(q.OTAmount) }}
                            </td>
                            <td class="v-align">
                                <textarea class="form-control form-control-sm" v-model="q.Notes"></textarea>
                            </td>
                            <td class="text-right v-align">
                                <button @click="saveForPayroll(q)" class="btn btn-sm btn-primary"><i class="fas fa-check-circle ico-tab-mini"></i>Save for Payroll</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- fetch from bio -->
    <div ref="modalFetchFromBio" class="modal fade" id="modal-fetch-from-bio" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4>
                            Fetch Data from Biometric Device
                            <div v-if="fetchLoader" id="loader" class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </h4>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered table-hover" id="bio-table">
                        <thead>
                            <th>Date</th>
                            <th>Time</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="bio in bioData">
                                <td class="v-align">{{ moment(bio.Timestamp).format("MMM DD, YYYY") }}</td>
                                <td class="v-align">{{ moment(bio.Timestamp).format("hh:mm A") }}</td>
                                <td class="text-right v-align">
                                    <button @click="insertBiometricTime(bio.Timestamp)" class="btn btn-success btn-sm"><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>               
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
    name : 'DueForPayroll.due-for-payroll',
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
            queue : [],
            bioData : [],
            fetchLoader : true,
            otDirectionType : '',
            activeQueue : {}
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
        getQueue() {
            axios.get(`${ this.baseURL }/overtimes/get-due-for-payroll-data`)
            .then(response => {
                this.queue = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting overtime queue!\n' + error.response.data
                })
            })
        },
        fetchFromBio(direction, otData) {
            this.otDirectionType = direction
            this.activeQueue = otData
            if (direction === 'Start') {
                this.getBiometricData(otData.DateOfOT, otData.BiometricsUserId)
            } else {
                this.getBiometricData(otData.DateOTEnded, otData.BiometricsUserId)
            }
            
            let modalElement = this.$refs.modalFetchFromBio
            $(modalElement).modal('show')
        },
        getBiometricData(date, bioId) {
            this.fetchLoader = true
            axios.get(`${ this.baseURL }/attendance_datas/fetch-by-employee-and-date`, {
                params : {
                    EmployeeBiometricsId : bioId,
                    Date : date,
                }
            })
            .then(response => {
                this.bioData = response.data
                this.fetchLoader = false
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting biometrics data!\n' + error.response.data
                })
                this.fetchLoader = false
            })
        },
        insertBiometricTime(time) {
            if (this.otDirectionType === 'Start') {
                if (!this.isNull(this.activeQueue)) {
                    this.activeQueue.From = moment(time).format("HH:mm")
                    const tHours = this.computeTotalHours()
                    this.activeQueue.TotalHours = tHours
                    this.queue = this.queue.map(obj => obj.id === this.activeQueue.id ? { ...obj, From : this.activeQueue.From, TotalHours : tHours, OTAmount : this.computeOvertime(this.activeQueue) } : obj )
                }
            } else {
                if (!this.isNull(this.activeQueue)) {
                    this.activeQueue.To = moment(time).format("HH:mm")
                    const tHours = this.computeTotalHours()
                    this.activeQueue.TotalHours = tHours
                    this.queue = this.queue.map(obj => obj.id === this.activeQueue.id ? { ...obj, To : this.activeQueue.To, TotalHours : tHours, OTAmount : this.computeOvertime(this.activeQueue) } : obj )
                }
            }

            let modalElement = this.$refs.modalFetchFromBio
            $(modalElement).modal('hide')

            this.otDirectionType = ''
            this.activeQueue = {}
        },
        computeTotalHours() {
            try {
                if (!this.isNull(this.activeQueue)) {
                    var start = moment(this.activeQueue.DateOfOT + " " + this.activeQueue.From)
                    var end = moment(this.activeQueue.DateOTEnded + " " + this.activeQueue.To)

                    var mins = end.diff(start, 'minutes')
                
                    var totalHrs = Math.round(((mins / 60) + Number.EPSILON) * 100, 2) / 100 // returns hours

                    var maxHrs = parseFloat(this.activeQueue.MaxHourThreshold)

                    if (totalHrs <= maxHrs && totalHrs >= 0) {
                        return totalHrs
                    } else if (totalHrs > maxHrs && totalHrs >= 0) {
                        return maxHrs
                    } else {
                        return 0
                    }
                } else {
                    return 0
                }
            } catch (e) {
                console.log(e)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error computing total hours'
                })
                return 0
            }
        },
        addTimeManually(direction, otData) {
            this.otDirectionType = direction
            this.activeQueue = otData
            this.activeQueue.TotalHours = this.computeTotalHours()
            
            this.queue = this.queue.map(obj => obj.id === this.activeQueue.id ? { ...obj, TotalHours : this.activeQueue.TotalHours, OTAmount : this.computeOvertime(this.activeQueue) } : obj )
        },
        updateAll(otData) {
            this.activeQueue = otData
            this.activeQueue.TotalHours = this.computeTotalHours()
            
            this.queue = this.queue.map(obj => obj.id === this.activeQueue.id ? { ...obj, TotalHours : this.activeQueue.TotalHours, OTAmount : this.computeOvertime(this.activeQueue) } : obj )
        },
        updateTotalHours(otData) {
            this.activeQueue = otData

            var maxHrs = parseFloat(this.activeQueue.MaxHourThreshold)
            
            if (this.activeQueue.TotalHours > maxHrs) {
                this.activeQueue.TotalHours = this.activeQueue.MaxHourThreshold
                this.queue = this.queue.map(obj => obj.id === this.activeQueue.id ? { ...obj, TotalHours : this.activeQueue.MaxHourThreshold, OTAmount : this.computeOvertime(this.activeQueue) } : obj )
                this.toast.fire({
                    icon : 'warning',
                    text : 'Total hours should not be greater than max hours'
                })
            } else {
                this.queue = this.queue.map(obj => obj.id === this.activeQueue.id ? { ...obj, OTAmount : this.computeOvertime(this.activeQueue) } : obj )
            }
        },
        getSalaryPerHour(baseSalary) {
            baseSalary = parseFloat(baseSalary)

            return this.round(((baseSalary * 12) / 302) / 8)
        },
        getSalaryPerDay(baseSalary) {
            baseSalary = parseFloat(baseSalary)

            return this.round((baseSalary * 12) / 302)
        },
        computeOvertime(overtimes) {
            var totalAmount = 0
            const baseSalary = overtimes.SalaryAmount
            const salaryPerDay = this.getSalaryPerDay(baseSalary)
            const salaryPerHour = this.getSalaryPerHour(baseSalary)

            const hours = parseFloat(overtimes.TotalHours)

            if (overtimes.TypeOfDay === 'Rest Day') {
                totalAmount += salaryPerHour * 1.3 * hours
            } else if (overtimes.TypeOfDay === 'Regular Holiday') {
                if (hours >= 8) {
                    // get excess of 8 hours
                    var excess8 = hours - 8

                    // calculate first 8
                    const first8 = salaryPerDay * 2
                    // calculate excess of 8
                    const excess8Amount = salaryPerHour * 2 * 1.3 * excess8

                    totalAmount += (first8 + excess8Amount)
                } else {
                    // compute hourly if duty is less than 8 hours
                    totalAmount += salaryPerHour * 2 * hours
                }
            } else if (overtimes.TypeOfDay === 'Regular Holiday + Rest Day') {
                if (hours >= 8) {
                    // get excess of 8 hours
                    var excess8 = hours - 8

                    // calculate first 8
                    const first8 = salaryPerDay * 2 * 1.3
                    // calculate excess of 8
                    const excess8Amount = salaryPerHour * 2 * 1.3 * 1.3 * excess8

                    totalAmount += (first8 + excess8Amount)
                } else {
                    // compute hourly if duty is less than 8 hours
                    totalAmount += salaryPerHour * 2 * 1.3 * hours
                }
            } else if (overtimes.TypeOfDay === 'Special Non-Working Holiday') {
                if (hours >= 8) {
                    // get excess of 8 hours
                    var excess8 = hours - 8

                    // calculate first 8
                    const first8 = salaryPerDay * 1.3
                    // calculate excess of 8
                    const excess8Amount = salaryPerHour * 1.3 * 1.3 * excess8

                    totalAmount += (first8 + excess8Amount)
                } else {
                    // compute hourly if duty is less than 8 hours
                    totalAmount += salaryPerHour * 1.3 * hours
                }
            } else if (overtimes.TypeOfDay === 'Special Non-Working Holiday + Rest Day') {
                if (hours >= 8) {
                    // get excess of 8 hours
                    var excess8 = hours - 8

                    // calculate first 8
                    const first8 = (salaryPerDay * .5) + (salaryPerDay * 1.3)
                    // calculate excess of 8
                    const excess8Amount = salaryPerHour * 1.5 * 1.3 * excess8

                    totalAmount += (first8 + excess8Amount)
                } else {
                    // compute hourly if duty is less than 8 hours
                    totalAmount += (salaryPerHour * .5 * hours) + (salaryPerHour * 1.3)
                }
            } else {
                totalAmount += 0    
            }

            return totalAmount
        },
        saveForPayroll(otData) {
            Swal.fire({
                title: 'Save for Payroll?',
                text : `By confirming, this overtime data will be credited to the employee's next payroll. Proceed with caution.`,
                showDenyButton: true,
                confirmButtonText: 'Confirm Save',
                denyButtonText: `Cancel`,
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/overtimes/save-approve-by-finance`, {
                        id : otData.id,
                        Notes : otData.Notes,
                        TypeOfDay : otData.TypeOfDay,
                        TotalHours : otData.TotalHours,
                        MaxHourThreshold : otData.MaxHourThreshold,
                        OTAmount : otData.OTAmount,
                        From : otData.From,
                        To : otData.To,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Overtime data approved and forwarded for payroll!'
                        })
                        this.queue = this.queue.filter(obj => obj.id !== otData.id)
                    })
                    .catch(error => {
                        console.log(error.response)
                        Swal.fire({
                            text : 'Error approving overtime data!',
                            icon : 'error'
                        })
                    })
                } else if (result.isDenied) {
                    
                }
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getQueue()
    }
}

</script>