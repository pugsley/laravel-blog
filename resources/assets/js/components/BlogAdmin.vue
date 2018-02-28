<template lang="html">
    <div class="blog-items">
        <blog-post v-for="post in posts" :post="post" :key="post.id"></blog-post>
    </div>
</template>

<script>
export default {
    props: ['posts'],
    updated() {
    }
}
</script>


