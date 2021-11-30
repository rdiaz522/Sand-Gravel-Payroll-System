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
                <AppTextBox label="SSS" v-model="formData.sss" placeholder="Enter SSS ..."> </AppTextBox>
            </div>
            <div class="form-group">
                <AppTextBox label="PAGIBIG" v-model="formData.pagibig" placeholder="Enter Pagibig ..."> </AppTextBox>
            </div>
            <div class="form-group">
                <AppTextBox label="PHILHEALTH" v-model="formData.philhealth" placeholder="Enter Philhealth ..."> </AppTextBox>
            </div>

            <label>Contribution Date</label>
            <div class="form-group">
                <datepicker 
                v-model="formData.contribution_date"
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
import Multiselect from 'vue-multiselect';
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
export default {
    props:['title','name','event','employees'],
    data() {
        return {
            formData: {
                employee_id:'',
                sss:'',
                pagibig:'',
                philhealth: '',
                contribution_date: moment().format('YYYY-MM-DD'),
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
            this.formData.sss = '';
            this.formData.pagibig = '';
            this.formData.philhealth = '';
            this.formData.contribution_date = moment().format('YYYY-MM-DD'),
            this.value = null;
        },

        save() {
            this.formData.employee_id = this.value.id;
            this.formData.contribution_date = moment(this.formData.contribution_date).format('YYYY-MM-DD');
            this.formData.sss = parseFloat(this.formData.sss);
            this.formData.pagibig = parseFloat(this.formData.pagibig);
            this.formData.philhealth = parseFloat(this.formData.philhealth);
            this.$SHOW_LOADING();
                axios.post(this.$BASE_URL + this.$CONTRIBUTION, this.formData)
                    .then((response) => {
                        this.clearFields();
                        this.$parent.getContributions();
                        this.$HIDE_LOADING();
                        this.$SHOW_MESSAGE('Successfully', 'New Contribution', 'success');
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