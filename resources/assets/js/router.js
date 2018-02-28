import Vue from 'vue'
import Router from 'vue-router'
import BlogList from './components/BlogList.vue';
import BlogPost from './components/BlogPost.vue';

Vue.use(Router);

Vue.component("BlogPost);

export default new Router({
    mode: 'history',
    base: '/blog',
    routes: [
        {
            path: '/',
            name: 'list',
            component: BlogList
        },
        {
            path: '/:id',
            name: 'view',
            component: BlogPost
        }
    ]
})