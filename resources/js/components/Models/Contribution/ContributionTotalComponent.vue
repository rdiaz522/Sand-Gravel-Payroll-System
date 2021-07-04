<template>
<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <h3>{{fullname}}</h3>
            <h4>Total SSS:  <strong>₱{{total_sss}}</strong></h4>
            <h4>Total PAGIBIG:  <strong>₱{{total_pagibig}}</strong></h4>
            <h4>Total PHILHEALTH:  <strong>₱{{total_philhealth}}</strong></h4>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Close</button>
        </div>
    </div>
</div>
</template>

<script>
import AppTextBox from '../../AppComponents/AppTextBox.vue';
import AppDropdown from '../../AppComponents/AppDropdown.vue';
import AppButton from '../../AppComponents/AppButton.vue';
export default {
    props: ['title', 'name', 'event','totalContribution'],
    data() {
        return {
            total_sss:'',
            total_pagibig: '',
            total_philhealth: '',
            fullname:''
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
        totalContribution: function (newVal) {
            this.total_sss = newVal.sss;
            this.total_pagibig = newVal.pagibig;
            this.total_philhealth = newVal.philhealth;
            this.fullname = newVal.fullname;
        }
    },
    components: {
        AppTextBox,
        AppDropdown,
        AppButton,
    },
    methods: {
        onCancel() {
            this.clearFields();
        },
        clearFields() {
            this.sss = '';
            this.pagibig = '';
            this.philhealth = '';
            this.fullname = '';
            this.$parent.showTotalContribution = false;
        }
    }
}
</script>
