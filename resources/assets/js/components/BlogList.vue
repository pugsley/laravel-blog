<template lang="html">
    <div class="blog-items">
        <div class="card-header">Blog Posts</div>
        <div class="card-body">
            <p v-if="posts.length < 1" class="alert alert-info">No blog posts to show!</p>
            <blog-post-preview v-if="posts.length > 0" v-for="post in posts" :post="post" :key="post.id"></blog-post-preview>
        </div>
    </div>
</template>

<script>
export default {
    props: ['posts'],
    updated() {
    }
}
</script>


