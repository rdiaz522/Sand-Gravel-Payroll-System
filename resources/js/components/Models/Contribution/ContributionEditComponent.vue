<template>
<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            {{totalContribution}}
            <h4>{{fullName}}</h4>
            
            <div class="form-group">
                <AppTextBox label="SSS" v-model="sss" placeholder="Enter SSS ..."> </AppTextBox>
            </div>

            <div class="form-group">
                <AppTextBox label="PAGIBIG" v-model="pagibig" placeholder="Enter Pagibig ..."> </AppTextBox>
            </div>

            <div class="form-group">
                <AppTextBox label="PHILHEALTH" v-model="philhealth" placeholder="Enter Philhealth ..."> </AppTextBox>
            </div>

            <label>Contribution Date</label>

            <div class="form-group">
                <datepicker 
                v-model="contribution_date"
                :circle="true"
                lang="en"/>
            </div>

            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <AppButton v-on:edit="edit" :btn-name="name" btn-method="edit" v-if="isOnEdit"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
    </div>
</div>
</template>

<script>
import AppTextBox from '../../AppComponents/AppTextBox.vue';
import AppDropdown from '../../AppComponents/AppDropdown.vue';
import AppButton from '../../AppComponents/AppButton.vue';
import VueDatepickerUi from 'vue-datepicker-ui';
export default {
    props: ['title', 'name', 'event','contributionEdit','totalContribution'],
    data() {
        return {
            id:'',
            sss: '',
            pagibig: '',
            philhealth: '',
            contribution_date: '',
            fullName:''
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
        contributionEdit: function (newVal) {
            this.id = newVal.id;
            this.sss = newVal.sss;
            this.pagibig = newVal.pagibig;
            this.philhealth = newVal.philhealth;
            this.contribution_date = newVal.contribution_date;
            this.fullName = newVal.employee_fullname
        }
    },
    components: {
        AppTextBox,
        AppDropdown,
        AppButton,
        datepicker: VueDatepickerUi
    },
    methods: {
        save() {
            this.$SHOW_LOADING();
            const data = {
                sss: this.sss,
                pagibig: this.pagibig,
                philhealth: this.philhealth,
                contribution_date: this.contribution_date,
            }
            const config = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            axios.put(this.$BASE_URL + this.$CONTRIBUTION + `/${this.id}`, data, config)
                .then((response) => {
                    this.$parent.getContributions();
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'Contribution Updated!', 'success');
                })
                .catch((error) => {
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Oops..', 'Something went wrong, Call the Administrator', 'error');
                });

        },

        edit() {

        },

        onCancel() {
            this.clearFields();
        },
        clearFields() {
            this.sss = '';
            this.pagibig = '';
            this.philhealth = '';
            this.contribution_date = '';
            this.fullName = '';
        }
    }
}
</script>
