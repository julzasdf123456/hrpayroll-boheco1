<template>
    <!-- personal info -->
    <div class="section">
        <div class="row">
            <div class="col-10">
                <p class="no-pads text-md">Your Staff Tree</p>
                <p class="no-pads text-muted">Your staff being grouped according to sub-positions. Note that only the HR can assign and re-asign these groupings. If there are employees that
                    are not listed in here, contact HR for immediate actions.
                </p>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 100% !important;" class="img-fluid" src="../../../../public/imgs/staff.png" alt="User profile picture">
            </div>
        </div>

        <!-- STAFF TREE -->
        <div class="card shadow-none mt-3">
            <div class="card-body table-responsive">
                <p class="text-md no-pads">Employee Management Tree</p>
                <span class="text-muted">You can press <strong>F3 or Ctrl+F</strong> to search</span>

                <div class="mt-4" v-html="treeHtml">
                    
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
    name : 'StaffManagement.staff-management',
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
            imgsPath : axios.defaults.imgsPath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            tree : [],
            currentEmployeeId : '',
            treeHtml : ''
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
        getStaffTree() {
            this.tree = []
            this.currentEmployeeId = this.employeeId

            this.getStaff()
        },
        getStaff() {
            axios.get(`${ axios.defaults.baseURL }/my_account/get-employees-by-department`, {
                params : {
                    EmployeeId : this.currentEmployeeId,
                }
            })
            .then(response => {
                var employees = response.data

                var len = employees.length
                var arr = []
                arr[this.currentEmployeeId] = []
                for(let i=0; i<len; i++) {
                    this.tree.push({
                        id : employees[i]['id'],
                        PositionId : employees[i]['PositionId'],
                        ParentPositionId : employees[i]['ParentPositionId'],
                        FirstName : employees[i]['FirstName'],
                        LastName : employees[i]['LastName'],
                        MiddleName : employees[i]['MiddleName'],
                        Suffix : employees[i]['Suffix'],
                        Position : employees[i]['Position'],
                        IsOpen : false,
                        Child : null
                    })
                }
                console.log(this.tree)
                this.tree = this.processTree(this.tree)
                // console.log(this.tree)
                this.treeHtml = this.treeToHtml(this.tree)
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting staff data!\n' + error.response.data
                })
            })
        },
        processTree(list) {
            let temp = {};
            let tree = [];

            for (let i = 0; i < list.length; i++) {
                temp[list[i].id] = list[i];
                temp[list[i].id].children = [];
            }

            const startingNode = temp[this.currentEmployeeId];
            if (startingNode) {
                // Add the starting node and its children to the tree
                tree.push(startingNode);
                this.addChildren(temp, startingNode);
            }
            return tree
        },
        addChildren(temp, node) {
            const children = Object.values(temp).filter(item => item.ParentPositionId === node.PositionId);
            for (let child of children) {
                node.children.push(child);
                this.addChildren(temp, child); // Recursively add children of the child node
            }
        },
        treeToHtml(tree) {
            let html = `<ul class='employee-tree'>`;
            for (let node of tree) {
                html += `<li  class='employee-tree-list'>` +
                            `<div style="display: inline-block; vertical-align: middle;">` +
                                `<img src='${ this.imgsPath }/prof-img.png' class='img-circle'></img>` +
                            `</div>` +
                            `<div style="display: inline-block; height: inherit; vertical-align: middle;">` +
                                `<strong>${node.FirstName} ${node.LastName}</strong><br>` +
                                `${ node.id }` +
                            `</div>` +

                            `<div class="dropdown" style="display: inline;" >
                                <a class="btn btn-link-muted btn-sm float-right" role="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                
                                <div class="dropdown-menu">
                                    <span class="dropdown-item text-muted">${node.FirstName} ${node.LastName}</span>
                                    <div class='divider'></div>
                                    <a class="dropdown-item" href="${ this.baseURL }/my_account/staff-day-off-schedules/${node.id}"><i class="fas fa-toggle-off ico-tab"></i>Manage Day-off Schedules</a>

                                </div>
                            </div>`
                    if (node.children && node.children.length) {
                        html += this.treeToHtml(node.children) 
                    }
                html += `</li>`
            }
            html += `</ul>`
            return html
        },
        openTree(employeeId, isOpen) {
            // reset all open
            this.tree = this.tree.map(obj => {
                return {
                    ...obj,
                    IsOpen : false 
                };
            });

            // find object
            const index = this.tree.findIndex(obj => obj.id === employeeId);

            if (index !== -1) {
                this.tree = this.tree.map(obj => {
                    if (obj.id === employeeId) {
                        return { ...obj, IsOpen : !isOpen }; // Update the name property
                    } else {
                        return obj;
                    }
                })
            }
        },
    },
    created() {
        
    },
    mounted() {
        this.getStaffTree()
    }
}

</script>