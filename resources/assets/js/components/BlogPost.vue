<template lang="html">
    <section class="blog-post">
        <div>
            <div class="card-header">
                <router-link :to="{ name: 'list'}">Posts</router-link>
                → {{ blogPost.title }}
            </div>
            <div class="card-body">
                <h3 class="blog-post__title">
                    <router-link v-if="listing" :to="blogPost.slug">{{ blogPost.title }}</router-link>
                    <span v-if="!listing">{{ blogPost.title }}</span>
                </h3>

                <p class="blog-post__created">{{ blogPost.human.created }}</p>

                <p v-if="listing" class="blog-post__blurb">{{ blogPost.blurb }}</p>
                <div v-if="!listing" class="blog-post__blurb" v-html="blogPost.html.content"></div>

                <p class="blog-post__author">
                    - By {{ blogPost.user.name }}
                </p>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                blogPost: null,
                listing: true,
            };
        },
        props: [
            'posts',
            'post'
        ],
        created() {
            // Determine if we're viewing a blog post in listing or full mode
            if (this.$route.params.id) {
                this.listing = false;
                this.blogPost = this.posts.find(post => {
                    return this.$route.params.id === post.slug;
                });
            } else {
                this.blogPost = this.post;
            }
        }
    }
</script>