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
                <img style="width: 100% !important;" class="img-fluid" :src="imgsPath + 'personal-info.png'" alt="User profile picture">
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
                            <td class="text-right">
                                <img @click="uploadPhoto()" title="Upload a profile picture" style="height: 50px !important; width: 50px !important; cursor: pointer; object-fit: cover;" class="img-fluid img-circle" :src="imagePreview" alt="n/a">
                                <input type="file" ref="fileInput" @change="onFileChange" class="gone" />
                            </td>
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
                            <td class="v-align">
                                {{ isNull(employeeData.ContactNumbers) ? '-' : employeeData.ContactNumbers }}
                                <button @click="updateContactNumbers(employeeData.ContactNumbers)" class="btn btn-xs btn-link-muted float-right"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Email Address</td>
                            <td class="v-align">
                                {{ isNull(employeeData.EmailAddress) ? '-' : employeeData.EmailAddress }}
                                <button @click="updateEmail(employeeData.EmailAddress)" class="btn btn-xs btn-link-muted float-right"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted v-align fixed-td-md">Current Address</td>
                            <td class="v-align">{{ (isNull(employeeData.StreetCurrent) ? '' : employeeData.StreetCurrent + ', ') + (isNull(employeeData.CurrentBarangay) ? '' : employeeData.CurrentBarangay + ', ') + (isNull(employeeData.CurrentTown) ? '' : employeeData.CurrentTown) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dependents Info -->
        <div class="card shadow-none mt-2">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">Dependents</p>
                <span class="text-muted">Could be your immediate family members, parents, or any direct relative</span>

                <table class="table table-hover mt-4">
                    <thead>
                        <th>Dependent</th>
                        <th>Relationship</th>
                        <th>Beneficiary</th>
                        <th>Disability</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="dependent in dependents" :key="dependent.id">
                            <td class="v-align">
                                <strong>{{ dependent.DependentName }}</strong>
                                <br>
                                <span class="text-muted">{{ isNull(dependent.Address) ? 'no address' : dependent.Address }}</span>
                            </td>
                            <td class="v-align">
                                {{ dependent.Relationship }}
                                <br>
                                <span title="Birthday" class="text-muted"><i class="fas fa-birthday-cake ico-tab-mini"></i>{{ isNull(dependent.Birthdate) ? 'no birthdate' : moment(dependent.Birthdate).format("MMM DD, YYYY") }}</span>
                            </td>
                            <td class="v-align">{{ dependent.IsBeneficiary }}</td>
                            <td class="v-align">{{ dependent.Disability }}</td>
                            <td class="v-align text-right">
                                <button @click="removeDependent(dependent.id)" class="btn btn-link text-muted"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a :href="baseURL + '/dependents/add-dependents'" class="btn btn-primary-skinny float-right">Add Dependents</a>
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
                <img style="width: 100% !important;" class="img-fluid" :src="imgsPath + 'files.png'" alt="User profile picture">
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

    <!-- modal edit contanct -->
    <div ref="modalEditContact" class="modal fade" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Edit Contact Numbers</span>
                </div>
                <div class="modal-body table-responsive">
                    <div class="form-group">
                        <label for="ItemPublic">Your Contact Number</label>
                        <input type="text" class="form-control" v-model="contactNumber" style="width: 100%;" required placeholder="Separate with comma if more than one">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-primary float-right" @click="saveContactNumber()"><i class="fas fa-check ico-tab-mini"></i>Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal edit email -->
    <div ref="modalEditEmail" class="modal fade" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Edit Email Address</span>
                </div>
                <div class="modal-body table-responsive">
                    <div class="form-group">
                        <label for="ItemPublic">Your Email Address</label>
                        <input type="text" class="form-control" v-model="emailAddress" style="width: 100%;" required placeholder="Separate with comma if more than one">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-primary float-right" @click="saveEmailAddress()"><i class="fas fa-check ico-tab-mini"></i>Update</button>
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
            imgsPath : axios.defaults.imgsPath,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            files : {},
            employeeData : '',
            dependents : [],
            contactNumber : '',
            emailAddress : '',
            selectedFile: null,
            imagePreview : null,
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
            axios.get(`${ this.baseURL }/employees/get-employee-full-ajax`, {
                params : {
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.employeeData = response.data

                if (!this.isNull(this.employeeData)) {
                    if (!this.isNull(this.employeeData.ProfilePicture)) {
                        this.imagePreview = this.imgsPath + "/profiles/" + this.employeeId + '.jpg'
                    } else {
                        this.imagePreview = this.imgsPath + 'prof-img.png'
                    }
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employee data!\n' + error.response.data
                })
            })
        },
        getDependents() {
            this.dependents = null
            axios.get(`${ axios.defaults.baseURL }/dependents/get-dependents`, {
                params : {
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.dependents = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting dependents!'
                })
            })
        },
        removeDependent(id) {
            Swal.fire({
                title: "Remove dependent?",
                text : 'You can always re-add this anytime.',
                showCancelButton: true,
                confirmButtonText: "Remove",
                confirmButtonColor : "#e03822"
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get(`${ axios.defaults.baseURL }/dependents/remove-dependent`, {
                        params : {
                            id : id,
                        }
                    })
                    .then(response => {
                        this.dependents = this.dependents.filter(obj => obj.id !== id)
                        this.toast.fire({
                            icon : 'success',
                            text : 'Dependent removed!'
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error deleting dependent!'
                        })
                    })
                }
            })
        },
        updateContactNumbers(contactNumber) {
            let modalElement = this.$refs.modalEditContact
            $(modalElement).modal('show')

            this.contactNumber = contactNumber
        },
        saveContactNumber() {
            axios.post(`${ axios.defaults.baseURL }/employees/update-ajax/${this.employeeId}`, {
                id : this.employeeId,
                ContactNumbers : this.contactNumber,
                EmailAddress : this.employeeData.EmailAddress,
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Contact numbers updated!'
                })

                if (!this.isNull(this.employeeData)) {
                    this.employeeData.ContactNumbers = this.contactNumber
                }

                let modalElement = this.$refs.modalEditContact
                $(modalElement).modal('hide')
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error updating contact numbers!'
                })
            })
        },
        updateEmail(email) {
            let modalElement = this.$refs.modalEditEmail
            $(modalElement).modal('show')

            this.emailAddress = email
        },
        saveEmailAddress() {
            axios.post(`${ axios.defaults.baseURL }/employees/update-ajax/${this.employeeId}`, {
                id : this.employeeId,
                ContactNumbers : this.employeeData.ContactNumbers,
                EmailAddress : this.emailAddress,
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Email address updated!'
                })

                if (!this.isNull(this.employeeData)) {
                    this.employeeData.EmailAddress = this.emailAddress
                }

                let modalElement = this.$refs.modalEditEmail
                $(modalElement).modal('hide')
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error updating email address!'
                })
            })
        },
        // upload photo
        uploadPhoto() {
            this.$refs.fileInput.click();
        },
        onFileChange(event) {
            this.selectedFile = event.target.files[0];

            // Generate a preview of the image
            const reader = new FileReader();
            reader.readAsDataURL(this.selectedFile);
            reader.onload = e => {
                this.imagePreview = e.target.result // Update image preview
            }

            this.uploadImage()
        },
        async uploadImage() {
            if (!this.selectedFile) {
                alert('Please select a file');
                return;
            }

            const formData = new FormData();
            formData.append('image', this.selectedFile)
            formData.append('employeeId', this.employeeId)

            try {
                const response = await axios.post(`${ axios.defaults.baseURL }/employees/upload-profile-image`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                console.log('Upload successful:', response.data)
                this.toast.fire({
                    icon : 'success',
                    text : 'Profile picture uploaded and updated!'
                })
            } catch (error) {
                console.error('Error uploading the file:', error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error uploading profile picture!'
                })
            }
        },
    },
    created() {
        
    },
    mounted() {
        this.getEmployeeInfo()
        this.getFiles()
        this.getDependents()

        this.imagePreview = this.imgsPath + 'prof-img.png'
    }
}

</script>