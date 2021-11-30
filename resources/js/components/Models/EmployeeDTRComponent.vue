<template>
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
               <AppDropdown label="Location" v-model="formData.position_id" :options="position"  placeholder="Select Position"> </AppDropdown>
            </div>
            <div class="form-group">
                 <AppTextBox label="Daily Rate" v-model="formData.daily_rate" placeholder="Enter Daily Rate ..." type="text" autocomplete="on"> </AppTextBox>
            </div>  
            <label>Time In Date Logs</label>
            <div class="form-group">
                <datepicker 
                v-model="formData.log_date"
                :circle="true"
                lang="en"/>

                <vue-timepicker
                    :disabled="disabledTimePicker"
                    :format="format"
                    v-model="startTime"
                    placeholder="Start Time"
                    input-width="240px"
                    input-class="my-awesome-picker"
                    close-on-complete
                    auto-scroll
                    hide-clear-button
                ></vue-timepicker>
            </div>
            <label>Time Out Date Logs</label>
             <div class="form-group">
                <datepicker 
                v-model="formData.log_date2"
                :circle="true"
                lang="en"/>
                  <vue-timepicker
                :disabled="disabledTimePicker"
                :format="format"
                v-model="endTime"
                placeholder="End Time"
                input-width="240px"
                input-class="my-awesome-picker"
                close-on-complete
                auto-scroll
                hide-clear-button
                ></vue-timepicker>
            </div>
                
            <div class="form-group">
                <AppDTRDropdown label="Break Time" v-model="breakTimeValue" :options="breakHour"  placeholder="Select Break Hours"> </AppDTRDropdown>
            </div>

            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div> 
    </div>
</div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import AppButton from '../AppComponents/AppButton.vue';
import AppTextBox from '../AppComponents/AppTextBox.vue';
import AppDTRDropdown from '../AppComponents/AppDTRDropdown.vue';
import AppDropdown from '../AppComponents/AppDropdown.vue';
import VueTimepicker from 'vue2-timepicker';
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
export default {
    props: ['title', 'name', 'event','employees','position','breakHour'],
    data() {
        return {
            formData: {
                employee_id: '',
                position_id: '',
                time_in:'',
                time_out:'',
                break_time:'',
                total_hours:'',
                log_date: moment().format('YYYY-MM-DD'),
                log_date2:moment().format('YYYY-MM-DD'),
                daily_rate: ''
            },
            breakTimeValue:'1:00',
            startTime: {
                    hh: '08',
                    mm: '00',
                    A: 'AM'
                },
            endTime: {
                    hh: '05',
                    mm: '00',
                    A: 'PM'
                },
            value: null,
            format:'hh:mm A',
            timeValue:'',
            disabledTimePicker: true,
            readyToSave: false
        }
    },
    components: {
        AppButton,
        AppTextBox,
        AppDTRDropdown,
        AppDropdown,
        Multiselect,
        VueTimepicker,
        datepicker: VueDatepickerUi
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
    methods: {
        fullName ({ firstname,middlename,lastname }) {
            return `${firstname} ${middlename}, ${lastname}`
            },

        atSelect() {
            this.disabledTimePicker = false;
        },
        
        save() {
            var timeInformat = this.startTime.hh + ':' + this.startTime.mm + ' '+ this.startTime.A;
            var timeOutformat = this.endTime.hh + ':' + this.endTime.mm + ' '+ this.endTime.A;
            this.formData.time_in = timeInformat;
            this.formData.time_out = timeOutformat;
            if(this.formData.time_in !== '' && this.formData.time_out !== '') {
                this.readyToSave = true;
                var breakTime = parseFloat(moment.duration(this.breakTimeValue).asHours());
                const dateTimeIn = `${moment(this.formData.log_date).format('YYYY-MM-DD') +' '+ this.formData.time_in}`;
                const dateTimeOut = `${moment(this.formData.log_date2).format('YYYY-MM-DD') +' '+ this.formData.time_out}`;
                const dateOneObj = new Date(dateTimeIn);
                const dateTwoObj = new Date(dateTimeOut);
                const milliseconds = Math.abs(dateTwoObj - dateOneObj);
                const hours = milliseconds / 36e5;
                var totalHours = hours - breakTime;
                this.formData.total_hours = totalHours.toFixed(2);
                this.formData.break_time = breakTime;
            }
            if(this.readyToSave) {
                this.formData.employee_id = this.value.id;
                this.formData.log_date = moment(this.formData.log_date).format('YYYY-MM-DD');
                this.formData.log_date2 = moment(this.formData.log_date2).format('YYYY-MM-DD');
                this.$SHOW_LOADING();
                axios.post(this.$BASE_URL + this.$EMPLOYEETIMELOGS, this.formData)
                    .then((response) => {
                        this.clearFields();
                        this.$parent.getTimeLogs();
                        this.$HIDE_LOADING();
                        this.$SHOW_MESSAGE('Successfully', 'New DTR Added!', 'success');
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
        },

        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.formData.start_time = '';
            this.formData.end_time = '';
            this.formData.break_time = '';
            this.formData.total_hours = '';
            this.formData.log_date = moment().format('YYYY-MM-DD'),
            this.formData.log_date2 = moment().format('YYYY-MM-DD'),
            this.breakTimeValue = '1:00';
            this.disabledTimePicker = true;
            this.value = null;
            this.startTime = {
                    hh: '08',
                    mm: '00',
                    A: 'AM'
                };
            this.endTime = {
                    hh: '05',
                    mm: '00',
                    A: 'PM'
            }
        },
    }

}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>

</style>