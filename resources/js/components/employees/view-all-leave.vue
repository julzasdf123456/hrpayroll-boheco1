<template>
    <form class="row" @submit.prevent="view">
        <div class="col-md-5 offset-md-2 input-group">
            <input v-model="search" @keyup="view" type="text" class="form-control" placeholder="Search name, employee ID, etc..." name="params" autofocus>
        </div>   
        <div class="col-md-3" style="display: flex; flex-direction: row; column-gap: 5px;">
            <select v-model="type" @change="view" class="form-control" style="width: 230px;">
                <option value="All">All Leave</option>
                <option value="Vacation">Vacation</option>
                <option value="Sick">Sick</option>
                <option value="Special">Special</option>
                <option value="Paternity">Paternity</option>
                <option value="Maternity">Maternity</option>
                <option value="MaternityForSoloMother">Maternity For Solo Mother</option>
                <option value="SoloParent">Solo Parent</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
    </form>

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
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="leave in results.data" :key="leave.id" style="cursor: pointer;">
                            <td @click="leaveView(leave.id)" class="v-align" :title="leave.LeaveType"><i class="ico-tab-mini fas" :class="getIconType(leave.LeaveType)"></i> {{ leave.LeaveType }}</td>
                            <td class="v-align">
                                <a target="_blank" :href="baseURL + '/employees/' + leave.EmployeeId">{{ leave.LastName + ', ' + leave.FirstName }}</a>
                            </td>
                            <td @click="leaveView(leave.id)" class="v-align">{{ moment(leave.created_at).format("MMM DD, YYY") }}</td>
                            <td @click="leaveView(leave.id)" class="v-align" v-html="getDaysConcat(leave.Days)"></td>
                            <td @click="leaveView(leave.id)" class="v-align">{{ validateTotalCredits(leave.LeaveType, leave.TotalCredits) }} days</td>
                            <td @click="leaveView(leave.id)" class="v-align">{{leave.Content }}</td>
                            <td @click="leaveView(leave.id)" class="v-align text-center"><span class="badge" :class="getStatusBadgeColor(leave.Status)">{{leave.Status }}</span></td>
                            <td class="v-align text-right">
                                <button @click="trashLeave(leave.id)" class="btn btn-xs btn-danger"><i class="fas fa-trash ico-tab-mini"></i>Delete</button>
                            </td>
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
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            results : {},
            type : 'All'
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
            axios.get(`${ this.baseURL }/leave_applications/search-leave`, {
                params : {
                    page : page,
                    search : this.search,
                    type : this.type,
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