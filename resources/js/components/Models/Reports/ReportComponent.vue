<template>
 <div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Report Type</label>
               <select  class="form-control" v-model="formData.report_type" @change="onChange($event)">
                    <option value="" disabled selected>Select your option</option>
                    <option value="Weekly Payroll">WEEKLY PAYROLL BY DEPARTMENT</option>
                    <option value="Department Expenses">DEPARTMENT EXPENSES REPORT</option>
                    <option value="Department Total Pay Report">TOTAL PAYMENT BY DEPARTMENT</option>
                    <option value="Payroll Report">PAYROLL REPORT</option>
                    <option value="Daily Processing">DAILY PROCESSING LOG REPORT</option>
                    <option value="Employee Record History">EMPLOYEE RECORD HISTORY REPORT</option>
                </select>
            </div>

            <div class="form-group" v-show="this.departmentExpenses">
                <AppDropdown label="Department" v-model="formData.department_id" :options="locationList" placeholder="Select Department Type"> </AppDropdown>
            </div>

            <div class="form-group" v-show="this.showPosition">
                <AppDropdown label="Location" v-model="formData.location_id" :options="filteredPosition" placeholder="Select Department Type"> </AppDropdown>
            </div>

            <div class="form-group" v-show="this.showEmployee">
                <label class="typo__label">Employees</label>
                 <multiselect
                        v-model="value"
                        deselect-label="Remove selected value"
                        track-by="firstname"
                        :custom-label="fullName"
                        placeholder="Search employees"
                        :options="employeeList"
                        :searchable="true"
                        :block-keys="['Tab']"
                        :allow-empty="true">
                        <template slot="singleLabel"
                        slot-scope="{ option }">

                        <strong >{{ option.firstname }} {{ option.lastname }}</strong>

                        </template>
                </multiselect>
            </div>

            <label>Start Date</label>
            <div class="form-group">
                <datepicker
                v-model="formData.start_date"
                :circle="true"
                lang="en"/>
            </div>

             <label>End Date</label>
            <div class="form-group">
                <datepicker
                :disabled="endDate"
                v-model="formData.end_date"
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
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
import Multiselect from 'vue-multiselect'
export default {
    props:['title','name','event','locationList', 'positionList', 'employeeList'],
    data() {
        return {
            formData: {
                report_type:'',
                start_date: '',
                end_date: '',
                department_id:'',
                location_id: '',
                employee_id: ''
            },
            value:null,
            departmentExpenses: false,
            showPosition: false,
            showEmployee: false,
            endDate: false
        }
    },
     computed: {
         isOnsave: function () {
            if (this.event === 'save') {
                return true;
            }
            return false;
        },

        filteredPosition: function () {
            return this.positionList.filter(position => position.location_id == this.formData.department_id);
        }
    },
    components: {
        AppTextBox,
        AppButton,
        AppDropdown,
        datepicker: VueDatepickerUi,
        Multiselect
    },
    methods: {
        fullName ({ firstname,middlename,lastname }) {
            return `${firstname} ${middlename}, ${lastname}`
            },
        onCancel() {
            this.clearFields();
        },
        onChange(event) {
            if(this.formData.report_type === 'Department Expenses') {
                    this.departmentExpenses = true;
                     this.showPosition = false;
            } else if( this.formData.report_type === 'Weekly Payroll') {
                    this.departmentExpenses = true;
                    this.showPosition = true;
            } else {
                  this.departmentExpenses = false;
                  this.showPosition = false;
            }

            if(this.formData.report_type === 'Daily Processing') {
                   this.endDate = true;
            } else {
                  this.endDate = false;
            }

            if(this.formData.report_type === 'Employee Record History') {
                this.showEmployee = true;
            } else {
                this.showEmployee = false;
            }
        },

        clearFields() {
            this.formData.report_type = '';
            this.formData.start_date =  '';
            this.formData.end_date =  '';
            this.formData.department_id = '',
            this.formData.location_id = '',
            this.formData.start_date = '',
            this.formData.end_date = '',
            this.value = null;
            this.departmentExpenses = false,
            this.showPosition = false,
            this.showEmployee = false,
            this.endDate = false
        },

        save() {
            this.$SHOW_LOADING();
            let startDate = moment(this.formData.start_date).format('YYYY-MM-DD');
            let endDate = moment(this.formData.end_date).format('YYYY-MM-DD');
            if(startDate < endDate) {
                this.formData.start_date = startDate;
                this.formData.end_date = endDate;
                if(this.formData.report_type !== '') {
                    if(this.formData.report_type === 'Payroll Report') {
                    axios.post(this.$BASE_URL + '/dailypayrollexport', this.formData).then((response) => {
                     this.$parent.getReports();
                    this.clearFields();
                    this.$SHOW_MESSAGE('Successfully', 'New Report', 'success');
                    this.$HIDE_LOADING();
                        })
                        .catch((err) => {
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                        });
                    }

                    if(this.formData.report_type === 'Department Total Pay Report') {
                            axios.post(this.$BASE_URL + '/departmentpay', this.formData).then((response) => {
                            this.clearFields();
                            this.$parent.getReports();
                            this.$SHOW_MESSAGE('Successfully', 'New Report', 'success');
                            this.$HIDE_LOADING();
                        })
                        .catch((err) => {
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                        });
                    }

                    if(this.formData.report_type === 'Department Expenses') {
                        if(this.formData.department_id === '') {
                                this.$HIDE_LOADING();
                                this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Please Select Department..', 'error');
                        } else {
                            axios.post(this.$BASE_URL + '/departmentexpenses', this.formData).then((response) => {
                                    this.clearFields();
                                    this.$parent.getReports();
                                    this.$SHOW_MESSAGE('Successfully', 'New Report', 'success');
                                    this.$HIDE_LOADING();
                                    })
                                    .catch((err) => {
                                        this.$HIDE_LOADING();
                                        this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                                });
                        }
                    }

                    if(this.formData.report_type === 'Daily Processing') {
                        axios.post(this.$BASE_URL + '/dailyprocessing', this.formData).then((response) => {
                            this.clearFields();
                            this.$parent.getReports();
                            this.$SHOW_MESSAGE('Successfully', 'New Report', 'success');
                            this.$HIDE_LOADING();
                        })
                        .catch((err) => {
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                        });
                    }

                    if(this.formData.report_type === 'Weekly Payroll') {
                         if(this.formData.department_id === '') {
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Please Select Department..', 'error');
                        } else {
                            axios.post(this.$BASE_URL + '/weeklypayroll', this.formData).then((response) => {
                            this.clearFields();
                            this.$parent.getReports();
                            this.$SHOW_MESSAGE('Successfully', 'New Report', 'success');
                            this.$HIDE_LOADING();
                            })
                            .catch((err) => {
                                this.$HIDE_LOADING();
                                this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                            });
                        }

                    }

                    if(this.formData.report_type === 'Employee Record History') {
                        // /**TODO CHECK ISSET ID VALUE OF EMPLOYEE IF EMPTY OR NOT */
                        if(this.value !== null) {
                            this.formData.employee_id = this.value.id;
                        }
                        axios.post(this.$BASE_URL + '/employeerecordhistory', this.formData).then((response) => {
                            this.clearFields();
                            this.$parent.getReports();
                            this.$SHOW_MESSAGE('Successfully', 'New Report', 'success');
                            this.$HIDE_LOADING();
                            })
                            .catch((err) => {
                                this.$HIDE_LOADING();
                                this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                            });
                    }

                } else {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Please Select Report Type', 'error');
                }
            } else {
                this.$HIDE_LOADING();
                this.$SHOW_MESSAGE('Oops..', 'Something went wrong, The Start Date must less than the End Date', 'error');
            }
        }
    }
}
</script>

<style>

</style>
