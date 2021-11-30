<template>
<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
                 <div class="form-group">
                    <AppDropdown label="Department" v-model="department_id" :options="locationData" placeholder="Select Department Type"> </AppDropdown>
                </div>

                <div class="form-group">
                    <AppTextBox label="Description" v-model="description" placeholder="Enter Description ..."> </AppTextBox>
                </div>

                <div class="form-group">
                    <AppTextBox label="Amount" v-model="amount" placeholder="Enter Cash Amount ..."> </AppTextBox>
                </div>

                <div class="form-group">
                    <AppTextBox label="Cash From" v-model="cash_from" placeholder="Enter Cash From ..."> </AppTextBox>
                </div>
                
                <label>Cash Date</label>
                <div class="form-group">
                    <datepicker 
                    v-model="cash_date"
                    :circle="true"
                    lang="en"/>
                </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <AppButton v-on:edit="edit" :btn-name="name" btn-method="edit" v-if="isOnEdit"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
    </div>
</div>
</template>

<script>
import AppTextBox from '../../AppComponents/AppTextBox.vue';
import AppDropdown from '../../AppComponents/AppDropdown.vue';
import AppButton from '../../AppComponents/AppButton.vue';
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
export default {
    props: ['title', 'name', 'event','expensesEdit','locationData'],
    data() {
        return {
            id:'',
            department_id:'',
            description:'',
            amount:'',
            cash_from:'',
            cash_date: ''
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
    watch: {
        expensesEdit: function (newVal) {
            this.id = newVal.id;
            this.department_id = newVal.department_id;
            this.description = newVal.description;
            this.amount = parseFloat(newVal.amount);
            this.cash_from = newVal.cash_from;
            this.cash_date = newVal.cash_date;
        }
    },
    components: {
        AppTextBox,
        AppDropdown,
        AppButton,
        datepicker: VueDatepickerUi
    },
    methods: {
        save() {
            this.$SHOW_LOADING();
            const data = {
                department_id: this.department_id,
                description: this.description,
                amount: this.amount,
                cash_from: this.cash_from,
                cash_date: moment(this.cash_date).format('YYYY-MM-DD')
            }
            const config = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            axios.put(this.$BASE_URL + this.$EXPENSES + `/${this.id}`, data, config)
                .then((response) => {
                    this.$parent.getLocations();
                    this.$parent.getExpenses();
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'Expenses Updated!', 'success');
                })
                .catch((error) => {
                   this.$HIDE_LOADING();
                    if(error.response) {
                         this.$SHOW_MESSAGE('Oops..', error.response.data, 'error');
                    } else {
                        this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                    }
                });

        },

        edit() {

        },

        onCancel() {
            this.clearFields();
        },
        clearFields() {
            this.department_id = '';
            this.description = '';
            this.amount = '';
            this.cash_from = '';
            this.cash_date = '';
            this.$parent.showExpensesEdit = false;
        }
    }
}
</script>
