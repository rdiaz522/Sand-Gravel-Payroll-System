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
                 <AppTextBox label="Daily Rate" v-model="formData.daily_rate" placeholder="Enter Daily Rate ..." type="number"> </AppTextBox>
            </div>
            <label>Date Logs</label>
            <div class="form-group">
                <datepicker 
                v-model="formData.log_date"
                :circle="true"
                lang="en"/>
            </div>

            <div class="form-group">
                <label for="">Time Logs</label>
                <div>   
                    <vue-timepicker
                    :disabled="disabledTimePicker"
                    :format="format"
                    v-model="startTime"
                    placeholder="Start Time"
                    input-width="250px"
                    input-class="my-awesome-picker"
                    close-on-complete
                    auto-scroll
                    hide-clear-button
                ></vue-timepicker>
                to
                <vue-timepicker
                :disabled="disabledTimePicker"
                :format="format"
                v-model="endTime"
                placeholder="End Time"
                input-width="250px"
                input-class="my-awesome-picker"
                close-on-complete
                auto-scroll
                hide-clear-button
                ></vue-timepicker>
                </div>
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
                log_date: moment().format('D MMM, YYYY'),
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
                var startTime = moment(this.formData.time_in, "hh:mm A");
                var endTime = moment(this.formData.time_out, "hh:mm A");
                var duration = moment.duration(endTime.diff(startTime));
                var hours = parseInt(duration.asHours());
                var minutes = parseInt(duration.asMinutes())%60;
                var totalMinutesandHours = hours + ':' + minutes;
                var breakTime = parseFloat(moment.duration(this.breakTimeValue).asHours());
                var convertTotalHour = parseFloat(moment.duration(totalMinutesandHours).asHours()).toFixed(1);
                var totalHours = convertTotalHour - breakTime;
                this.formData.total_hours = totalHours;
                this.formData.break_time = breakTime;
                this.formData.employee_id = this.value.id;
            }

            if(this.readyToSave) {
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
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
            }
        },

        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.formData.position_id = '';
            this.formData.employee_id = '';
            this.formData.start_time = '';
            this.formData.end_time = '';
            this.formData.break_time = '';
            this.formData.total_hours = '';
            this.formData.log_date = moment().format('D MMM, YYYY'),
            this.formData.daily_rate = '';
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