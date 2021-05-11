<template>
    <div class="container">
        <div class="row justify-content-center">
            <h1>Welcome To IBCS-PRiMAX Software (BD) Ltd.</h1>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Employee Transactions
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="employeeTransactions.length > 0">
                                    <tr v-for="(employeeTransaction, index) in employeeTransactions" :key="'emp-'+index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ employeeTransaction.employee.employee_id }}</td>
                                        <td>{{ employeeTransaction.employee.name }}</td>
                                        <td>Tk.{{ employeeTransaction.amount }}</td>
                                        <td>{{ setDate(employeeTransaction.created_at) }}</td>
                                    </tr>

                                    <pagination :data="employeeTransactionPag" @pagination-change-page="empTransactions"></pagination>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="5" align="center">Data not found</td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Company Transactions
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transfer type</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="companyTransactions.length > 0">
                                    <tr v-for="(companyTransaction, index) in companyTransactions" :key="'com-'+index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ companyTransaction.transfer_type }}</td>
                                        <td>Tk.{{ companyTransaction.amount }}</td>
                                        <td>{{ companyTransaction.note }}</td>
                                        <td>{{ setDate(companyTransaction.created_at) }}</td>
                                    </tr>
                                    <pagination :data="companyTransactionPag" @pagination-change-page="comTransactions"></pagination>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="5" align="center">Data not found</td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import pagination from 'laravel-vue-pagination'
    import axios from "axios";
    import moment from 'moment'

    export default {
        components: {
            pagination
        },
        data() {
            return {
                companyTransactions: [],
                companyTransactionPag: null,
                employeeTransactions: [],
                employeeTransactionPag: null,
            };
        },

        mounted() {
            this.empTransactions();
            this.comTransactions();
        },

        methods: {
            empTransactions(page = 1) {
                axios.get(this.Api.transaction+"/employee?page="+page,{ headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.employeeTransactions = response.data.data;
                            this.employeeTransactionPag = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            comTransactions(page = 1) {
                axios.get(this.Api.transaction+"/company?page="+page,{ headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.companyTransactions = response.data.data;
                            this.companyTransactionPag = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            setDate(date) {
                return  moment(date).format('DD MMMM, YYYY hh:mm A')
            }
        }
    }
</script>


