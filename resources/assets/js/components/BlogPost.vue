<template lang="html">
<div>
    <section class="blog-post">
        <div class="blog-post__content">
            <p>{{ content }}</p>
        </div>
        <div class="blog-post__author">
            - By {{ author }}
        </div>
    </section>
</div>
</template>

<script>
export default {
    data() {
        return {
            content: "Hello Blog Post",
            author: "James Moosebeard"
        }
    }
}
</script>


