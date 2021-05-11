<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div v-if="message" class="alert alert-secondary">
                    {{ message }}
                </div>
                <form @submit.prevent="dataUpdate">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Employee Information
                                </div>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="grade">Grade/Rank</label>
                                        <select v-model="employee.grade" class="form-control" id="grade">
                                            <option value="" disabled selected hidden>Select Grade</option>
                                            <option v-for="(grade, index) in grades" :value="grade.id">{{ grade.title+" - Tk."+grade.salary }}</option>
                                        </select>
                                        <span v-if="errors.grade" class="text-danger">
                                            <small>{{ errors.grade[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" v-model="employee.name" class="form-control" id="name" placeholder="Employee name">
                                        <span v-if="errors.name" class="text-danger">
                                            <small>{{ errors.name[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">+88</div>
                                            </div>
                                            <input type="text" v-model="employee.mobile_number" class="form-control" id="mobile_number" placeholder="Mobile number">
                                        </div>
                                        <span v-if="errors.mobile_number" class="text-danger">
                                            <small>{{ errors.mobile_number[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea type="text" v-model="employee.address" class="form-control" id="address" placeholder="Employee address"></textarea>
                                        <span v-if="errors.address" class="text-danger">
                                            <small>{{ errors.address[0] }}</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Employee Bank Information
                                    <router-link class="btn btn-sm btn-info float-right" :to="{name: 'Employee'}">Back</router-link>
                                </div>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="account_type">Account Type</label>
                                        <select v-model="employee.account_type" class="form-control" id="account_type">
                                            <option value="" disabled selected hidden>Select Account type</option>
                                            <option value="savings">Savings</option>
                                            <option value="current">Current</option>
                                        </select>
                                        <span v-if="errors.account_type" class="text-danger">
                                            <small>{{ errors.account_type[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="account_name">Account name</label>
                                        <input type="text" v-model="employee.account_name" class="form-control" id="account_name" placeholder="Account name">
                                        <span v-if="errors.account_name" class="text-danger">
                                            <small>{{ errors.account_name[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="account_number">Account number</label>
                                        <input type="text" v-model="employee.account_number" class="form-control" id="account_number" placeholder="Account number">
                                        <span v-if="errors.account_number" class="text-danger">
                                            <small>{{ errors.account_number[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="bank_name">Bank name</label>
                                        <input type="text" v-model="employee.bank_name" class="form-control" id="bank_name" placeholder="Bank name">
                                        <span v-if="errors.bank_name" class="text-danger">
                                            <small>{{ errors.bank_name[0] }}</small>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="branch_name">Branch name</label>
                                        <input type="text" v-model="employee.branch_name" class="form-control" id="branch_name" placeholder="Branch name">
                                        <span v-if="errors.branch_name" class="text-danger">
                                            <small>{{ errors.branch_name[0] }}</small>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white">
                        <button type="submit" class="btn btn-primary">
                            <span v-if="loader === true" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Save
                        </button>
                    </div>
                </form>
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
                employee: {
                    grade: '',
                    name: '',
                    mobile_number: '',
                    address: '',
                    account_type: '',
                    account_name: '',
                    account_number: '',
                    bank_name: '',
                    branch_name: '',
                    account_id: '',
                },
                grades:[]

            };
        },

        mounted() {
            this.getData();
            this.getGrades();
        },

        methods: {
            getData() {
                axios.get(this.Api.employee+"/"+this.$route.params.id, { headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.employee = response.data;
                            this.employee.account_id = response.data.account.id;
                            this.employee.account_type = response.data.account.account_type;
                            this.employee.account_name = response.data.account.account_name;
                            this.employee.account_number = response.data.account.account_number;
                            this.employee.bank_name = response.data.account.bank_name;
                            this.employee.branch_name = response.data.account.branch_name;
                            this.employee.grade = response.data.grade.id;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            getGrades() {
                axios.get(this.Api.grade,{ headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.grades = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            dataUpdate() {
                this.loader = true;
                axios.put(this.Api.employee+"/"+this.$route.params.id, this.employee, {headers: this.$globalHelper.authHeader()})
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.loader = false;
                            this.message = response.message;
                        } else {
                            this.loader = false;
                            this.errors = response.errors;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loader = false;
                    });

            },
        },

    }
</script>


