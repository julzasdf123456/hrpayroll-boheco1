<template>
<div class="row mt-3">
    <!-- ATTACHED ACCOUNTS -->
    <div class="col-lg-7 col-md-12">
        <div class="card shadow-none">
            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        <p class="text-md no-pads">Attached BOHECO I Accounts</p>
                        <span class="text-muted">You account attachments will be deducted monthly on your payroll</span>
                    </div>
                    <div class="col-2 center-contents">
                        <img style="width: 100% !important;" class="img-fluid" :src=" imgsPath + 'attached-accounts.png'" alt="User profile picture">
                    </div>

                    <div class="col-12 mt-3 table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th>Account Address</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr v-for="account in connectedAccounts" :key="account.id">
                                    <td style="vertical-align: middle;">{{ account.AccountNumber }}</td>
                                    <td style="vertical-align: middle;">{{ account.ConsumerName }}</td>
                                    <td style="vertical-align: middle;">{{ account.ConsumerAddress }}</td>
                                    <td class="text-right">
                                        <button @click="removeAccount(account.id)" class="btn btn-link-muted btn-sm"><i class="fas fa-trash text-danger"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ATTACH FORM -->
    <div class="col-lg-5 col-md-12">
        <div class="card shadow-none">
            <div class="card-body">
                <p class="text-md no-pads">Add account attachments</p>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <span class="text-muted">Search account</span>
                        <input type="text" v-model="search" @keyup="getSearch" placeholder="Account number or name" class="form-control" :autofocus="true"/>
                    </div>

                    <div class="col-lg-12 mt-3 table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr v-for="result in results.data" :key="result.AccountNumber">
                                    <td style="vertical-align: middle;">{{ result.AccountNumber }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ result.ConsumerName }}</strong>
                                        <br>
                                        <span class="text-muted">{{ result.ConsumerAddress }}</span>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle;">
                                        <button @click="attachAccount(result.AccountNumber, result.ConsumerName, result.ConsumerAddress)" class="btn btn-primary-skinny">Attach</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <pagination :data="results" :limit="10" @pagination-change-page="getSearch"></pagination>
                    </div>
                </div>
            </div>
        </div>
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
            results : {},
            search : '',
            connectedAccounts : [],
            imgsPath : axios.defaults.imgsPath,
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
        getSearch(page = 1) {
            axios.get(`${ axios.defaults.baseURL }/my_account/search-boheco-accounts`, {
                params : {
                    page : page,
                    Search : this.search,
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error)
                // this.toast.fire({
                //     icon : 'error',
                //     text : 'Error fetching consumers'
                // })
            })
        },
        attachAccount(accountNo, name, address) {
            Swal.fire({
                title: "Attach Account?",
                text : 'The monthly bills for account number ' + accountNo + ' will automatically be deducted from your salary. Procced to continue...',
                showCancelButton: true,
                confirmButtonText: "Submit",
                confirmButtonColor: '#3a9971',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ axios.defaults.baseURL }/attachedAccounts`, {
                            id : this.generateUniqueId(),
                            EmployeeId : this.employeeId,
                            AccountNumber : accountNo,
                            ConsumerName : name,
                            ConsumerAddress : address,
                    })
                    .then(response => {
                        this.getConnectedAccounts()
                        this.toast.fire({
                            icon : 'success',
                            text : 'Account attached!'
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'info',
                            text : error.response.data
                        })
                    })
                }
            })
        },
        getConnectedAccounts() {
            axios.get(`${ axios.defaults.baseURL }/attached_accounts/get-connected-accounts`, {
                params : {
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.connectedAccounts = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting attached accounts!\n' + error.response.data
                })
            })
        },
        removeAccount(id) {
            Swal.fire({
                title: "Remove this Account?",
                text : `This account's bill shall no longer be deducted in your future payrolls. Proceed to confirm.`,
                showCancelButton: true,
                confirmButtonText: "Remove",
                confirmButtonColor: '#e03822',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`${ axios.defaults.baseURL }/attachedAccounts/${id}`)
                    .then(response => {
                        this.getConnectedAccounts()
                        this.toast.fire({
                            icon : 'success',
                            text : 'Account removed!'
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error deleting attached accounts!\n' + error.response.data
                        })
                    })
                }
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getSearch()
        this.getConnectedAccounts()
    }
}

</script>