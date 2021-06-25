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
                        placeholder="Select employees" 
                        :options="employees"
                        @select="clickInput" 
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
                title="Selected Circle"
                lang="en"/>
            </div>

            <div class="form-group">
                <label for="">Time Logs</label>
                <div>   
                    <vue-timepicker
                    :disabled="disabledTimePicker"
                :format="format"
                v-model="formData.start_time"
                placeholder="Start Time"
                input-width="250px"
                input-class="my-awesome-picker"
                ></vue-timepicker>
                to
                <vue-timepicker
                :disabled="disabledTimePicker"
                :format="format"
                v-model="formData.end_time"
                placeholder="End Time"
                input-width="250px"
                input-class="my-awesome-picker"
                ></vue-timepicker>
                </div>
            </div>

            <div class="form-group">
                <AppDropdown label="Break Hour" v-model="formData.break_time" :options="breakHour"  placeholder="Select Break Hours"> </AppDropdown>
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
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue';
import VueDatepickerUi from 'vue-datepicker-ui';
import moment from 'moment';

export default {
    props: ['title', 'name', 'event','employees','position','breakHour'],
    data() {
        return {
            formData: {
                employee_id: '',
                position_id: '',
                start_time: '',
                end_time: '',
                break_time:'',
                total_hours:'',
                log_date: '',
                daily_rate: ''
            },
            employeeName: '',
            value: null,
            format:'hh:mm A',
            timeValue:'',
            disabledTimePicker: true
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

        clickInput() {
            this.disabledTimePicker = false;  
        },
        save() {
            var startTime = moment(this.formData.start_time, "hh:mm A");
            var endTime = moment(this.formData.end_time, "hh:mm A");
            var duration = moment.duration(endTime.diff(startTime));
            var hours = parseInt(duration.asHours());
            var minutes = parseInt(duration.asMinutes())%60;
            var totalMinutesandHours = hours + ':' + minutes;
            var breakTime = parseFloat( moment.duration(this.formData.break_time).asHours());
            var convertTotalHour = parseFloat(moment.duration(totalMinutesandHours).asHours()).toFixed(1);
            var totalHours = convertTotalHour - breakTime;
            this.formData.total_hours = totalHours;
            this.formData.employee_id = this.value.id;
            console.log(this.formData);
            // this.$SHOW_LOADING();
            // const data = {
            //     name: this.location,
            // }
            // axios.post(this.$BASE_URL + this.$LOCATION, data)
            //     .then((response) => {
            //         this.clearFields();
            //         this.$HIDE_LOADING();
            //         this.$parent.getLocations();
            //         this.$SHOW_MESSAGE('Successfully', 'New Location Added!', 'success');
            //     })
            //     .catch((error) => {
            //         this.$HIDE_LOADING();
            //         this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
            //     });
        },

        onCancel() {
            this.test();
            this.location = '';
        },

        clearFields() {
            this.location = '';
        },
  
    }

}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>

</style>