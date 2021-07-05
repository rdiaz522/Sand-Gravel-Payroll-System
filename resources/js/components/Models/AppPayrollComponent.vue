<template>
<div>
    <div class="row">
        <div class="col-xl-6 col-lg-6">
             <EmployeeDTRComponent 
            title="Employee Daily Time Record" 
            name="Save" event="save" 
            :employees="this.employees"
            :position="this.position"
            :breakHour="this.breakHour"
            ></EmployeeDTRComponent>
        </div>
         <div class="col-xl-6 col-lg-6">
             <EmployeeDTREditComponent 
                title="Edit Daily Time Record" 
                name="Update" 
                event="save"
                :position="this.position"
                :breakHour="this.breakHour"
                :timeLogsEdit="this.timeLogsEdit"
                v-show="this.showDTREdit"
            ></EmployeeDTREditComponent>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <EmployeeDTRListComponent
            :timelogList="this.timelogList" 
            v-on:editTimeLogs="this.editTimeLogs"
            >
                
            </EmployeeDTRListComponent>
        </div>
    </div>
</div>
</template>

<script>

import EmployeeDTRComponent from './EmployeeDTRComponent.vue';
import EmployeeDTRListComponent from './EmployeeDTRListComponent.vue';
import EmployeeDTREditComponent from './EmployeeDTREditComponent.vue'
export default {
    data() {
        return {
            employees: [],
            position: [],
            breakHour: [],
            timelogList: [],
            showDTREdit:false,
            timeLogsEdit:[]
        }
    },
    components: {
        EmployeeDTRComponent,
        EmployeeDTRListComponent,
        EmployeeDTREditComponent
    },
    methods: {

        async getEmployees() {
            axios.get(this.$BASE_URL + this.$EMPLOYEES).then((response) => {
                    this.employees = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        async getPositions() {
            axios.get(this.$BASE_URL + this.$POSITION).then((response) => {
                    this.position = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        async getBreakHours() {
            axios.get(this.$BASE_URL + this.$BREAKHOURS).then((response) => {
                    this.breakHour = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        async getTimeLogs() {
            axios.get(this.$BASE_URL + this.$EMPLOYEETIMELOGS).then((response) => {
                    this.timelogList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        editTimeLogs(props) {
            this.showDTREdit = true;
            this.timeLogsEdit = props;
        }
    },

    async created() {
        await this.getEmployees();
        await this.getPositions();
        await this.getBreakHours();
        await this.getTimeLogs();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>
