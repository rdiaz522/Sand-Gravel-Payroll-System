<template>
<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <h4>{{fullName}}</h4>
            <div class="form-group">
                <AppTextBox label="Cash Deduction" v-model="cash_deduction" placeholder="Enter Cash Deduction ..."> </AppTextBox>
            </div>

            <label>Cash Deduction Date</label>

            <div class="form-group">
                <datepicker 
                v-model="cash_deduction_date"
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
    props: ['title', 'name', 'event','cashDeductionEdit'],
    data() {
        return {
            id:'',
            cash_deduction: '',
            cash_deduction_date: '',
            fullName:''
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
        cashDeductionEdit: function (newVal) {
            this.id = newVal.id;
            this.cash_deduction = newVal.cash_deduction;
            this.cash_deduction_date = newVal.cash_deduction_date;
            this.fullName = newVal.employee_fullname
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
                cash_deduction: this.cash_deduction,
                cash_deduction_date: moment(this.cash_deduction_date).format('YYYY-MM-DD'),
            }
            const config = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            axios.put(this.$BASE_URL + this.$CASHDEDUCTION + `/${this.id}`, data, config)
                .then((response) => {
                    this.$parent.getCashDeduction();
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'Cash Updated Updated!', 'success');
                })
                .catch((error) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });

        },

        edit() {

        },

        onCancel() {
            this.clearFields();
        },
        clearFields() {
            this.id = '';
            this.cash_deduction = '';
            this.cash_deduction_date = '';
            this.fullName = '';
        }
    }
}
</script>
