<template>
<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <AppTextBox label="Position" v-model="position" placeholder="Enter Position ..."> </AppTextBox>
            </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
        </div>  
    </div>
</div>
</template>

<script>

import AppButton from '../AppComponents/AppButton.vue';
import AppTextBox from '../AppComponents/AppTextBox.vue';

export default {
    props:['title','name','event'],
    data() {
        return {
            position:''
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

        },

        edit() {

        }
    }
}
</script>

<style>

</style>
