<template>
    <div>
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            </div>
            <datatable
                title="Cash Advance List"
                :columns="columns" 
                :rows="cashAdvanceList"
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
        props:['cashAdvanceList'],
        data() {
            return {
                columns: [
                    {
                        label: 'Employee',
                        field: 'employee_fullname',
                    },
                    {
                        label: 'Cash Advance',
                        field: 'cash_advance'
                    },
                    {
                        label: 'Description',
                        field: 'description'
                    },
                    {
                        label: 'Date of Cash Advance',
                        field: 'cash_advance_date'
                    },
                    
                ],
            }
        },
        components: {
            "datatable": DataTable
        },
        methods: {
            onEdit(props,row,e) {
                this.$emit('editCashAdvance',props);
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
                        axios.delete(this.$BASE_URL + this.$CASHADVANCEDEDUCTION + `/${props.id}`).then((response) =>  {
                            this.$parent.getCashAdvance();
                            this.$parent.showCashAdvanceEdit = false;
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