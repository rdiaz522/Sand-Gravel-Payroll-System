<template>
    <div>
         <div class="row">
            <div class="col-xl-12 col-lg-12"> 
                <ExpensesListComponent
                :expensesList="this.expensesList" 
                 v-on:editExpenses="this.editExpenses"
                > </ExpensesListComponent>
                 </div>
        </div>
        <div class="row"> 
           <div class="col-xl-6 col-lg-6">
                 <ExpensesComponent
                    title="Expenses" 
                    name="Save" 
                    event="save"
                    :locationData="this.locationData">
                    </ExpensesComponent>
           </div>
           
            <div class="col-xl-6 col-lg-6">
                 <ExpensesEditComponent
                    title="Edit Expenses" 
                    name="Update" 
                    event="save"
                    :locationData="this.locationData"
                    :expensesEdit="this.expensesEdit"
                    v-show="this.showExpensesEdit"
                    >
                    </ExpensesEditComponent>
           </div>
        </div>
    </div>
</template>

<script>
import ExpensesComponent from './ExpensesComponent.vue';
import ExpensesEditComponent from './ExpensesEditComponent.vue'
import ExpensesListComponent from './ExpensesListComponent.vue'
export default {
    data() {
        return {
            locationData:[],
            expensesEdit:[],
            showExpensesEdit:false,
            expensesList:[]
            
        }
    },
    
    components: {
        ExpensesComponent,
        ExpensesEditComponent,
        ExpensesListComponent,
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

        async getExpenses() {
            axios.get(this.$BASE_URL + this.$EXPENSES).then(response => {
                    this.expensesList = response.data.data;
                    this.$HIDE_LOADING();
                })
                .catch((err) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                })
        },

        editExpenses(props) 
        {
            this.showExpensesEdit = true;
            this.expensesEdit = props;
        },
    },

    async created() {
        await this.getLocations();
        await this.getExpenses();
    },
    
    mounted() {
        this.$SHOW_LOADING();
    }
}
</script>

<style>

</style>