<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div v-if="message" class="alert alert-secondary">
                    {{ message }}
                </div>
                <div class="card">
                    <div class="card-header">
                        Employees
                        <router-link class="btn btn-sm btn-info float-right" :to="{name: 'EmployeeCreate'}">Add Employees</router-link>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp ID</th>
                                    <th>Name</th>
                                    <th>Grade</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Bank A/C</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="employees.length > 0">
                                    <tr v-for="(employee, index) in employees" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ employee.employee_id }}</td>
                                        <td>{{ employee.name }}</td>
                                        <td>{{ employee.grade.title }}</td>
                                        <td>{{ employee.address }}</td>
                                        <td>{{ employee.mobile_number }}</td>
                                        <td>{{ employee.account.account_number }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenu21" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu21">
                                                    <router-link class="dropdown-item" :to="{name: 'EmployeeEdit', params: {id: employee.id}}">Edit</router-link>
                                                    <button class="dropdown-item" @click="viewData(employee.id)" type="button">View</button>
                                                    <button class="dropdown-item" @click="deleteData(employee.id)" type="button">Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="8" align="center">Data not found</td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="empLoyeeModal" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Employee Details | {{ employeeData.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Name</th>
                                        <td>:</td>
                                        <td>{{ employeeData.name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Emp ID</th>
                                        <td>:</td>
                                        <td>{{ employeeData.name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Grade/Rank</th>
                                        <td>:</td>
                                        <td><span v-if="employeeData.grade">{{ employeeData.grade.title+" - Salary Tk."+employeeData.grade.salary }}</span></td>
                                    </tr>

                                    <tr>
                                        <th>Mobile Number</th>
                                        <td>:</td>
                                        <td>{{ employeeData.mobile_number }}</td>
                                    </tr>

                                    <tr>
                                        <th>Address</th>
                                        <td>:</td>
                                        <td>{{ employeeData.address }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table v-if="employeeData.account" class="table table-borderless">
                                    <tr>
                                        <th>Account type</th>
                                        <td>:</td>
                                        <td class="text-capitalize">{{ employeeData.account.account_type }}</td>
                                    </tr>

                                    <tr>
                                        <th>Account name</th>
                                        <td>:</td>
                                        <td>{{ employeeData.account.account_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Account number</th>
                                        <td>:</td>
                                        <td>{{ employeeData.account.account_number }}</td>
                                    </tr>

                                    <tr>
                                        <th>Bank name</th>
                                        <td>:</td>
                                        <td>{{ employeeData.account.bank_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Branch name</th>
                                        <td>:</td>
                                        <td>{{ employeeData.account.branch_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Current balance</th>
                                        <td>:</td>
                                        <td>Tk.{{ employeeData.account.current_balance }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                loader: false,
                message: '',
                employees: [],
                employeeData: '',
            };
        },

        mounted() {
            this.getDatas();
        },

        methods: {
            getDatas() {
                axios.get(this.Api.employee,{ headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.employees = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            viewData(id) {
                axios.get(this.Api.employee+"/"+id, { headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.employeeData = response.data;
                            $('#empLoyeeModal').modal("show");
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            deleteData(id) {
                axios.delete(this.Api.employee+"/"+id, { headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.message = response.message;
                            this.getDatas();
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
        }


    }
</script>


