<template>
<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{title}}</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <AppTextBox label="Department" v-model="location" placeholder="Enter Department ..."> </AppTextBox>
            </div>
            <AppButton v-on:save="save" :btn-name="name" btn-method="save" v-if="isOnsave"></AppButton>
            <button @click="onCancel" class="btn btn-secondary float-right mr-2">Cancel</button>
        </div>
            <datatable
                title="Department List"
                :columns="columns" 
                :rows="locationList"
                :sortable="false"
                :clickable="false"
                :printable="false"
                >

                <th slot="thead-tr">
                    Actions
                  </th>
                  <template slot="tbody-tr" slot-scope="props">
                    <td>
                      <button class="btn btn-danger btn-sm" v-on:click="(e) => onDelete(props.row, e)">
                        Delete
                      </button>
                    </td>
                  </template>
            </datatable> 

    </div>
</div>
</template>

<script>
import AppButton from '../../AppComponents/AppButton.vue';
import AppTextBox from '../../AppComponents/AppTextBox.vue';
import DataTable from "vue-materialize-datatable";
export default {
    props: ['title', 'name', 'event', 'locationList'],
    data() {
        return {
            location: '',
            columns: [
                    {
                        label: 'Department Name',
                        field: 'name'
                    },
                ],
        }
    },
    components: {
        AppButton,
        AppTextBox,
        "datatable": DataTable
    },
    computed: {
        isOnsave: function () {
            if (this.event === 'save') {
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

        onDelete(props,row,e) {
                 this.$WARNING_MESSAGE.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.isConfirmed) {
                        this.$SHOW_LOADING();
                        axios.delete(this.$BASE_URL + this.$LOCATION + `/${props.id}`).then((response) =>  {
                            this.clearFields();
                            this.$parent.getPositions();
                            this.$parent.showExpensesEdit = false;
                            this.$HIDE_LOADING();
                            this.$WARNING_MESSAGE.fire(
                            'Deleted!',
                            'Data has been deleted.',
                            'success'
                            )
                        })
                        .catch((err) =>{
                            this.$HIDE_LOADING();
                            this.$SHOW_MESSAGE('Oops..','Something went wrong, Call the Administrator','error');
                        });
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === this.$SWAL.DismissReason.cancel
                    ) {
                        this.$WARNING_MESSAGE.fire(
                        'Cancelled',
                        'Data deleting cancelled :)',
                        'error'
                        )

                        return false;
                    }
                })
               
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
