<template>
    <div>
        <div class="row"> 
           <div class="col-xl-6 col-lg-6">
                 <CashAdvanceComponent
                    title="Cash Advance" 
                    name="Save" 
                    event="save"
                    :employees="this.employees"
                    :cashAdvanceDescriptions="this.cashAdvanceDescription">
                    </CashAdvanceComponent>
           </div>
           
            <div class="col-xl-6 col-lg-6">
                 <CashAdvanceEditComponent
                    title="Edit Cash Advance" 
                    name="Update" 
                    event="save"
                    :cashAdvanceEdit="this.cashAdvanceEdit"
                    :cashAdvanceDescriptions="this.cashAdvanceDescription"
                    v-show="this.showCashAdvanceEdit"
                    >
                    </CashAdvanceEditComponent>
           </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12"> 
                <CashAdvanceListComponent
                :cashAdvanceList="this.cashAdvanceList" 
                 v-on:editCashAdvance="this.editCashAdvance"
                > </CashAdvanceListComponent>
                 </div>
        </div>
    </div>
</template>

<script>
import CashAdvanceComponent from './CashAdvanceComponent.vue';
import CashAdvanceListComponent from './CashAdvanceListComponent.vue'
import CashAdvanceEditComponent from './CashAdvanceEditComponent.vue'
export default {
    data() {
        return {
            employees:[],
            cashAdvanceList:[],
            cashAdvanceEdit:[],
            cashAdvanceDescription:[],
            showCashAdvanceEdit: false
        }
    },
    
    components: {
        CashAdvanceComponent,
        CashAdvanceListComponent,
        CashAdvanceEditComponent
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

        async getCashAdvance() {
            axios.get(this.$BASE_URL + this.$CASHADVANCEDEDUCTION).then((response) => {
                    this.showCashAdvanceEdit = false;
                    this.cashAdvanceList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        async getCashAdvanceDescription() {
            axios.get(this.$BASE_URL + this.$CASHADVANCEDESCRIPTION).then((response) => {
                    this.showCashAdvanceEdit = false;
                    this.cashAdvanceDescription = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        editCashAdvance(props) {
            this.showCashAdvanceEdit = true;
            this.cashAdvanceEdit = props;
        }
    },

    async created() {
        await this.getEmployees();
        await this.getCashAdvance();
        await this.getCashAdvanceDescription();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>