<template>
    <div>
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            </div>
            <datatable
                title="Report List"
                :columns="columns" 
                :rows="reportList"
                :sortable="false"
                :clickable="false"
                :printable="false"
                >

                <th slot="thead-tr">
                    Actions
                  </th>
                  <template slot="tbody-tr" slot-scope="props">
                    <td>
                    <button class="btn btn-success btn-sm" v-on:click="(e) => onGenerateReport(props.row, e)">
                       Download Report
                      </button>
                      <button class="btn btn-success btn-sm" v-on:click="(e) => onGeneratePayslip(props.row, e)">
                        Payslip
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
        props:['reportList'],
        data() {
            return {
                columns: [{
                        label: 'ID',
                        field: 'id'
                    },
                    {
                        label: 'Report Type',
                        field: 'report_type',
                    },
                    {
                        label: 'Start Date',
                        field: 'start_date'
                    },
                    {
                        label: 'End Date',
                        field: 'end_date'
                    },
                ],
            }
        },
        components: {
            "datatable": DataTable
        },
        methods: {
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
                        axios.delete(this.$BASE_URL + this.$REPORTS + `/${props.id}`).then((response) =>  {
                            this.$parent.getReports();
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

            onGenerateReport(props,row,e) {
                    this.$SHOW_LOADING();
                    axios.get(this.$BASE_URL + this.$REPORTS + `/${props.id}`).then((response) =>  {
                        window.open(this.$BASE_URL + this.$REPORTS + `/${props.id}`);
                        this.$HIDE_LOADING();
                        this.$WARNING_MESSAGE.fire(
                        'Downloaded!',
                        'Data has been download.',
                        'success'
                        )
                    })
                    .catch((err) =>{
                        this.$HIDE_LOADING();
                        this.$SHOW_MESSAGE('Oops..','Something went wrong, Call the Administrator','error');
                    });
            },
            onGeneratePayslip(props,row,e) {
                 this.$SHOW_LOADING();
                    axios.get(this.$BASE_URL + '/generatepayslip' + `/${props.id}`).then((response) =>  {
                        if(response.data !== 0) {
                            window.open(this.$BASE_URL + '/generatepayslip' + `/${props.id}`);
                        }
                        this.$HIDE_LOADING();
                        this.$WARNING_MESSAGE.fire(
                        'Downloaded!',
                        'Data has been download.',
                        'success'
                        )
                    })
                    .catch((err) =>{
                        this.$HIDE_LOADING();
                        this.$SHOW_MESSAGE('Oops..','Something went wrong, Call the Administrator','error');
                    });
            }
        }
    }
</script>