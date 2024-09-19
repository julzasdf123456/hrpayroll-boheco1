<template>
    <div class="row">
        <form class="col-md-6 offset-md-3" @submit.prevent="view">
            <div class="input-group">
                <input v-model="search" @keyup="view" type="text" class="form-control" placeholder="Search name, employee ID, etc..." name="params" autofocus>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>   
        </form>
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
                        <th>Reason</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <tr v-for="leave in results.data" :key="leave.id" @click="leaveView(leave.id)" style="cursor: pointer;">
                            <td :title="leave.LeaveType"><i class="ico-tab-mini fas" :class="getIconType(leave.LeaveType)"></i> {{ leave.LeaveType }}</td>
                            <td class="v-align">{{ leave.FirstName + ', ' + leave.LastName }}</td>
                            <td class="v-align">{{ moment(leave.created_at).format("MMM DD, YYY") }}</td>
                            <td class="v-align" v-html="getDaysConcat(leave.Days)"></td>
                            <td class="v-align">{{leave.Content }}</td>
                            <td class="v-align text-center"><span class="badge" :class="getStatusBadgeColor(leave.Status)">{{leave.Status }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            
            <pagination :data="results" :limit="30" @pagination-change-page="view"></pagination>
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
        view (page = 1) {
            axios.get(`${ axios.defaults.baseURL }/leave_applications/search-leave`, {
                params : {
                    page : page,
                    search : this.search,
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
            window.location.href = `${ axios.defaults.baseURL }/leaveApplications/` + id
        },
    },
    created() {
        
    },
    mounted() {
        this.view()
    }
}

</script>