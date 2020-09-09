import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import Dashboard from './components/pages/dashboard'
import EmployeeManagement from './components/pages/employee_management'
import Department from './components/pages/system_management/department'
import Division from './components/pages/system_management/division'
import Country from './components/pages/system_management/country'
import State from './components/pages/system_management/state'
import City from './components/pages/system_management/city'
import User from './components/pages/user_management/user'
import Login from './components/pages/login'

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/employee-management',
        name: 'employee-management',
        component: EmployeeManagement
    },
    {
        path: '/department',
        name: 'department',
        component: Department
    },
    {
        path: '/division',
        name: 'division',
        component: Division
    },
    {
        path: '/country',
        name: 'country',
        component: Country
    },
    {
        path: '/state',
        name: 'state',
        component: State
    },
    {
        path: '/city',
        name: 'city',
        component: City
    },
    {
        path: '/user',
        name: 'user',
        component: User
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    }
]

export default new Router({
    mode: 'history',
    routes
})