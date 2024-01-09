<template>
    <div class="row mb-2" style="margin-top: 10px;">
        <form class="col-md-6 offset-md-3" @submit.prevent="view">
            <div class="input-group">
                <input v-model="search" @keyup="view" type="text" class="form-control" placeholder="Type Name or ID" name="params">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>   
        </form>
    </div>

    <!-- results -->
    <div class="row">
        <div class="col-lg-12 p-3">
            <table class="table table-hover table-sm">
                <thead>
                    <th>ID</th>
                    <th>Employee Name</th>
                    <th>Barangay</th>
                    <th>Town</th>
                    <th>Designation</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="employee in employees.data" :key="employee.id">
                        <td><a :href="baseURL + '/employees/' + employee.id"><strong>{{ employee.id }}</strong></a></td>
                        <td>{{ employee.EmployeeName }}</td>
                        <td>{{ employee.Barangay }}</td>
                        <td>{{ employee.Town }}</td>
                        <td>{{ employee.Designation }}</td>
                        <td class="text-right">
                            <a :href="baseURL + '/employees/' + employee.id" class="btn btn-primary btn-xs"><i class="fas fa-eye ico-tab-mini"></i>View</a>    <!-- IF PORT 80 -->                         
                            <!-- <a :href="'/serviceAccounts/' + account.id" class="btn btn-primary btn-xs"><i class="fas fa-eye ico-tab-mini"></i>View</a>  --> <!-- IF PORT 8000 -->
                        </td>
                    </tr>
                </tbody>
            </table>

            <pagination :data="employees" :limit="10" @pagination-change-page="view"></pagination>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'

export default {
    name : 'EmployeesSearch.search',
    components : {
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            search : '',
            isEditMode : false,
            employees : {},
            baseURL : axios.defaults.baseURL,
        }
    },
    methods : {
        view (page = 1) {
            // axios.get(`/service_accounts/search-account-ajax?page=${page}&search=${this.search}`) // IF PORT 8000
            axios.get(`${ axios.defaults.baseURL }/employees/get-search-results?page=${page}&search=${this.search}`) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.employees = response.data
            })
            .catch(error => {
                console.log(error)
            })
        },
    },
    created() {
        this.view()
    },
    mounted() {
        console.log('page mounted')
    }
}

</script>