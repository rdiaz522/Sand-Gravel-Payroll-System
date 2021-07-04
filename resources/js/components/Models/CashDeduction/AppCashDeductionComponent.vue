<template>
    <div>
         <div class="row">
            <div class="col-xl-12 col-lg-12"> 
                <CashDeductionListComponent
                :cashDeductionList="this.cashDeductionList" 
                 v-on:editCashDeduction="this.editCashDeduction"
                > </CashDeductionListComponent>
                 </div>
        </div>
        <div class="row"> 
           <div class="col-xl-6 col-lg-6">
                 <CashDeductionComponent
                    title="Cash Deduction" 
                    name="Save" 
                    event="save"
                    :employees="this.employees">
                    </CashDeductionComponent>
           </div>
           
            <div class="col-xl-6 col-lg-6">
                 <CashDeductionEditComponent
                    title="Edit Cash Advance" 
                    name="Update" 
                    event="save"
                    :cashDeductionEdit="this.cashDeductionEdit"
                    v-show="this.showCashDeductionEdit"
                    >
                    </CashDeductionEditComponent>
           </div>
        </div>
    </div>
</template>

<script>
import CashDeductionComponent from './CashDeductionComponent.vue';
import CashDeductionListComponent from './CashDeductionListComponent.vue'
import CashDeductionEditComponent from './CashDeductionEditComponent.vue'
export default {
    data() {
        return {
            employees:[],
            cashDeductionList:[],
            cashDeductionEdit:[],
            showCashDeductionEdit: false
        }
    },
    
    components: {
        CashDeductionComponent,
        CashDeductionListComponent,
        CashDeductionEditComponent
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

        async getCashDeduction() {
            axios.get(this.$BASE_URL + this.$CASHDEDUCTION).then((response) => {
                    this.showCashDeductionEdit = false;
                    this.cashDeductionList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        editCashDeduction(props) {
            this.showCashDeductionEdit = true;
            this.cashDeductionEdit = props;
        }
    },

    async created() {
        await this.getEmployees();
        await this.getCashDeduction();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>