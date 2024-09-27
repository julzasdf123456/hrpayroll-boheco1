<template>
    <div class="row mb-2">
        <form class="col-lg-6 offset-lg-3 col-md-8 offset-md-2" @submit.prevent="view()">
            <div class="input-group">
                <input v-model="search" @keyup="view()" type="text" class="form-control" placeholder="Search Memo ID or Title" name="params" autofocus>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>   
        </form>

        <!-- results -->
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
            <div v-for="res in results.data" class="card shadow-none mt-2">
                <div class="card-body">
                    <span class="text-muted text-sm">Memo No. {{ res.MemoNumber }}</span>
                    <h4><strong>{{ res.MemoTitle }}</strong></h4>

                    <br>

                    <p v-html="res.MemoContent"></p>

                    <p class="text-sm text-muted">{{ moment(res.created_at).format('ddd, MMM DD, YYYY, hh:mm A') }}</p>

                    <button @click="showMemo(res)" class="btn btn-link-muted btn-sm" title="Memo respondents"><i class="fas fa-users"></i> ({{ res.Count }})</button>

                    <button @click="trashMemo(res.id)" class="btn btn-link-muted btn-sm float-right" title="Delete memo"><i class="fas fa-trash"></i></button>
                    <button class="btn btn-link-muted btn-sm float-right" title="Edit Memo"><i class="fas fa-pen"></i></button>
                    <button @click="printMemo(res.id)" class="btn btn-link-muted btn-sm float-right" title="Print memo"><i class="fas fa-print"></i></button>
                </div>
            </div>
        
            <pagination :data="results" :limit="16" @pagination-change-page="view()"></pagination>
        </div>
    </div>

    <!-- show modal details -->
    <div ref="modalShowMemo" class="modal fade" id="modal-show-memo" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content px-4 py-3">
                <div class="modal-body">
                    <span class="text-muted text-sm">Memo No. {{ activeMemo.MemoNumber }}</span>
                    <h4>{{ activeMemo.MemoTitle }}</h4>

                    <br>

                    <p v-html="activeMemo.MemoContent"></p>

                    <p class="text-sm text-muted">{{ moment(activeMemo.created_at).format('ddd, MMM DD, YYYY, hh:mm A') }}</p>

                    <div class="divider my-3"></div>

                    <div class="table-responsive">
                        <p class="text-muted">Respondents:</p>
                        <table class="table table-sm table-hover table-borderless">
                            <tbody>
                                <tr v-for="emp in respondents">
                                    <td>• {{ emp.LastName + ', ' + emp.FirstName }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
    name : 'EmployeesSearch.search',
    components : {
        'pagination' : Bootstrap4Pagination,
        Swal,
    },
    data() {
        return {
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            moment : moment,
            search : '',
            isEditMode : false,
            employees : {},
            baseURL : axios.defaults.baseURL,
            imgsPath : axios.defaults.imgsPath,
            results : {},
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            activeMemo : {},
            respondents : [],
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
            axios.get(`${ this.baseURL }/memorandums/search-memo`, {
                params : {
                    page : page,
                    params : this.search
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        printMemo(id) {
            Swal.fire({
                title: 'Print Option',
                showDenyButton: true,
                confirmButtonText: 'Print Without Employee Names',
                denyButtonText: `Print With Employee Names`,
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${ this.baseURL }/memorandums/print-memo/${ id }/with-no-employee`
                } else if (result.isDenied) {
                    window.location.href = `${ this.baseURL }/memorandums/print-memo/${ id }/with-employee`
                }
            })
        },
        trashMemo(id) {
            Swal.fire({
                title: 'Delete this Memo?',
                text : 'You can always re-add this.',
                confirmButtonText: 'Delete',
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/memorandums/trash-memo`, {
                        _token : this.token,
                        id : id,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Memo deleted!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error deleting memo!'
                        })
                    })
                }
            })
        },
        showMemo(memoData) {
            this.activeMemo = memoData

            // get employees in memo
            axios.get(`${ this.baseURL }/memorandums/get-memo-respondents`, {
                params : {
                    id : this.activeMemo.id
                }
            })
            .then(response => {
                this.respondents = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting memo respondents!'
                })
            })

            let modalElement = this.$refs.modalShowMemo
            $(modalElement).modal('show')
        }
    },
    created() {
        
    },
    mounted() {
        this.view()
    }
}

</script>