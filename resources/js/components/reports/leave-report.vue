<template>
    <div class="row">
        <div class="col-lg-2">
            <span class="text-muted">From</span>
            <flat-pickr v-model="from" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
        </div>
        <div class="col-lg-2">
            <span class="text-muted">To</span>
            <flat-pickr v-model="to" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
        </div>  
        <div class="col-md-2">
            <span class="text-muted">Leave Type</span>
            <select v-model="type" @change="view" class="form-control form-control-sm">
                <option value="All">All Leave</option>
                <option value="Vacation">Vacation</option>
                <option value="Sick">Sick</option>
                <option value="Special">Special</option>
                <option value="Paternity">Paternity</option>
                <option value="Maternity">Maternity</option>
                <option value="MaternityForSoloMother">Maternity For Solo Mother</option>
                <option value="SoloParent">Solo Parent</option>
            </select>
        </div>
        <div class="col-lg-4">
            <span class="text-muted">Actions</span>
            <br>
            <button @click="getLeaveReport" type="submit" class="btn btn-default btn-sm"><i class="fas fa-eye ico-tab-mini"></i>View</button>
            <button @click="printLeaveReport" type="submit" class="btn btn-warning btn-sm ml-1"><i class="fas fa-print ico-tab-mini"></i>Print</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                    <thead>
                        <th></th>
                        <th>Employee</th>
                        <th>Date Filed</th>
                        <th>Leave Days</th>
                        <th>Total Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <!-- <th></th> -->
                    </thead>
                    <tbody>
                        <tr v-for="leave in results.data" :key="leave.id" style="cursor: pointer;">
                            <td @click="leaveView(leave.id)" class="v-align" :title="leave.LeaveType"><i class="ico-tab-mini fas" :class="getIconType(leave.LeaveType)"></i> {{ leave.LeaveType }}</td>
                            <td class="v-align">
                                <a target="_blank" :href="baseURL + '/employees/' + leave.EmployeeId">{{ leave.LastName + ', ' + leave.FirstName }}</a>
                            </td>
                            <td class="v-align">{{ moment(leave.created_at).format("MMM DD, YYYY") }}</td>
                            <td class="v-align" v-html="getDaysConcat(leave.Days)"></td>
                            <td class="v-align">{{ validateTotalCredits(leave.LeaveType, leave.TotalCredits) }} days</td>
                            <td class="v-align">{{leave.Content }}</td>
                            <td class="v-align text-center"><span class="badge" :class="getStatusBadgeColor(leave.Status)">{{leave.Status }}</span></td>
                            <!-- <td class="v-align text-right">
                                <button @click="trashLeave(leave.id)" class="btn btn-xs btn-danger"><i class="fas fa-trash ico-tab-mini"></i>Delete</button>
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>

            <pagination :data="results" :limit="16" @pagination-change-page="view"></pagination>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import jquery from 'jquery';
import moment from 'moment';
import Swal from 'sweetalert2';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
    name : 'LeaveReport.leave-report',
    components : {
        Swal,
        'pagination' : Bootstrap4Pagination,
        FlatPickr,
    },
    data() {
        return {
            moment : moment,
            isEditMode : false,
            baseURL : axios.defaults.baseURL,
            imgsPath : axios.defaults.imgsPath,
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
            results : {},
            type : 'All',
            from : '',
            to : ''
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
        getLeaveReport (page = 1) {
            axios.get(`${ this.baseURL }/leave_applications/get-leave-report`, {
                params : {
                    page : page,
                    Type : this.type,
                    From : this.from,
                    To : this.to,
                }  
            }) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
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
        getDaysConcat(daysArray) {
            var days = "<ul>"
            for (let i=0; i<daysArray.length; i++) {
                days += `<li>` + moment(daysArray[i].LeaveDate).format("MMM DD, YYYY") + ` (${ daysArray[i].Duration })</li>`
            }
            days += "</ul>"

            return days
        },
        leaveView(id) {
            window.location.href = `${ this.baseURL }/leaveApplications/` + id
        },
        validateTotalCredits(type, credits) {
            if (type === 'Sick' | type === 'Vacation') {
                return credits / 8 / 60
            } else {
                return credits * 1
            }
        },
        trashLeave(id) {
            Swal.fire({
                title: 'Deletion Confirmation',
                text : 'You sure you wanna delete this leave?',
                showDenyButton: true,
                confirmButtonText: 'Delete',
                denyButtonText: `Close`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.get(`${ axios.defaults.baseURL }/leave_applications/delete-leave`, {
                        params : {
                            id : id,
                        }  
                    }) // IF PORT 80 DIRECT FROM APACHE
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Leave removed!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        Swal.fire({
                            text : 'Error deleting leave! Contact IT support for more.',
                            icon : 'error'
                        })
                    })
                } else if (result.isDenied) {
                    
                }
            })
        },
        printLeaveReport() {
            if (this.isNull(this.from) | this.isNull(this.to)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please fill in dates of report'
                })
            } else {
                window.location.href = `${ this.baseURL }/leave_applications/print-leave-report/${ this.from }/${ this.to }/${ this.type }`
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getLeaveReport()
    }
}

</script>