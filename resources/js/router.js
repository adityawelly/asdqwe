import Vue from 'vue';
import VueRouter from 'vue-router';

//component
import LayoutAuth from './views/layouts/LayoutAuth.vue'
import Login from './views/auth/Login.vue'
import NProgress from 'nprogress'

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.use(VueRouter);

const routes = [
    
    /**
     * 
     * Layout for main app
     */
    {
        path: '/',
        redirect: '/auth/login'
    },
    {
        path: '/auth',
        component: LayoutAuth,
        children: [
            {
                path: 'login',
                component: Login,
                name: 'login'
            }
        ]
    }
]

const router = new VueRouter({
    routes,
    // mode: 'history',
    linkActiveClass: 'active'
});

router.beforeResolve((to, from, next) => {
    NProgress.start();
    next();
});

router.afterEach((to, from) => {
    // Complete the animation of the route progress bar.
    NProgress.done();
});

// before a request is made start the nprogress
window.axios.interceptors.request.use(config => {
    NProgress.start();

    return config;
}, (error) => {
    NProgress.done();

    return Promise.reject(error);
})

// before a response is returned stop nprogress
window.axios.interceptors.response.use(response => {
    NProgress.done();
    
    return response;
}, (error) => {
    NProgress.done();
    // return to login if unauthorized
    if (error.response.status == 401 || error.response.status == 419) {
        this.$utils.sendNotification('Sesi anda sudah habis, login kembali');
        return router.push('/login');
    }
    return Promise.reject(error);
});

export default router;