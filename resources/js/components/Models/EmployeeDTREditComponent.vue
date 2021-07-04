<template>
<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <h3>{{employeeFullname}}</h3>

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
                    :format="format"
                    v-model="formData.time_in"
                    placeholder="Start Time"
                    input-width="250px"
                    input-class="my-awesome-picker"
                    close-on-complete
                    auto-scroll
                    hide-clear-button
                ></vue-timepicker>
                to
                <vue-timepicker
                :format="format"
                v-model="formData.time_out"
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
            <AppButton v-on:edit="edit" :btn-name="name" btn-method="edit" v-if="isOnEdit"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
    </div>
</div>
</template>

<script>
import AppButton from '../AppComponents/AppButton.vue';
import AppTextBox from '../AppComponents/AppTextBox.vue';
import AppDTRDropdown from '../AppComponents/AppDTRDropdown.vue';
import AppDropdown from '../AppComponents/AppDropdown.vue';
import VueTimepicker from 'vue2-timepicker';
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';
export default {
    props: ['title', 'name', 'event','timeLogsEdit','position', 'breakHour'],
    data() {
        return {
            formData: {
                id: '',
                position_id: '',
                time_in:'',
                time_out:'',
                break_time:'',
                total_hours:'',
                log_date: '',
                daily_rate: ''
            },
            breakTimeValue:'',
            format:'hh:mm A',
            timeValue:'',
            disabledTimePicker: true,
            readyToSave: false,
            employeeFullname:''
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
        timeLogsEdit: function (newVal) {
            console.log(newVal);
            this.formData.id = newVal.id;
            this.formData.position = newVal.position_id;
            this.formData.daily_rate = newVal.daily_rate;
            this.formData.time_in = newVal.time_in;
            this.formData.time_out = newVal.time_out;
            this.breakTimeValue = this.parse(newVal.break_time);
            this.formData.total_hours = newVal.total_hours;
            this.formData.log_date = newVal.log_date;
            this.employeeFullname = newVal.employee_fullname
        }
    },
    components: {
        AppTextBox,
        AppDropdown,
        AppButton,
        AppDTRDropdown,
        VueTimepicker,
        datepicker: VueDatepickerUi
    },
    methods: {
        fullName ({ firstname,middlename,lastname }) {
            return `${firstname} ${middlename}, ${lastname}`
            },

        atSelect() {
            this.disabledTimePicker = false;  
        },
        save() {
            this.$SHOW_LOADING();
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
                this.formData.break_time = breakTime
            }
            const config = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            axios.put(this.$BASE_URL + this.$EMPLOYEETIMELOGS + `/${this.formData.id}`, this.formData, config)
                .then((response) => {
                    this.$parent.getTimeLogs();
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'New DTR Added!', 'success');
                })
                .catch((error) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });

        },

        parse(num) {
            return ('0' + Math.floor(num) % 24).slice(-2) + ':' + ((num % 1)*60 + '0').slice(0, 2);
        },

        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.formData.position_id = '';
            this.formData.id = '';
            this.formData.start_time = '';
            this.formData.end_time = '';
            this.formData.break_time = '';
            this.formData.total_hours = '';
            this.formData.log_date = '';
            this.formData.daily_rate = '';
            this.breakTimeValue = '';
            this.$parent.showDTREdit = false;
        },
    }
}
</script>
