<template>
<div>
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <LocationComponent 
            title="Add new Department" 
            name="Save" 
            event="save">
            </LocationComponent>
            
            <PositionComponent 
            title="Add new Location" 
            :locationList="this.locationData" 
            name="Save" event="save">
            </PositionComponent>
        </div>
        <div class="col-xl-6 col-lg-6">
            <EmployeeTypeComponent
            title="Add new Employee Type" 
            name="Save" 
            event="save"
            ></EmployeeTypeComponent>
            <BreakHourComponent
            title="Add new Break Time" 
            name="Save" 
            event="save"
            ></BreakHourComponent>
        </div>
    </div>
</div>
</template>

<script>
import PositionComponent from './PositionComponent.vue';
import LocationComponent from './LocationComponent.vue';
import BreakHourComponent from './BreakHourComponent.vue';
import EmployeeTypeComponent from './EmployeeTypeComponent.vue';
export default {
    data() {
        return {
            locationData: [],
        }
    },
    components: {
        PositionComponent,
        LocationComponent,
        EmployeeTypeComponent,
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
    },

    async created() {
        await this.getLocations();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>