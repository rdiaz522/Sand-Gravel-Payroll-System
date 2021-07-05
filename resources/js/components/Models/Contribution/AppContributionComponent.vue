<template>
    <div>
        <div class="row"> 
           <div class="col-xl-6 col-lg-6">
                 <ContributionComponent
                    title="Contribution" 
                    name="Save" 
                    event="save"
                    :employees="this.employees">
                    </ContributionComponent>
           </div>
           
            <div class="col-xl-6 col-lg-6">
                 <ContributionEditComponent
                    title="Edit Contribution" 
                    name="Update" 
                    event="save"
                    :contributionEdit="this.contributionEdit"
                    v-show="this.showContributionEdit"
                    :totalContribution="this.totalContribution"
                    >
                    </ContributionEditComponent>

                    <ContributionTotalComponent
                    title="Total Contributions" 
                    name="Update" 
                    event="save"
                    v-show="this.showTotalContribution"
                    :totalContribution="this.totalContribution"
                    >

                    </ContributionTotalComponent>
                
           </div>
        </div>
            <div class="row">
            <div class="col-xl-12 col-lg-12"> 
                <ContributionListComponent
                :contributionList="this.contributionList" 
                 v-on:editContribution="this.editContribution"
                 v-on:showOverTotal="this.showOverTotal"
                > </ContributionListComponent>
            </div>
        </div>
    </div>
</template>

<script>
import ContributionComponent from './ContributionComponent.vue';
import ContributionListComponent from './ContributionListComponent.vue'
import ContributionEditComponent from './ContributionEditComponent.vue'
import ContributionTotalComponent from './ContributionTotalComponent.vue'
export default {
    data() {
        return {
            employees:[],
            contributionList:[],
            contributionEdit:[],
            showContributionEdit: false,
            showTotalContribution: false,
            totalContribution:[]
        }
    },
    
    components: {
        ContributionComponent,
        ContributionListComponent,
        ContributionEditComponent,
        ContributionTotalComponent
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

        async getContributions() {
            axios.get(this.$BASE_URL + this.$CONTRIBUTION).then((response) => {
                    this.showContributionEdit = false;
                    this.contributionList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        editContribution(props) 
        {
            this.showContributionEdit = true;
            this.contributionEdit = props;
        },

        getOverallTotalContribution(props) {
             axios.get(this.$BASE_URL + this.$CONTRIBUTION + `/${props.employee_id}`).then((response) => {
                    this.totalContribution = response.data;
                    this.$HIDE_LOADING();
                })
            .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
            });
        },

        showOverTotal(props) {
            this.showTotalContribution = true;
            this.getOverallTotalContribution(props);
        }
    },

    async created() {
        await this.getEmployees();
        await this.getContributions();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>