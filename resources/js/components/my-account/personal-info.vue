<template>
    <!-- personal info -->
    <div class="section">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Your employement info in BOHECO I</p>
                    <p class="no-pads text-muted">Information you declared about yourself based on your contract to BOHECO I. 
                        Do note that these information are only visible to you and to the company administrators.</p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 100% !important;" class="img-fluid" src="../../../../public/imgs/personal-info.png" alt="User profile picture">
            </div>
        </div>

        <!-- Basic Info -->
        <div class="card shadow-none mt-4">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">Basic Info</p>
                <span class="text-muted">Your personal information</span>

                <table class="table table-hover mt-4">
                    <tbody>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Profile Picture</td>
                            <td class="text-right"><img style="width: 50px; !important;" class="img-fluid img-circle" src="../../../../public/imgs/prof-img.png" alt="User profile picture"></td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Declared Name</td>
                            <td class="v-align">{{ employeeData.LastName + ', ' + employeeData.FirstName + (isNull(employeeData.Suffix) ? '' : employeeData.Suffix + ' ') + (isNull(employeeData.MiddleName) ? '' : employeeData.MiddleName) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Gender</td>
                            <td class="v-align">{{ isNull(employeeData.Gender) ? '-' : employeeData.Gender }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Birthday</td>
                            <td class="v-align">{{ isNull(employeeData.Birthdate) ? '-' : moment(employeeData.Birthdate).format("MMMM DD, YYYY") }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Civil Status</td>
                            <td class="v-align">{{ isNull(employeeData.CivilStatus) ? '-' : employeeData.CivilStatus }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Citizenship</td>
                            <td class="v-align">{{ isNull(employeeData.Citizenship) ? '-' : employeeData.Citizenship }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Blood type</td>
                            <td class="v-align">{{ isNull(employeeData.BloodType) ? '-' : employeeData.BloodType }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card shadow-none mt-2">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">Contact & Address Info</p>
                <span class="text-muted">This is only visible to you and to your supers for privacy purposes</span>

                <table class="table table-hover mt-4">
                    <tbody>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Contact Numbers</td>
                            <td class="v-align">{{ isNull(employeeData.ContactNumbers) ? '-' : employeeData.ContactNumbers }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Email Address</td>
                            <td class="v-align">{{ isNull(employeeData.EmailAddress) ? '-' : employeeData.EmailAddress }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Current Address</td>
                            <td class="v-align">{{ (isNull(employeeData.StreetCurrent) ? '' : employeeData.StreetCurrent + ', ') + (isNull(employeeData.CurrentBarangay) ? '' : employeeData.CurrentBarangay + ', ') + (isNull(employeeData.CurrentTown) ? '' : employeeData.CurrentTown) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cards and Numbers Info -->
        <div class="card shadow-none mt-2">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">ID and Bank Numbers</p>
                <span class="text-muted">This is only visible to you and to your supers for privacy purposes</span>

                <table class="table table-hover mt-4">
                    <tbody>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">TIN</td>
                            <td class="v-align">{{ isNull(employeeData.TIN) ? '-' : employeeData.TIN }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Social Security System</td>
                            <td class="v-align">{{ isNull(employeeData.SSSNumber) ? '-' : employeeData.SSSNumber }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">PhilHealth</td>
                            <td class="v-align">{{ isNull(employeeData.PhilHealthNumber) ? '-' : employeeData.PhilHealthNumber }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Pag-Ibig</td>
                            <td class="v-align">{{ isNull(employeeData.PagIbigNumber) ? '-' : employeeData.PagIbigNumber }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Pitakard Number</td>
                            <td class="v-align">{{ isNull(employeeData.PrimaryBankNumber) ? '-' : employeeData.PrimaryBankNumber }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- files -->
    <div class="section mt-3">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Your BOHECO I files</p>
                    <p class="no-pads text-muted">Files you submitted to BOHECO I. These could be your requirements, certifications, medical, or any random files your submitted to the firm.</p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 100% !important;" class="img-fluid" src="../../../../public/imgs/files.png" alt="User profile picture">
            </div>
        </div>

        <!-- Files Repository -->
        <div class="card shadow-none mt-3">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">Files Repository</p>
                <span class="text-muted">Only you and your supers can view these files. If you wish to upload more of your certifications, submit them to the HR office.</span>

                <table class="table table-hover mt-4">
                    <tbody>
                        <tr v-for="file in files" :key="file.file">
                            <td class="v-align" style="cursor: pointer;" @click="goToFile(filePath + '' + employeeId + '/' + file.file)"><i class="fas fa-file ico-tab"></i>{{ file.file }}</td>
                            <td class="text-muted v-align">{{ file.dateModified }}</td>
                            <td class="text-right v-align">
                                <div class="dropdown">
                                    <a class="btn btn-link-muted float-right" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                                      <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                  
                                    <div class="dropdown-menu">
                                        <a target="_blank" class="dropdown-item" :href="filePath + '' + employeeId + '/' + file.file"><i class="fas fa-external-link-alt ico-tab-mini"></i>View</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    name : 'PersonalInfo.personal-info',
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
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            files : {},
            employeeData : ''
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
        getEmployeeInfo() {
            this.employeeData = null
            axios.get(`${ axios.defaults.baseURL }/employees/get-employee-full-ajax`, {
                params : {
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.employeeData = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employee data!\n' + error.response.data
                })
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getEmployeeInfo()
        this.getFiles()
    }
}

</script>