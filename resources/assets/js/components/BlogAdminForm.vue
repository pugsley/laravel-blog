<template lang="html">
    <div>
        <div class="card-header">
            <router-link :to="{ name: 'admin'}">Admin</router-link>
            → {{ editing ? 'Edit' : 'Create' }} Blog Post
        </div>
        <div class="card-body">
            <div v-if="!user.id">
                You must be <a :href="'/login?intended=' + currentPath">logged in</a> to access the admin area.
            </div>
            <div v-if="user.id">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="title" ref="title" type="text" class="form-control" v-model="blogPost.title">
                    </div>
                    <div class="form-group">
                        <label for="blurb">Blurb</label>
                        <textarea class="form-control" id="blurb" rows="3" v-model="blogPost.blurb"></textarea>
                        <small class="form-text text-muted">A short blurb from the blog post, usually the first paragraph.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" rows="9" v-model="blogPost.content"></textarea>
                        <small class="form-text text-muted">The main blog post content.</small>
                    </div>

                    <button v-on:submit.prevent="saveForm()"
                            type="submit"
                            ref="button"
                            class="btn btn-primary"
                            :data-loading="editing ? 'Updating...' : 'Saving...'"
                            :data-default="editing ? 'Update' : 'Create'">
                            {{ editing ? 'Update' : 'Create' }}
                    </button>
                    <router-link :to="{ name: 'admin'}" tag="button" class="btn btn-link">Cancel</router-link>

                    <input type="hidden" :value="blogPost.id">
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['posts', 'user'],
        data: function() {
            return {
                blogPost: {
                    id: '',
                    title: '',
                    blurb: '',
                    content: ''
                },
                editing: false
            }
        },
        computed: {
            currentPath() {
                return this.$router.resolve(this.$router.currentRoute.fullPath).href;
            }
        },
        created() {
            // If we're editing a post, the id parameter will be set
            if (this.$route.params.id) {
                this.editing = true;

                // Find the post to edit
                const toEdit = this.posts.find(post => {
                    return parseInt(this.$route.params.id) === post.id;
                });

                if (toEdit) {
                    // Copy the found post fields that match the ones we're using in the form (i.e. defined as data)
                    Object.keys(this.blogPost).forEach((key) => this.blogPost[key] = toEdit[key]);
                } else {
                    console.log("Couldn't find blog post to edit", this.$route.params.id);
                }
            }
        },
        mounted() {
            if (this.$refs.title) {
                this.$refs.title.focus();
            }
        },
        methods: {
            async saveForm() {
                try {

                    // Disable button while processing
                    this.$refs.button.innerHTML = this.$refs.button.getAttribute('data-loading');
                    this.$refs.button.setAttribute('disabled', '');

                    let response;
                    if (this.editing) {
                        response = await axios.put('/api/blog/' + this.blogPost.id, this.blogPost);
                    } else {
                        response = await axios.post('/api/blog', this.blogPost);
                    }

                    // Emit an event with the new post
                    this.$emit('postsaved', {post: response.data});
                    this.$emit('showmessage', {message: "Blog post " + (this.editing ? "updated" : "created"), type: "info", timeout: 5000});

                    this.$router.push({name: 'admin', params: {id: response.data.id}});

                } catch (error) {

                    let message;
                    let type = "danger";
                    let timeout = 5000;

                    // See https://github.com/axios/axios#handling-errors
                    if (error.response) {
                        if (error.response.status === 401) {
                            message = "Sorry, you don't have permission to " + (this.editing ? "update this" : "create a new") + " post.";
                        } else {
                            message = "Failed to " + (this.editing ? "update" : "save") + " post";
                        }
                    } else if (error.request) {
                        message = "No response received from the server, please try again.";
                    } else {
                        message = error.message;
                    }

                    this.$emit('showmessage', {
                        message: message,
                        type: type,
                        timeout: timeout
                    });

                    // Reset button
                    this.$refs.button.innerHTML = this.$refs.button.getAttribute('data-default');
                    this.$refs.button.removeAttribute('disabled');
                }
            }
        }
    }
</script>


