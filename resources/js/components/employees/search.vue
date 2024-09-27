<template>
    <div class="row mb-2" style="margin-top: 10px;">
        <form class="col-md-6 offset-md-3" @submit.prevent="view">
            <div class="input-group">
                <input v-model="search" @keyup="view" type="text" class="form-control" placeholder="Type Name or ID" name="params" autofocus>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>   
        </form>
    </div>

    <!-- results -->
    <div class="row" style="margin-top: 24px; padding-left: 24px; padding-right: 24px;">
        <div class="col-lg-4 col-md-6" v-for="employee in employees.data" :key="employee.id">
            <div class="card shadow-none card-widget widget-user-2">
                <div class="widget-user-header">
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2 img-cover" style="width: 56px; height: 56px;" :src="isNull(employee.ProfilePicture) ? (imgsPath + '/prof-img.png') : (imgsPath + 'profiles/' + employee.ProfilePicture)" alt="pp">
                    </div>
                    <h3 class="widget-user-username" style="padding-left: 10px;"><strong>{{ employee.EmployeeName }}</strong></h3>
                    <span class="text-muted" style="margin-left: 20px;">{{ employee.Designation }}</span>
                </div>

                <div class="card-body" style="padding: 8px 16px 4px 48px;">
                    <p class="text-muted" style="margin-top: 5px !important; margin-bottom: 5px !important;"><i class="fas fa-hashtag ico-tab"></i>{{ employee.id }}</p>
                    <p class="text-muted" style="margin-top: 5px !important; margin-bottom: 5px !important;"><i class="fas fa-map-marker-alt ico-tab"></i>{{ employee.Town + (isNull(employee.Barangays) ? '' : ', ' + employee.Barangays) }}</p>
                    <p class="text-muted" style="margin-top: 5px !important; margin-bottom: 5px !important;"><i class="fas fa-phone ico-tab"></i>{{ employee.ContactNumbers }}</p>
                    <p class="text-muted" style="margin-top: 5px !important; margin-bottom: 5px !important;"><i class="fas fa-at ico-tab"></i>{{ employee.EmailAddress }}</p>
                </div>

                <div class="card-footer">
                    <a :href="baseURL + '/employees/' + employee.id" class="btn btn-primary-skinny float-right"><i class="fas fa-eye ico-tab-mini"></i>View</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import jquery from 'jquery';

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
            imgsPath : axios.defaults.imgsPath,
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