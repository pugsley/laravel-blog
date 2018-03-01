<template lang="html">
    <section class="blog-post">
        <div>
            <div class="card-header">
                Oh, no — sad face :'(
            </div>
            <div class="card-body">
                <h3 class="blog-post__title">Page not found</h3>
                <p>Sorry, something went wrong and we couldn't find that page. Try starting again from the <router-link :to="{ name: 'list'}">blog homepage</router-link></p>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
    }
</script>