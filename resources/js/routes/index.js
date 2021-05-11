import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

import globalHelper from "../globalHelper";

import Home            from '../components/Home'
import Login           from '../components/auth/Login'
import Register        from '../components/auth/Register'
import Header          from '../components/Header';

import Dashboard       from '../components/Dashboard'

import Grades          from '../components/grades/Index'
import GradeCreate     from '../components/grades/Create'
import GradeEdit       from '../components/grades/Edit'

import Employee        from '../components/employee/Index'
import EmployeeCreate  from '../components/employee/Create'
import EmployeeEdit    from '../components/employee/Edit'

import Salary          from '../components/salary/Index.vue'

const router =  new Router({
    mode: 'history',
    routes: [
        {
            path: "/",
            name: "Home",
            components: {
                'default': Home,
                'header': Header,
            },
        },
        {
            path: "/login",
            name: "Login",
            components: {
                'default': Login,
                'header': Header,
            },
        },
        {
            path: "/register",
            name: "Register",
            components: {
                'default': Register,
                'header': Header,
            },
        },
        {
            path: "/dashboard",
            name: "Dashboard",
            components: {
                'default': Dashboard,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/grades",
            name: "Grades",
            components: {
                'default': Grades,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/grade-create",
            name: "GradeCreate",
            components: {
                'default': GradeCreate,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/grade/edit/:id",
            name: "GradeEdit",
            components: {
                'default': GradeEdit,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/employees",
            name: "Employee",
            components: {
                'default': Employee,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/employee-create",
            name: "EmployeeCreate",
            components: {
                'default': EmployeeCreate,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/employee/edit/:id",
            name: "EmployeeEdit",
            components: {
                'default': EmployeeEdit,
                'header': Header,
            },
            meta: { requiresAuth: true }
        },
        {
            path: "/salary",
            name: "Salary",
            components: {
                'default': Salary,
                'header': Header,
            },
            meta: { requiresAuth: true }
        }


    ],
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (globalHelper.authToken() === null) {
            next({
                path: '/login',
            })
        } else {
            next()
        }
    } else {
        next()
    }
});

export default router;
