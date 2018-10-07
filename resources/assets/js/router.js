import VueRouter from "vue-router";

let routes = [
    {
        path: '/',
        redirect: '/workers'
    },
    {
        path: '/workers',
        component: require('./views/Workers')
    },
    {
        path: '/customers',
        component: require('./views/Customers')
    },
    {
        path: '/projects',
        component: require('./views/Projects')
    },
    {
        path: '/income',
        component: require('./views/Income')
    },         
];

export default new VueRouter({
    routes
});