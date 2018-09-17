import VueRouter from "vue-router";

let routes = [
    {
        path: '/',
        component: require('./views/Home')
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
    }       
];

export default new VueRouter({
    routes
});