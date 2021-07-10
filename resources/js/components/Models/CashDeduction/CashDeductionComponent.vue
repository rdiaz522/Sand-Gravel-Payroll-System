!<template>
 <div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="typo__label">Employees</label>
                 <multiselect 
                        v-model="value" 
                        deselect-label="Remove selected value" 
                        track-by="firstname" 
                        :custom-label="fullName" 
                        placeholder="Search employees" 
                        :options="employees"
                        @select="atSelect" 
                        :searchable="true" 
                        :block-keys="['Tab']"
                        :allow-empty="true">
                        <template slot="singleLabel" 
                        slot-scope="{ option }">

                        <strong >{{ option.firstname }} {{ option.lastname }}</strong>
                        
                        </template>
                </multiselect>
            </div>

            <div class="form-group">
                 <AppDropdown :disabled="disabledTimePicker" label="Cash Advance" v-model="cash_advance_id" :options="cashAdvanceList" placeholder="Select Cash Advance" v-on:input="onSelect"> </AppDropdown>
            </div>

             <div class="form-group">
                <AppTextBox label="Total Cash Advance" v-model="this.total_cashAdvance" disabled> </AppTextBox>
            </div>

            <div class="form-group">
                <AppTextBox label="Cash Deduction" v-model="formData.cash_deduction" placeholder="Enter Cash Deduction ..."> </AppTextBox>
            </div>
            
            <label>Cash Deduction Date</label>
            <div class="form-group">
                <datepicker 
                v-model="formData.cash_deduction_date"
                :circle="true"
                lang="en"/>
            </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
    </div>
</div>
</template>

<script>
import AppTextBox from '../../AppComponents/AppTextBox.vue';
import AppButton from '../../AppComponents/AppButton.vue';
import AppDropdown from '../../AppComponents/AppDropdown.vue';
import Multiselect from 'vue-multiselect';
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
export default {
    props:['title','name','event','employees'],
    data() {
        return {
            formData: {
                employee_id:'',
                cash_deduction:'',
                employee_cash_advance_id:'',
                new_cash_advance_balance: '',
                cash_deduction_date: moment().format('YYYY-MM-DD')
            },
            cash_advance_id:'',
            cashAdvanceList:[],
            value:null,
            total_cashAdvance:0,
            disabledTimePicker:true
        }
    },
     computed: {
         isOnsave: function () {
            if (this.event === 'save') {
                return true;
            }
            return false;
        },

        isOnEdit: function () {
            if (this.event === 'edit') {
                return true;
            }

            return false;
        },
    },
    components: {
        AppTextBox,
        AppButton,
        AppDropdown,
        Multiselect,
        datepicker: VueDatepickerUi
    },
    methods: {
        fullName ({ firstname,middlename,lastname }) {
            return `${firstname} ${middlename}, ${lastname}`
            },

        atSelect() {
            this.disabledTimePicker = false; 
            this.$SHOW_LOADING();
           setTimeout(() => {
                axios.get(this.$BASE_URL + this.$CASHADVANCEDEDUCTION + `/${this.value.id}`)
                .then((response) => {
                    this.$HIDE_LOADING();
                    this.cashAdvanceList = response.data.data
                })
           }, 500);
        },

        onSelect(id) {
            this.$SHOW_LOADING();
            setTimeout(() => {
                    axios.get(this.$BASE_URL + this.$CASHDEDUCTION + `/${id}`)
                    .then((response) => {
                        this.$HIDE_LOADING();
                        this.total_cashAdvance = response.data;
                        this.formData.employee_cash_advance_id = id;
                    })
            }, 500);
        },

        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.formData.employee_id = '';
            this.formData.cash_deduction = '';
            this.formData.employee_cash_advance_id = '';
            this.cash_advance_id = '';
            this.formData.cash_deduction_date =  moment().format('YYYY-MM-DD');
            this.value = null;
            this.total_cashAdvance = 0;
            this.disabledTimePicker = true;
        },

        save() {
            this.formData.employee_id = this.value.id;
            this.$SHOW_LOADING();
            if(parseFloat(this.formData.cash_deduction) <= this.total_cashAdvance) {
                this.formData.new_cash_advance_balance = parseFloat(this.total_cashAdvance) - parseFloat(this.formData.cash_deduction);
                this.formData.cash_deduction_date = moment(this.formData.cash_deduction_date).format('YYYY-MM-DD');
                axios.post(this.$BASE_URL + this.$CASHDEDUCTION, this.formData)
                        .then((response) => {
                            this.$parent.getCashDeduction();
                            this.clearFields();
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Successfully', 'New Cash Deduction', 'success');
                    })
                    .catch((error) => {
                        this.$HIDE_LOADING();
                        this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
            } else {
                this.$HIDE_LOADING();
                this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Cash Deduction must less than Cash Advance', 'error');
            }
                
        }
    }
}
</script>

<style>

</style>