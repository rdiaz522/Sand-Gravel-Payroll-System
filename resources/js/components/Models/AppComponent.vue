<template>
<div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <EmployeeListComponent :employeeList="this.employees" v-on:editEmployee="test"></EmployeeListComponent>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <EmployeeComponent title="Add New Employee" name="Save" event="save" :employeeTypeList="this.employee_types"></EmployeeComponent>
        </div>
        <div class="col-xl-6 col-lg-6">
            <EditEmployeeComponent 
            title="Edit Employee" 
            name="Update" event="save"
             :employeeEdit="this.employeeEdit" 
             :employeeTypeList="this.employee_types" 
             v-show="this.showEmployeeEdit">
             </EditEmployeeComponent>
            <EmployeeTypeComponent title="Add New Employee Type" name="Save" event="save"></EmployeeTypeComponent>
        </div>
    </div>
</div>
<!-- <PositionComponent title="Add New Position"  name="Save" event="save"></PositionComponent>
     <LocationComponent title="Add New Location" name="Save" event="save"></LocationComponent> -->
</template>

<script>
import EmployeeTypeComponent from './EmployeeTypeComponent.vue';
import EmployeeListComponent from './EmployeeListComponent.vue';
import EmployeeComponent from './EmployeeComponent.vue';
import EditEmployeeComponent from './EditEmployeeComponent.vue';
export default {
    data() {
        return {
            employees: [],
            employee_data: [],
            employee_types: [],
            employeeEdit: [],
            showEmployeeList: true,
            showEmployeeEdit: false
        }
    },
    components: {
        EmployeeListComponent,
        EmployeeComponent,
        EmployeeTypeComponent,
        EditEmployeeComponent
    },
    methods: {
        async getEmployees() {
            this.showEmployeeEdit = false;
            axios.get(this.$BASE_URL + this.$EMPLOYEES).then((response) => {
                    this.employees = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        async getEmployeeTypes() {
            axios.get(this.$BASE_URL + this.$EMPLOYEETYPE).then((response) => {
                    this.employee_types = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },
        test(props) {
            this.showEmployeeEdit = true;
            this.employeeEdit = props;
        }
    },
    async created() {
        await this.getEmployees();
        await this.getEmployeeTypes();
    },
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>
