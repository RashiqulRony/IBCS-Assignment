<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div v-if="message" class="alert alert-secondary">
                    {{ message }}
                </div>
                <div class="card">
                    <div class="card-header">
                        Grades
                        <router-link class="btn btn-sm btn-info float-right" :to="{name: 'GradeCreate'}">Add Grade</router-link>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Salary</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="grades.length > 0">
                                    <tr v-for="(grade, index) in grades" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ grade.title }}</td>
                                        <td>Tk.{{ grade.salary }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenu21" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu21">
                                                    <router-link class="dropdown-item" :to="{name: 'GradeEdit', params: {id: grade.id}}">Edit</router-link>
                                                    <button class="dropdown-item" @click="deleteData(grade.id)" type="button">Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="4" align="center">Data not found</td>
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
    import axios from "axios";

    export default {
        data() {
            return {
                loader: false,
                message: '',
                grades: [],
            };
        },

        mounted() {
            this.getDatas();
        },

        methods: {
            getDatas() {
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

            deleteData(id) {
                axios.delete(this.Api.grade+"/"+id, { headers: this.$globalHelper.authHeader() })
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


