import './bootstrap';
import Vue from 'vue';
import VueRouter from 'vue-router'
import BlogList from './components/BlogList.vue';
import BlogPostPreview from './components/BlogPostPreview.vue';
import BlogPostFull from './components/BlogPostFull.vue';
import BlogLoading from './components/BlogLoading.vue';
import BlogAlert from './components/BlogAlert.vue';
import BlogAdmin from './components/BlogAdmin.vue';
import BlogAdminForm from './components/BlogAdminForm.vue';
import BlogNotFound from './components/BlogNotFound.vue';

Vue.use(VueRouter);
Vue.component('blog-list', BlogList);
Vue.component('blog-post-preview', BlogPostPreview);
Vue.component('blog-post-full', BlogPostFull);
Vue.component('blog-loading', BlogLoading);
Vue.component('blog-alert', BlogAlert);
Vue.component('blog-admin', BlogAdmin);
Vue.component('blog-admin-form', BlogAdminForm);
Vue.component('blog-404', BlogNotFound);

const router = new VueRouter({
    mode: 'history',
    base: '/blog',
    routes: [
        {
            path: '',
            name: 'list',
            component: BlogList,
            props: true
        },
        {
            path: '/:id(\\d+.+)',
            name: 'view',
            component: BlogPostFull,
            props: true
        },
        {
            path: '/admin',
            name: 'admin',
            component: BlogAdmin,
            props: true,
        },
        {
            path: '/admin/create',
            name: 'admin.create',
            component: BlogAdminForm,
            props: true,
        },
        {
            path: '/admin/:id(\\d+)/edit',
            name: 'admin.edit',
            component: BlogAdminForm,
            props: true,
        },
        {
            path: "*",
            component: BlogNotFound
        }
    ]
});

const app = new Vue({
    el: '#app',
    router: router,
    data: {
        loading: true,
        posts: [],
        post: null,
        error: null,
        user: {},
        message: {
            text: '',
            type: ''
        }
    },
    async created() {

        try {
            const response = await axios.get('/api/blog');
            this.posts = response.data;
        } catch (error) {
            this.message = {
                text: "Failed to load blog posts",
                type: "danger"
            };
        }

        try {
            const response = await axios.get('/api/user');
            if (response.data === null) {
                this.user = {};
            } else {
                this.user = response.data;
            }
            this.loading = false;
        } catch (error) {
            this.message = {
                text: "Failed to load user data",
                type: "danger"
            };
        }
    },

    methods: {
        postSaved({post}) {
            const postIndex = this.posts.findIndex(existingPost => {
                return post.id === existingPost.id;
            });

            if (postIndex !== -1) {
                this.posts[postIndex] = post;
            } else {
                this.posts.push(post);
            }
        },

        postDeleted({id}) {
            const index = this.posts.findIndex(existingPost => {
                return id === existingPost.id;
            });
            if (index !== -1) {
                this.posts.splice(index, 1);
            }
        },

        showMessage({message, type, timeout}) {
            this.message = {
                text: message,
                type: type
            };

            // Hide the message after x ms if specified
            if (timeout) {
                setTimeout(() => {
                    this.message = {
                        text: '',
                        type: '',
                    };
                }, timeout);
            }
        },
        setLoading(value) {
            this.loading = value;
        },
    }
});
