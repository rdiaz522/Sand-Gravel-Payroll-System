<template>
    <div>
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            </div>
            <datatable
                title="Employee List"
                :columns="columns" 
                :rows="employeeList"
                :sortable="false"
                :clickable="false"
                :printable="false"
                v-on:row-click="onEdit"
                :serverSearch="true"
                >

                <th slot="thead-tr">
                    Actions
                  </th>
                  <template slot="tbody-tr" slot-scope="props">
                    <td>
                        <button class="btn btn-primary btn-sm" v-on:click="(e) => onEdit(props.row, e)">
                            Edit
                          </button>
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
import DataTable from "vue-materialize-datatable";
    export default {
        props:['employeeList'],
        data() {
            return {
                columns: [{
                        label: 'ID',
                        field: 'id'
                    },
                    {
                        label: 'Employee Name',
                        field: 'employee_fullname',
                    },
                    {
                        label: 'Employee Type',
                        field: 'employeeTypeName'
                    },
                ],
            }
        },
        components: {
            "datatable": DataTable
        },
        methods: {
            onEdit(props,row,e) {
                this.$emit('editEmployee',props);
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
                        axios.delete(this.$BASE_URL + this.$EMPLOYEES + `/${props.id}`).then((response) =>  {
                            this.$parent.getEmployees();
                            this.$parent.showEmployeeEdit = false;
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
               
            }
        }
    }
</script>