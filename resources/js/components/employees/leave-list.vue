<template>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Date Filed</th>
                    <th>Leave Days</th>
                    <th>Total Days</th>
                    <th>Reason</th>
                    <th style="max-width: 30px; width: 30px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="leave in results.data" :key="leave.id" style="cursor: pointer;">
                    <td @click="leaveView(leave.id)" class="v-align" :title="leave.LeaveType"><i class="fas" :class="getIconType(leave.LeaveType)"></i></td>
                    <td @click="leaveView(leave.id)" class="v-align">
                        {{ moment(leave.created_at).format("MMM DD, YYYY") }}
                        <br>
                        <span class="badge" :class="getStatusBadgeColor(leave.Status)">{{leave.Status }}</span>
                    </td>
                    <td @click="leaveView(leave.id)" class="v-align" v-html="getDaysConcat(leave.Days)"></td>
                    <td @click="leaveView(leave.id)" class="v-align">{{ validateTotalCredits(leave.LeaveType, leave.TotalCredits) }} days</td>
                    <td @click="leaveView(leave.id)" class="v-align">{{leave.Content }}</td>
                    <td class="v-align text-right">
                        <button @click="trashLeave(leave.id)" class="btn btn-xs btn-link text-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <pagination :data="results" :limit="16" @pagination-change-page="view"></pagination>
</template>


<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import jquery from 'jquery';
import moment from 'moment';
import Swal from 'sweetalert2';

export default {
    name : 'ViewAllLeave.view-all-leave',
    components : {
        Swal,
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            moment : moment,
            search : '',
            isEditMode : false,
            baseURL : axios.defaults.baseURL,
            imgsPath : axios.defaults.imgsPath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            employeeId : document.querySelector("meta[name='view-employee-id']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            results : {},
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
        view (page = 1) {
            axios.get(`${ this.baseURL }/leave_applications/get-leave-by-employee`, {
                params : {
                    EmployeeId : this.employeeId
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
        }
    },
    created() {
        
    },
    mounted() {
        this.view()
    }
}

</script>