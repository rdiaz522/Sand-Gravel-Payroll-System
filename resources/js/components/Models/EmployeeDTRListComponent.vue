<template>
    <div>
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            </div>
            <datatable
                title="Employee Daily Time Record List"
                :columns="columns" 
                :rows="timelogList"
                :sortable="false"
                :clickable="false"
                :printable="false"
                v-on:row-click="onEdit"
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
        props:['timelogList'],
        data() {
            return {
                columns: [
                    {
                        label: 'Employee',
                        field: 'employee_fullname',
                    },
                    {
                        label: 'Department',
                        field: 'departmentName'
                    },
                    {
                        label: 'Daily Rate',
                        field: 'daily_rate'
                    },
                    {
                        label: 'Time In',
                        field: 'time_in'
                    },
                     {
                        label: 'Time Out',
                        field: 'time_out'
                    },
                     {
                        label: 'Total Hours',
                        field: 'total_hours'
                    },
                    {
                        label: 'Date of Log',
                        field: 'log_date'
                    },
                    
                ],
            }
        },
        components: {
            "datatable": DataTable
        },
        methods: {
            onEdit(props,row,e) {
                this.$emit('editTimeLogs',props);
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
                        axios.delete(this.$BASE_URL + this.$EMPLOYEETIMELOGS + `/${props.id}`).then((response) =>  {
                            this.$parent.getTimeLogs();
                            this.$parent.showDTREdit = false;
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