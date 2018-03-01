<template lang="html">
    <section class="blog-post">
        <div>
            <div class="card-header">
                <router-link :to="{ name: 'list'}">Blog Posts</router-link>
                → {{ post.title }}
            </div>
            <div class="card-body">
                <h3 class="blog-post__title">{{ post.title }}</h3>
                <p class="blog-post__created">{{ post.human.created }}</p>
                <div class="blog-post__blurb" v-html="post.html.content"></div>
                <p class="blog-post__author">
                    - By {{ post.user.name }}
                </p>

                <div v-if="user.id">
                    <router-link :to="{ name: 'admin.edit', params: { id: post.id }}" tag="button" class="btn btn-primary">Edit</router-link>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data: function() {
            return {
                post: null
            }
        },
        props: [
            'posts',
            'user'
        ],
        created() {
            // Determine if we're viewing a blog post in listing or full mode
            this.post = this.posts.find(post => {
                return this.$route.params.id === post.slug;
            });
        }
    }
</script>