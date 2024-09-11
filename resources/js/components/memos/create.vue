<template>
    <div class="row">
        <!-- form -->
        <div class="col-lg-8">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title">Compose</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <span class="text-muted">Memo Number</span>
                            <input type="text" class="form-control mb-3" v-model="memoNumber">
                        </div>

                        <div class="col-lg-6">
                            <span class="text-muted">Memo Type</span>
                            <select class="form-control" v-model="memoType">
                                <option value="Information">Information</option>
                                <option value="Violation">Violation</option>
                            </select>
                        </div>

                        <div class="col-lg-12">
                            <span class="text-muted">Memo Title</span>
                            <input type="text" class="form-control" v-model="title" placeholder="Memo title...">
                        </div>

                        <div class="col-lg-12 my-3">
                            <span class="text-muted">Compose Content</span>
                            <div style="display: flex; justify-items: start;">
                                <button class="btn btn-sm" @click="editor.chain().focus().toggleBold().run()" :disabled="!editor.can().chain().focus().toggleBold().run()" :class="{ 'btn-primary': editor.isActive('bold') }"><i class="fas fa-bold"></i></button>
                                <button class="btn btn-sm" @click="editor.chain().focus().toggleItalic().run()" :disabled="!editor.can().chain().focus().toggleItalic().run()" :class="{ 'btn-primary': editor.isActive('italic') }"><i class="fas fa-italic"></i></button>
                                <button class="btn btn-sm" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'btn-primary': editor.isActive('bulletList') }"><i class="fas fa-list-ul"></i></button>
                                <button class="btn btn-sm" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'btn-primary': editor.isActive('orderedList') }"><i class="fas fa-list-ol"></i></button>
                            </div>
                            <editor-content :editor="editor" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- recipients -->
        <div class="col-lg-4">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title">Set Recipients</span>
                </div>
                <div class="card-body">
                    <span class="text-muted">By Department</span>
                    <div class="form-checkbox mt-2 ml-4 mb-3">
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="ESD">
                            ESD
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="ISD">
                            ISD
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="OGM">
                            OGM
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="OSD">
                            OSD
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="PGD">
                            PGD
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="SEEAD">
                            SEEAD
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="Department" v-model="departments" value="SUB-OFFICE">
                            SUB-OFFICE
                        </label>
                    </div>

                    <span class="text-muted">Or By Specific Individuals</span>
                    <select class="custom-select select2 mb-3" v-model="employeeSelect" @change="addEmployee">
                        <option value="">-- Select --</option>
                        <option v-for="employee in employees" :value="employee.id" >{{ employee.LastName + ', ' + employee.FirstName }}</option>
                    </select>
                    <ul>
                        <li v-for="selected in selectedEmployees">
                            <button class="btn btn-sm" title="Remove" @click="removeSelected(`${ selected.id }`)"><i class="fas fa-times-circle text-danger"></i></button>
                            {{ selected.LastName + ', ' + selected.FirstName }} 
                        </li>
                    </ul>

                    <span class="text-muted">SMS Notifications</span>
                    <div class="form-group custom-control custom-switch mt-2 ml-4">
                        <input type="checkbox" class="custom-control-input" name="SendSms" id="SendSms" value="Yes" v-model="sendSms">
                        <label class="custom-control-label" for="SendSms" id="SendSmsLabel">Also Send SMS</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" @click="submit">Submit Memo</button>
                </div>
            </div>
        </div>
            
    </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import axios from 'axios';
import moment from 'moment';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    components: {
        EditorContent,
        Swal,
    },

    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            editor: new Editor({
                content: '<p></p>',
                extensions: [
                    StarterKit,
                ],
            }),
            content : null,
            title : null,
            memoNumber : null,
            memoType : 'Information',
            departments : [],
            sendSms : '',
            employees : [],
            employeeSelect : '',
            selectedEmployees : [],
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
        getEmployees() {
            axios.get(`${ this.baseURL }/employees/get-employees-ajax`)
            .then(response => {
                this.employees = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting employees!',
                });
                console.log(error)
            })
        },
        addEmployee() {
            this.selectedEmployees.push(this.employees.find(obj => obj.id === this.employeeSelect))
        },
        removeSelected(id) {
            this.selectedEmployees = this.selectedEmployees.filter(obj => obj.id !== id)
            console.log(this.selectedEmployees)
        },
        submit() {
            if (this.isNull(this.memoNumber) | this.isNull(this.title) | this.isNull(this.editor.getText())) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please make sure Memo Number, Memo Title, and Content are filled!'
                })
            } else {
                
            }
        }
    },
    mounted() {
        // this.editor = new Editor({
        //     content: '<p></p>',
        //     extensions: [
        //         StarterKit,
        //     ],
        // })

        this.getEmployees()
    },
    beforeUnmount() {
        this.editor.destroy()
    },
}
</script>