<template>
    <div class="row mt-2 px-3">
        <div class="col-lg-12">
            <a :href="baseURL + '/test/it-exam/'"><i class="fas fa-arrow-left"></i> Go Back to Search</a>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-soft">
                <div class="card-body">
                    <p class="text-muted">Consumer Information</p>
                    <h4>{{ accountInfo.ConsumerName }}</h4>
                    <p class="no-pads">{{ accountInfo.AccountNumber + ' | ' + accountInfo.ConsumerAddress }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Billing Data</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <thead>
                            <th>Billing Month</th>
                            <th>Amount Due</th>
                            <th>Surcharges</th>
                            <th>Total Amount Due</th>
                            <th>Due Date</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="bill in bills">
                                <td>{{ moment(bill.BillingMonth).format("MMMM YYYY") }}</td>
                                <td class="text-right">{{ toMoney(bill.AmountDue) }}</td>
                                <td class="text-right">{{ toMoney(bill.Surcharges) }}</td>
                                <td class="text-right">{{ toMoney(bill.TotalAmountDue) }}</td>
                                <td>{{ moment(bill.DueDate).format("MMM DD, YYYY") }}</td>
                                <td class="text-right">
                                    <button v-if="isNull(bill.AmountPaid) ? true : false" class="btn btn-warning btn-sm" @click="payNow(bill)">Pay Bills</button>
                                    <span v-if="isNull(bill.AmountPaid) ? false : true" class="badge bg-success">Paid</span>
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
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            accountNo : document.querySelector("meta[name='accountNo']").getAttribute('content'),
            token : 'RNwWffwXIIRVUqyDYOdH7JrLeMpuhM5azJ8tSq5BmwoJhJvBtYQou0l0ZCCpHY8fod9GJ4E8',
            accountInfo : {},
            bills :  [],
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
        getAccount(page = 1) {
            axios.get(`http://192.168.10.200/boheco1dev/public/api/fetch-accounts-search-paginate`, {
                params : {
                    page : page,
                    _token : this.token,
                    pageLimit : 1,
                    q : this.accountNo,
                }
            })
            .then(response => {
                this.accountInfo = response.data.data[0]
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting account!'
                })
            })
        },
        getBills() {
            axios.get(`http://192.168.10.200/boheco1dev/public/api/get-all-bills-from-account`, {
                params : {
                    _token : this.token,
                    accountNumber : this.accountNo,
                }
            })
            .then(response => {
                this.bills = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting bills!'
                })
            })
        },
        payNow(billData) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'number',
                    text : 'Input amount for payment',
                    inputPlaceholder: 'Input Amount',
                    title: 'Input Amount',
                    showCancelButton: true,
                    confirmButtonText : 'Confirm Payment'
                })

                if (text) {
                    axios.post(`http://192.168.10.200/boheco1dev/public/api/transact`, {
                        _token : this.token,
                        acct_no : this.accountNo,
                        period : billData.BillingMonth,
                        amount : text,
                        teller : 'Juls',
                        company : 'Hashed.it',
                        ornumber : this.generateUniqueId()
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Payment successful!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error performing transaction!!\n' + error.response.data
                        })
                    })
                }
            })()
        },
    },
    created() {
        
    },
    mounted() {
        this.getAccount()
        this.getBills()
    }
}

</script>