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
                <AppDropdown label="Select Cash Advance" v-model="formData.cash_advance_desc_id" :options="cashAdvanceDescriptions" placeholder="Select Cash Advance Description"> </AppDropdown>
            </div>

            <div class="form-group">
                <AppTextBox label="Cash Advance Amount" v-model="formData.cash_advance" placeholder="Enter Cash Advance ..."> </AppTextBox>
            </div>
             
            <label>Cash Advance Date</label>
            <div class="form-group">
                <datepicker 
                v-model="formData.cash_advance_date"
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
    props:['title','name','event','employees','cashAdvanceDescriptions'],
    data() {
        return {
            formData: {
                employee_id:'',
                cash_advance:'',
                cash_advance_desc_id:'',
                cash_advance_date:  moment().format('YYYY-MM-DD')
            },
            value:null
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
        },

        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.formData.employee_id = '';
            this.formData.cash_advance = '';
            this.formData.cash_advance_desc_id = '';
            this.formData.cash_advance_date =  moment().format('YYYY-MM-DD');
            this.value = null;
        },

        save() {
            this.formData.employee_id = this.value.id;
            this.formData.cash_advance_date = moment(this.formData.cash_advance_date).format('YYYY-MM-DD');
            this.formData.cash_advance = parseFloat(this.formData.cash_advance);
            this.$SHOW_LOADING();
                axios.post(this.$BASE_URL + this.$CASHADVANCEDEDUCTION, this.formData)
                    .then((response) => {
                        this.clearFields();
                        this.$parent.getCashAdvance();
                        this.$HIDE_LOADING();
                        this.$SHOW_MESSAGE('Successfully', 'New Cash Advance', 'success');
                })
                .catch((error) => {
                     this.$HIDE_LOADING();
                    if(error.response) {
                         this.$SHOW_MESSAGE('Oops..', error.response.data, 'error');
                    } else {
                        this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                    }
            });
        }
    }
}
</script>

<style>

</style>