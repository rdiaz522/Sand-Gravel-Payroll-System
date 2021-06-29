<template>
 <div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
        <label for="">Break Time</label>
            <div class="form-group">
                <vue-timepicker
                :format="format"
                v-model="hour"
                placeholder="Break Time"
                input-width="250px"
                input-class="my-awesome-picker"
                close-on-complete
                auto-scroll
                hide-clear-button
                @change="changeHandler"
                ></vue-timepicker>
            </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
    </div>
</div>
</template>

<script>
import AppButton from '../../AppComponents/AppButton.vue';
import AppTextBox from '../../AppComponents/AppTextBox.vue';
import VueTimepicker from 'vue2-timepicker';
export default {
props: ['title', 'name', 'event'],
    data() {
        return {
            hour:  {
                H: '0',
                mm: '00',
            },
            value: null,
            format:'H:mm',
            timeValue:'',
            disabledTimePicker: true
        }
    },
    components: {
        AppButton,
        AppTextBox,
        VueTimepicker
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
        save() {
            if(this.hour.H === '0' && this.hour.mm === '00') {
                 this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Must not 00:00 Time', 'error');
            } else {
                this.$SHOW_LOADING();
                const data = {
                    name: this.hour.H + ':' + this.hour.mm
                }
                axios.post(this.$BASE_URL + this.$BREAKHOURS, data)
                .then((response) => {
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'New Break Time Added!', 'success');
                })
                .catch((error) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
            }
        },

        onCancel() {
            this.location = '';
        },

        clearFields() {
            this.location = '';
        },

        changeHandler() {
            console.log('TEST');
        }
    }
}
</script>

<style>

</style>