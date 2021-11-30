!<template>
 <div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <AppDropdown label="Department" v-model="formData.department_id" :options="locationData" placeholder="Select Department Type"> </AppDropdown>
            </div>

            <div class="form-group">
                <AppTextBox label="Description" v-model="formData.description" placeholder="Enter Description ..."> </AppTextBox>
            </div>

            <div class="form-group">
                <AppTextBox label="Amount" v-model="formData.amount" placeholder="Enter Cash Amount ..."> </AppTextBox>
            </div>

            <div class="form-group">
                <AppTextBox label="Cash From" v-model="formData.cash_from" placeholder="Enter Cash From ..."> </AppTextBox>
            </div>
            
            <label>Cash Date</label>
            <div class="form-group">
                <datepicker 
                v-model="formData.cash_date"
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
import AppDropdown from '../../AppComponents/AppDropdown.vue'
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
export default {
    props:['title','name','event','locationData'],
    data() {
        return {
            formData: {
                department_id:'',
                description:'',
                amount:'',
                cash_from:'',
                cash_date: moment().format('YYYY-MM-DD'),
            },
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
        datepicker: VueDatepickerUi
    },
    methods: {
        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.formData.department_id = '';
            this.formData.description = '';
            this.formData.amount = '';
            this.formData.cash_from = '';
            this.formData.cash_date = moment().format('YYYY-MM-DD');
        },

        save() {
            this.$SHOW_LOADING();
            this.formData.cash_date = moment(this.formData.cash_date).format('YYYY-MM-DD');
            this.formData.amount = parseFloat(this.formData.amount);
            axios.post(this.$BASE_URL + this.$EXPENSES, this.formData)
                        .then((response) => {
                            this.clearFields();
                            this.$parent.getLocations();
                            this.$parent.getExpenses();
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Successfully', 'New Expenses Added', 'success');
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