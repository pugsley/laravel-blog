import './bootstrap';
import Vue from 'vue';
import VueRouter from 'vue-router'
import BlogList from './components/BlogList.vue';
import BlogPost from './components/BlogPost.vue';
import BlogLoading from './components/BlogLoading.vue';
import BlogAlert from './components/BlogAlert.vue';

Vue.use(VueRouter);
Vue.component('blog-list', BlogList);
Vue.component('blog-post', BlogPost);
Vue.component('blog-loading', BlogLoading);
Vue.component('blog-alert', BlogAlert);
Vue.component('blog-admin', BlogAdmin);

const router = new VueRouter({
    mode: 'history',
    base: '/blog',
    routes: [
        {
            path: '/',
            name: 'list',
            component: BlogList,
            props: true
        },
        {
            path: '/:id',
            name: 'view',
            component: BlogPost,
            props: true
        },
        {
            path: '/admin',
            name: 'admin',
            component: BlogAdmin,
            props: true
        }
    ]
});

const app = new Vue({
    el: '#app',
    router: router,
    data: {
        loading: true,
        posts: [],
        error: null
    },
    async created() {
        try {
            const response = await axios.get('/blog/api/posts');
            setTimeout(function() {
                this.posts = response.data;
                this.loading = false;
            }.bind(this), 500);
        } catch (error) {
            console.error(error);
        }
    }
});
