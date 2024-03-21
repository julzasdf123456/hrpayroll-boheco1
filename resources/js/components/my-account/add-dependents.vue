<template>
    <div class="section">
        <div class="card shadow-none mt-2">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">Add Dependents</p>
                <span class="text-muted">You can add your wife, children, parents, or any direct family member</span>

                <div class="table-responsive mt-4">
                    <table class="table table-hover table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">Full Name</td>
                                <td class="v-align">
                                    <input type="text" class="form-control" v-model="fullname" placeholder="Enter dependent name">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Address (optional)</td>
                                <td class="v-align">
                                    <input type="text" class="form-control" v-model="address" placeholder="Enter dependent address">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Relationship</td>
                                <td class="v-align">
                                    <select v-model="relationship" class="form-control">
                                        <option value="WIFE">WIFE</option>
                                        <option value="HUSBAND">HUSBAND</option>
                                        <option value="SON">SON</option>
                                        <option value="DAUGHTER">DAUGHTER</option>
                                        <option value="MOTHER">MOTHER</option>
                                        <option value="FATHER">FATHER</option>
                                        <option value="MOTHER IN LAW">MOTHER IN LAW</option>
                                        <option value="FATHER IN LAW">FATHER IN LAW</option>
                                        <option value="GRANDMOTHER">GRANDMOTHER</option>
                                        <option value="GRANDFATHER">GRANDFATHER</option>
                                        <option value="GRANDMOTHER IN LAW">GRANDMOTHER IN LAW</option>
                                        <option value="GRANDFATHER IN LAW">GRANDFATHER IN LAW</option>
                                        <option value="NEPHEW">NEPHEW</option>
                                        <option value="NIECE">NIECE</option>
                                        <option value="BOYFRIEND">BOYFRIEND</option>
                                        <option value="GIRLFRIEND">GIRLFRIEND</option>
                                        <option value="BESTFRIEND">BESTFRIEND</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Birthday</td>
                                <td class="v-align">
                                    <flat-pickr v-model="birthday" :config="pickerOptions" :readonly="false" class="form-control"></flat-pickr>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Occupation (optional)</td>
                                <td class="v-align">
                                    <input type="text" class="form-control" v-model="occupation" placeholder="Enter occupation/profession">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Disability (PWD, Optional)</td>
                                <td class="v-align">
                                    <select v-model="disability" class="form-control">
                                        <option value="">Totally normal</option>
                                        <option value="Autism">Autism</option>
                                        <option value="Blind">Blind</option>
                                        <option value="Deaf/Mute">Deaf/Mute</option>
                                        <option value="Down Syndrome">Down Syndrome</option>
                                        <option value="Handicapped">Handicapped</option>
                                        <option value="Mental Retardation">Mental Retardation</option>
                                        <option value="Paralysis due to physical trauma">Paralysis due to physical trauma</option>
                                        <option value="Paralysis due to stroke">Paralysis due to stroke</option>
                                        <option value="PTSD">PTSD</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Make Beneficiary</td>
                                <td class="v-align">
                                    <div class="custom-control custom-switch py-2">
                                        <input type="checkbox" class="custom-control-input" @change="updateBeneficiary(isBeneficiary)" :checked="isBeneficiary" id="isBeneficiary">
                                        <label class="custom-control-label text-muted" for="isBeneficiary" style="font-weight: normal">This will be added to your insurance beneficiaries</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Notes/Remarks</td>
                                <td>
                                    <textarea type="text" class="form-control" v-model="notes" placeholder="Remarks, comments, or notes" rows="3"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button @click="saveDependent()" class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
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
    name : 'AddDependents.add-dependents',
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
            employeeId : document.querySelector("meta[name='employee-id-current']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
                // You can configure more options here as per Flatpickr documentation
            },
            fullname : '',
            address : '',
            relationship : '',
            birthday : '',
            occupation : '',
            isBeneficiary : false,
            disability : '',
            isBeneficiary : false,
            notes : ''
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
        updateBeneficiary(cond) {
            if (cond) {
                this.isBeneficiary = false
            } else {
                this.isBeneficiary = true
            }
        },
        saveDependent() {
            if (this.isNull(this.fullname) | this.isNull(this.relationship) | this.isNull(this.birthday)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please fill in important fields!'
                })
            } else {
                axios.post(`${ axios.defaults.baseURL }/dependents`, {
                    id : this.generateUniqueId() + this.generateRandomString(),
                    EmployeeId : this.employeeId,
                    DependentName : this.fullname,
                    Address : this.address,
                    Relationship : this.relationship,
                    Birthdate : this.birthday,
                    IsBeneficiary : this.isBeneficiary ? 'Yes' : 'No',
                    Occupation : this.occupation,
                    Disability : this.disability,
                    Notes : this.notes,
                })
                .then(response => {
                    window.location.href = this.baseURL + "/my_account/personal-info"
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error saving dependent!',
                    });
                    console.log(error)
                });
            }
        }
    },
    created() {
        
    },
    mounted() {

    }
}

</script>