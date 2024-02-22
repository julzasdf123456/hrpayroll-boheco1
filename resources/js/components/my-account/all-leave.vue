<template>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <span class="text-muted">Filter Leave</span>
        <select v-model="leaveType" class="form-control" @change="getLeaveData">
            <option value="All">All</option>
            <option value="Vacation">Vacation</option>
            <option value="Sick">Sick</option>
            <option value="Special">Special</option>
            <option value="Paternity">Paternity</option>
            <option value="Maternity">Maternity</option>
            <option value="MaternityForSoloMother">Maternity for Solo Mom</option>
            <option value="SoloParent">Solo Parent</option>
        </select>
    </div>

    <div class="col-lg-12 mt-4">
        <table class="table table-md table-hover">
            <thead>
                <th style="width: 32px;"></th>
                <th>Date Filed</th>
                <th>Reason</th>
                <th class="text-right"># of Days</th>
                <th class="text-center">Status</th>
            </thead>
            <tbody>
                <tr v-for="leave in leaveData.data" :key="leave.id" @click="leaveView(leave.id)" style="cursor: pointer;">
                    <td :title="leave.LeaveType"><i class="fas" :class="getIconType(leave.LeaveType)"></i></td>
                    <td>{{ moment(leave.created_at).format("MMMM DD, YYY") }}</td>
                    <td>{{leave.Content }}</td>
                    <td class="text-right">{{leave.TotalDays }}</td>
                    <td class="text-center"><span class="badge" :class="getStatusBadgeColor(leave.Status)">{{leave.Status }}</span></td>
                </tr>
            </tbody>
        </table>

        <pagination :data="leaveData" :limit="10" @pagination-change-page="getLeaveData"></pagination>
    </div>
</div>
</template>

<style>
    .table-md {
        font-size: 1.1em !important;
    }
</style>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'AllLeave.all-leave',
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
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            search : '',
            leaveData : {},
            leaveType : 'All',
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
        leaveView(id) {
            window.location.href = `${ axios.defaults.baseURL }/my_account/view-leave/` + id
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
        getLeaveData(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/leave_balances/get-leave-data`, {
                params : {
                    page : page,
                    LeaveType : this.leaveType,
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.leaveData = response.data
                console.log(this.leaveData)
            })
            .catch(error => {
                console.log(error)
            })
        },
    },
    created() {
        
    },
    mounted() {
        this.getLeaveData()
    }
}

</script>