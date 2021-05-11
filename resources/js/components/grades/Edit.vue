<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="message" class="alert alert-secondary">
                    {{ message }}
                </div>
                <div class="card">
                    <div class="card-header">
                        Grade Edit
                        <router-link class="btn btn-sm btn-info float-right" :to="{name: 'Grades'}">Back</router-link>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="dataUpdate">
                            <div class="form-group">
                                <label for="title">Grade Title</label>
                                <input type="text" v-model="grade.title" class="form-control" id="title" placeholder="Grade Title">
                                <span v-if="errors.title" class="text-danger">
                                    <small>{{ errors.title[0] }}</small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="salary">Grade Salary</label>
                                <input type="number" min="0" v-model="grade.salary" step="0.01" max="100000" class="form-control" id="salary" placeholder="Grade Salary">
                                <span v-if="errors.salary" class="text-danger">
                                    <small>{{ errors.salary[0] }}</small>
                                </span>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <span v-if="loader === true" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Update
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
                grade: {
                    title: '',
                    salary: '',
                }
            };
        },

        mounted() {
            this.getData();
        },

        methods: {
            getData() {
                axios.get(this.Api.grade+"/"+this.$route.params.id, { headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.grade = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },


            dataUpdate(){
                this.loader = true;
                axios.put(this.Api.grade+"/"+this.$route.params.id, this.grade, { headers: this.$globalHelper.authHeader() })
                    .then((response) => response.data)
                    .then((response) => {
                        if (response.status === true) {
                            this.loader = false;
                            this.message = response.message;
                            this.getData()
                        } else {
                            this.loader = false;
                            this.errors = response.errors;
                        }
                    })
                    .catch((error) => {
                        this.loader = false;
                        console.log(error);
                    });
            }
        },

    }
</script>


