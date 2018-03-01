<template lang="html">
    <section class="blog-post">
        <div class="card-body">
            <h3 class="blog-post__title">
                <router-link :to="post.slug">{{ post.title }}</router-link>
            </h3>

            <p class="blog-post__created">{{ post.human.created }}</p>

            <p class="blog-post__blurb">{{ post.blurb }}</p>

            <p class="blog-post__author">
                - By {{ post.user.name }}
            </p>
        </div>
    </section>
</template>

<script>
    export default {
        props: [
            'post'
        ],
    }
</script>