<template>
    <div>
         <div class="row">
           <div class="col-xl-6 col-lg-6">
                 <ReportComponent
                    title="Generate Report" 
                    name="Save" 
                    event="save"
                    :locationList="this.locationList"
                    :positionList="this.positionList"
                    >
                    </ReportComponent>
           </div>
        </div>
        <div class="row"> 
             <div class="col-xl-12 col-lg-12"> 
                <ReportListComponent
                :reportList="this.reportList" 
                > </ReportListComponent>
            </div>
        </div>
    </div>
</template>

<script>
import ReportComponent from './ReportComponent.vue';
import ReportListComponent from './ReportListComponent.vue'
export default {
    data() {
        return {
            reportList:[],
            locationList:[],
            positionList:[]
        }
    },
    
    components: {
        ReportComponent,
        ReportListComponent
    },

    methods: {
        async getReports() {
            axios.get(this.$BASE_URL + this.$REPORTS).then((response) => {
                    this.reportList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        async getLocations() {
            axios.get(this.$BASE_URL + this.$LOCATION).then(response => {
                    this.locationList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                })
        },

        async getPositions() {
            axios.get(this.$BASE_URL + this.$POSITION).then(response => {
                    this.positionList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                })
        },
    },

    async created() {
        await this.getReports();
        await this.getLocations();
        await this.getPositions();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>