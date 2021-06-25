<template>
<div>
    <div class="row">
        <div class="col-xl-6 col-lg-6">

            <PositionComponent 
            title="Add new Location" 
            :locationList="this.locationData" 
            name="Save" event="save">
            </PositionComponent>

        </div>
        <div class="col-xl-6 col-lg-6">

            <LocationComponent 
            title="Add new Department" 
            name="Save" 
            event="save">
            </LocationComponent>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <EmployeeDTRComponent 
            title="Employee DTR" 
            name="Save" event="save" 
            :employees="this.employees"
            :position="this.position"
            :breakHour="this.breakHour"
            ></EmployeeDTRComponent>

        </div>
        <div class="col-xl-6 col-lg-6"></div>
    </div>
</div>
</template>

<script>
import PositionComponent from './PositionComponent.vue';
import LocationComponent from './LocationComponent.vue';
import EmployeeDTRComponent from './EmployeeDTRComponent.vue';
import BreakHourComponent from './BreakHourComponent.vue';
export default {
    data() {
        return {
            locationData: [],
            employees: [],
            position: [],
            breakHour: []
        }
    },
    components: {
        PositionComponent,
        LocationComponent,
        EmployeeDTRComponent,
        BreakHourComponent
    },
    methods: {
        async getLocations() {
            axios.get(this.$BASE_URL + this.$LOCATION).then(response => {
                    this.locationData = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                })
        },

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
    },

    async created() {
        await this.getLocations();
        await this.getEmployees();
        await this.getPositions();
        await this.getBreakHours();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>
