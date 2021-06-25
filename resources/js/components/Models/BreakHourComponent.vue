<template>
 <div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <AppTextBox label="Break Hour" v-model="hour" placeholder="Enter Break Hour ..."> </AppTextBox>
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

export default {
props: ['title', 'name', 'event'],
    data() {
        return {
            hour: ''
        }
    },
    components: {
        AppButton,
        AppTextBox
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
                name: this.location,
            }
            axios.post(this.$BASE_URL + this.$LOCATION, data)
                .then((response) => {
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$parent.getLocations();
                    this.$SHOW_MESSAGE('Successfully', 'New Location Added!', 'success');
                })
                .catch((error) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });
        },

        onCancel() {
            this.location = '';
        },

        clearFields() {
            this.location = '';
        }
    }
}
</script>

<style>

</style>