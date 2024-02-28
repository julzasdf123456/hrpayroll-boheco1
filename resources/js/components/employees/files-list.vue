<template>
    <div class="table-responsive" style="height: inherit;">
        <table class="table table-hover table-borderless mt-3" style="margin-bottom: 150px;">
            <thead>
                <th class="text-muted">Files <i class="fas fa-sort-amount-down-alt ico-tab-left-mini"></i></th>
                <th class="text-muted">Date Modified</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="file in files" :key="file.file">
                    <td style="cursor: pointer;" @click="goToFile(filePath + '' + employeeId + '/' + file.file)"><i class="fas fa-file ico-tab"></i>{{ file.file }}</td>
                    <td class="text-muted">{{ file.dateModified }}</td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-link-muted float-right" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                              <i class="fas fa-ellipsis-v"></i>
                            </a>
                          
                            <div class="dropdown-menu">
                                <a target="_blank" class="dropdown-item" :href="filePath + '' + employeeId + '/' + file.file"><i class="fas fa-external-link-alt ico-tab-mini"></i>View</a>
                                <button @click="rename(file.file)" class="dropdown-item"><i class="fas fa-i-cursor ico-tab-mini"></i>Rename</button>
                                <button @click="trash(file.file)" class="dropdown-item"><i class="fas fa-trash ico-tab-mini text-danger"></i>Move to Trash</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
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
    name : 'FilesList.files-list',
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
            employeeId : document.querySelector("meta[name='view-employee-id']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            files : {},
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
        getFiles() {
            this.files = {}
            axios.get(`${ axios.defaults.baseURL }/employees/fetch-files`, {
                params : {
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.files = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting attached files!\n' + error.response.data
                })
            })
        },
        goToFile(file) {
            window.open(file, '_blank');
        },
        rename(oldName) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'text',
                    inputPlaceholder: 'New Name',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Rename File',
                    text : 'Rename ' + oldName,
                    showCancelButton: true
                })

                if (text) {
                    if (this.isNull(text)) {
                        this.toast.fire({
                            icon : 'info',
                            text : 'Please provide name to rename!',
                        })
                    } else { 
                        try {
                            axios.get(`${ axios.defaults.baseURL }/employees/rename-file`, {
                                params : {
                                    EmployeeId : this.employeeId,
                                    OldFileName : oldName,
                                    NewFileName : text,
                                }
                            })
                            .then(response => {
                                this.toast.fire({
                                    icon : 'success',
                                    text : 'File renamed!'
                                })
                                this.getFiles()
                            })
                            .catch(error => {
                                console.log(error)
                                this.toast.fire({
                                    icon : 'error',
                                    text : error.response.data
                                })
                            })
                        } catch (err) {
                            console.log(err)
                            Swal.fire({
                                icon : 'info',
                                title : 'Oops!',
                                text : err.message,
                            })
                        }
                    }
                }
            })()
        },
        trash(file) {
            Swal.fire({
                icon : 'warning',
                title: "Move file to trash?",
                text : 'Administrators can always restore this anytime.',
                showCancelButton: true,
                confirmButtonText: "Move to Trash",
                confirmButtonColor: '#e03822',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ axios.defaults.baseURL }/employees/trash-file`, {
                        EmployeeId : this.employeeId,
                        CurrentFile : file,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'info',
                            text : 'File moved to trash!'
                        })
                        this.getFiles()
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'error',
                            text : error.response.data
                        })
                    })
                }
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getFiles()
    }
}

</script>