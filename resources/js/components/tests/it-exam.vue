<template>
    <div class="row">
        <div class="col-lg-8 offset-lg-2 mt-3">
            <!-- search -->
            <div class="card shadow-soft">
                <div class="card-body">
                    <p class="text-muted">Search Account</p>

                    <input type="text" placeholder="Account number or account name..." v-model="search" class="form-control" autofocus @keyup.enter="getSearch()">
                </div>
            </div>

            <!-- resutls -->
            <div class="mt-4 table-responsive">
                <p class="text-muted text-sm"><i>Search Results</i></p>
                <table class="table table-hover">
                    <thead>
                        <th>Account Number</th>
                        <th>Consumer Name</th>
                        <th>Account Address</th>
                        <th>Meter Number</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="res in results.data">
                            <td>{{ res.AccountNumber }}</td>
                            <td>{{ res.ConsumerName }}</td>
                            <td>{{ res.ConsumerAddress }}</td>
                            <td>{{ res.MeterNumber }}</td>
                            <td class="text-right">
                                <a :href="baseURL + '/test/account-view/' + res.AccountNumber" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                
                <pagination :data="results" :limit="10" @pagination-change-page="getSearch"></pagination>
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
            search : '',
            token : 'RNwWffwXIIRVUqyDYOdH7JrLeMpuhM5azJ8tSq5BmwoJhJvBtYQou0l0ZCCpHY8fod9GJ4E8',
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
            axios.get(`http://192.168.10.200/boheco1dev/public/api/fetch-accounts-search-paginate`, {
                params : {
                    page : page,
                    _token : this.token,
                    pageLimit : 10,
                    q : this.search,
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error searching accounts!'
                })
            })
        }
    },
    created() {
        
    },
    mounted() {
       
    }
}

</script>