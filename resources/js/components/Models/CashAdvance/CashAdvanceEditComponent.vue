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
                <AppTextBox label="Cash Advance" v-model="cash_advance" placeholder="Enter Cash Advance ..."> </AppTextBox>
            </div>
             <div class="form-group">
                <AppDropdown label="Cash Description" v-model="description" :options="cashAdvanceDescriptions" placeholder="Select Cash Advance Description"> </AppDropdown>
            </div>

            <label>Cash Advance Date</label>

            <div class="form-group">
                <datepicker 
                v-model="cash_advance_date"
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
    props: ['title', 'name', 'event','cashAdvanceEdit', 'cashAdvanceDescriptions'],
    data() {
        return {
            id:'',
            cash_advance: '',
            cash_advance_desc_id: '',
            cash_advance_date: '',
            fullName:'',
            description:''
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
        cashAdvanceEdit: function (newVal) {
            console.log(newVal);
            this.id = newVal.id;
            this.cash_advance = parseFoat(newVal.cash_advance);
            this.cash_advance_date = newVal.cash_advance_date;
            this.cash_advance_desc_id = newVal.cash_advance_description;
            this.description = newVal.description;
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
                cash_advance: parseFloat(this.cash_advance),
                cash_advance_desc_id: this.cash_advance_desc_id,
                cash_advance_date: moment(this.cash_advance_date).format('YYYY-MM-DD')
            }
            const config = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            
            axios.put(this.$BASE_URL + this.$CASHADVANCEDEDUCTION + `/${this.id}`, data, config)
                .then((response) => {
                    this.$parent.getCashAdvance();
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'Cash Advance Updated!', 'success');
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
            console.log(this.cash_advance);
        },
        clearFields() {
            this.id = '';
            this.cash_advance = '';
            this.description = '';
            this.cash_advance_date = '';
            this.fullName = '';
        }
    }
}
</script>
