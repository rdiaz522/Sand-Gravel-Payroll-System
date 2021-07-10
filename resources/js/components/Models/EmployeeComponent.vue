<template>
<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="form-group">
                <AppTextBox label="First Name" v-model="firstname" placeholder="Employee First Name.."> </AppTextBox>
            </div>
            <div class="form-group">
                <AppTextBox label="M.I" v-model="middlename" placeholder="Employee Middle Initial.." maxlength="1"> </AppTextBox>
            </div>
            <div class="form-group">
                <AppTextBox label="Last Name" v-model="lastname" placeholder="Employee Last Name.."> </AppTextBox>
            </div>
            <div class="form-group">
                <AppDropdown label="Employee Type" v-model="employee_type_id" :options="employeeTypeList" placeholder="Select Employee Type"> </AppDropdown>
            </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <AppButton v-on:edit="edit" :btn-name="name" btn-method="edit" v-if="isOnEdit"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
    </div>
</div>
</template>

<script>
import AppTextBox from '../AppComponents/AppTextBox.vue';
import AppDropdown from '../AppComponents/AppDropdown.vue';
import AppButton from '../AppComponents/AppButton.vue';

export default {
    props: ['title', 'name', 'event', 'employeeTypeList'],
    data() {
        return {
            firstname: '',
            middlename: '',
            lastname: '',
            employee_type_id: '',
            isEdit: false,
            isCreate: false,
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
    components: {
        AppTextBox,
        AppDropdown,
        AppButton
    },
    methods: {
        save() {
            this.$SHOW_LOADING();
            const data = {
                firstname: this.firstname,
                middlename: this.middlename,
                lastname: this.lastname,
                employee_type_id: this.employee_type_id
            }
            axios.post(this.$BASE_URL + this.$EMPLOYEES, data)
                .then((response) => {
                    this.$parent.getEmployees();
                    this.clearFields();
                    this.$HIDE_LOADING();
                    this.$SHOW_MESSAGE('Successfully', 'New Employee Added', 'success');
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
            this.firstname = '';
            this.middlename = '';
            this.lastname = '';
            this.employee_type_id = '';
        }
    }
}
</script>
