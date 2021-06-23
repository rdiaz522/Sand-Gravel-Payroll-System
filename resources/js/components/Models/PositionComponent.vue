<template>
<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <AppTextBox label="Location" v-model="position" placeholder="Enter Location ..."> </AppTextBox>
            </div>
            <div class="form-group">
                <AppDropdown label="Department" v-model="location" :options="locationList" placeholder="Select Department Type"> </AppDropdown>
            </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>  
    </div>
</div>
</template>

<script>

import AppButton from '../AppComponents/AppButton.vue';
import AppTextBox from '../AppComponents/AppTextBox.vue';
import AppDropdown from '../AppComponents/AppDropdown.vue';

export default {
    props:['title','name','event', 'locationList'],
    data() {
        return {
            position:'',
            location:'',
            location_id:''
        }
    },
    components: {
        AppButton,
        AppTextBox,
        AppDropdown
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
            this.$SHOW_LOADING();
            const data = {
                name: this.position,
                location: this.location
            }
            axios.post(this.$BASE_URL + this.$POSITION, data)
                .then((response) => {
                    this.clearFields();
                    this.$parent.getPositions();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'New Position Added!', 'success');
                })
                .catch((error) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });    
        },

        onCancel() {
            this.clearFields();
        },

        clearFields() {
            this.position = '';
            this.location = '';
        }
    }
}
</script>

<style>

</style>
