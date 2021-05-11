<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div v-if="message" class="alert alert-secondary">
                    {{ message }}
                </div>
                <div class="card">
                    <div class="card-header">
                        Salary summary
                        <router-link class="btn btn-sm btn-info float-right" :to="{name: 'Dashboard'}">Back</router-link>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="paymentSalary()">
                            <div class="alert alert-success" role="alert">
                                Employee payment summary. <br> <h4>Company Balance Tk.{{ companyBalance }}</h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Emp ID</th>
                                        <th>Name</th>
                                        <th>Grade/Rank/Basic</th>
                                        <th>House rent - 20%</th>
                                        <th>Medical allowance - 15%</th>
                                        <th>Total Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-if="employees.length > 0">
                                        <tr v-for="(employee, index) in employees" :key="index">
                                            <td>{{ employee.employee_id }}</td>
                                            <td>{{ employee.name }}</td>
                                            <td>{{ employee.grade.title + " - Tk."+employee.grade.salary }}</td>
                                            <td>Tk. {{ getAllPercentage(employee.grade.salary, 20) }}</td>
                                            <td>Tk. {{ getAllPercentage(employee.grade.salary, 15) }}</td>
                                            <td>
                                                {{ getTotalAmount(employee.grade.salary, getAllPercentage(employee.grade.salary, 20), getAllPercentage(employee.grade.salary, 15)) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" align="right">
                                                <strong>Total Payable salary amount</strong>
                                            </td>
                                            <td><strong>Tk. {{ totalBasic }}</strong></td>
                                            <td><strong>Tk. {{ totalHouseRent }}</strong></td>
                                            <td><strong>Tk. {{ totalMedical }}</strong></td>
                                            <td><strong>Tk. {{ totalSalary }}</strong></td>
                                        </tr>

                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="6" align="center">Data not found</td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>

                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" @change="updateSalary()" type="checkbox" v-model="salary.is_add_more" value="1" id="is_add_more">
                                        <label class="form-check-label" for="is_add_more">
                                            Do you want add more company balance.
                                        </label>
                                    </div>

                                    <div v-if="salary.is_add_more === true" class="form-group">
                                        <label for="amount"></label>
                                        <input type="number" min="0" style="width: 250px;" @keyup="updateSalary()" v-model="salary.amount" step="0.01" max="9999999999" class="form-control" id="amount" placeholder="Add amount">
                                        <span v-if="errors.amount" class="text-danger">
                                            <small>{{ errors.amount[0] }}</small>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="submit" :disabled="companyBalance <= totalSalary" class="btn btn-primary">
                                    <span v-if="loader === true" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Payment
                                </button>
                            </div>
                        </form>
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
                errors: [],
                employees: [],
                totalBasic: 0,
                totalHouseRent: 0,
                totalMedical: 0,
                totalSalary: 0,
                companyBalance: 0,
                tempCompanyBalance: 0,
                salary: {
                    is_add_more: '',
                    amount: '',
                }

            };
        },

        mounted() {
            this.getEmployees();
            this.getSalarySummary();
        },

        methods: {
            getEmployees() {
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

            getSalarySummary() {
                axios.get(this.Api.salary+"/summary",{ headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.totalBasic = response.totalBasic;
                            this.totalHouseRent = response.totalHouseRent;
                            this.totalMedical = response.totalMedical;
                            this.totalSalary = response.totalSalary;
                            this.totalSalary = response.totalSalary;
                            this.companyBalance = response.companyBalance;
                            this.tempCompanyBalance = response.companyBalance;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            paymentSalary() {
                this.loader = true;
                axios.post(this.Api.salary+'/payment-salary', this.salary,{headers: this.$globalHelper.authHeader()})
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.loader = false;
                            this.message = response.message;
                            this.getEmployees();
                            this.getSalarySummary();
                            this.salary = {
                                is_add_more: '',
                                amount: '',
                            }
                        } else {
                            this.loader = false;
                            this.errors = response.errors;
                            this.message = response.message;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loader = false;
                    });
            },

            updateSalary() {
                if (this.salary.amount > 0) {
                    this.companyBalance = parseFloat(this.tempCompanyBalance) + parseFloat(this.salary.amount);
                } else {
                    this.getSalarySummary();
                }
                if (this.salary.is_add_more === false) {
                    this.salary.amount = '';
                    this.getSalarySummary();
                }
            },

            getAllPercentage(amount, percentage) {
                return (parseFloat(amount) / 100) * parseFloat(percentage)
            },

            getTotalAmount(salary, house_rent, medical_allowance) {
                return parseFloat(salary) + parseFloat(house_rent) + parseFloat(medical_allowance);
            }

        }
    }
</script>


