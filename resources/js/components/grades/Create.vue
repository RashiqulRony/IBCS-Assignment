<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="message" class="alert alert-secondary">
                    {{ message }}
                </div>
                <div class="card">
                    <div class="card-header">
                        Grade Create
                        <router-link class="btn btn-sm btn-info float-right" :to="{name: 'Grades'}">Back</router-link>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="dataStore">
                            <div class="form-group">
                                <label for="title">Grade Title</label>
                                <input type="text" v-model="grade.title" class="form-control" id="title" placeholder="Grade Title">
                                <span v-if="errors.title" class="text-danger">
                                    <small>{{ errors.title[0] }}</small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="salary">Grade Salary</label>
                                <input type="number" min="0" v-model="grade.salary" :disabled="isPrevious === true" step="0.01" max="100000" class="form-control" id="salary" placeholder="Grade Salary">
                                <span v-if="errors.salary" class="text-danger">
                                    <small>{{ errors.salary[0] }}</small>
                                </span>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <span v-if="loader === true" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Save
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
                isPrevious: false,
                message: '',
                errors: [],
                grade: {
                    title: '',
                    salary: '',
                },
                previousData: ''

            };
        },

        mounted() {
            this.previousGrade();
        },

        methods: {
            dataStore() {
                this.loader = true;
                axios.post(this.Api.grade, this.grade, {headers: this.$globalHelper.authHeader()})
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.loader = false;
                            this.message = response.message;
                            this.grade = {
                                title: '',
                                salary: '',
                            };
                            this.previousGrade()
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

            previousGrade() {
                axios.get(this.Api.grade+"/previous",{ headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.previousData = response.data;
                            this.grade.salary =  parseFloat(this.previousData.salary) + parseFloat(5000)
                            this.isPrevious = true
                        } else {
                            this.isPrevious = false
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

        },

    }
</script>


