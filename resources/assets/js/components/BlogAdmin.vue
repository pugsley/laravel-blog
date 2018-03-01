<template lang="html">
    <div>
        <div class="card-header">Admin</div>
        <div class="card-body">
            <div v-if="!user.id">
                You must be <a :href="'/login?intended=' + currentPath">logged in</a> to access the admin area.
            </div>

            <div v-if="user.id">
                <p v-if="posts.length < 1" class="alert alert-info">No blog posts yet. Get cracking and <router-link :to="{ name: 'admin.create'}">create one</router-link>!</p>

                <table v-if="posts.length > 0" class="table">
                    <thead>
                    <tr>
                        <th scope="col" width="10%">Id</th>
                        <th scope="col" width="30%">Title</th>
                        <th scope="col" width="20%">Author</th>
                        <th scope="col" width="25%">Created</th>
                        <th scope="col" width="15%">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="post, arrayIndex in posts">
                        <th scope="row">{{ post.id }}</th>
                        <td>{{ post.title }}</td>
                        <td>{{ post.user.name }}</td>
                        <td>{{ post.human.created }}</td>
                        <td>
                            <router-link :to="{ name: 'admin.edit', params: { id: post.id }}">Edit</router-link>&nbsp;&nbsp;
                            <span v-if="deleteId !== post.id">
                                <a href="#delete" v-on:click.prevent="deleteId = post.id" class="text-danger">Delete</a>
                            </span>
                            <span v-if="deleteId === post.id">
                                <a href="#really" v-on:click.prevent="deletePost(post.id)" class="text-danger">Really?</a>
                                <a href="#no" v-on:click.prevent="deleteId = null" class="text-muted">No</a>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <router-link :to="{ name: 'admin.create'}" tag="button" class="btn btn-primary">New blog post</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                deleteId: null,
            };
        },
        computed: {
            currentPath() {
                return this.$router.resolve(this.$router.currentRoute.fullPath).href;
            }
        },
        props: ['posts', 'user'],
        methods: {
            async deletePost(id) {
                try {
                    await axios.delete('/api/blog/' + id);
                    this.$emit('postdeleted', {id: id})

                } catch (error) {

                    let message;

                    // See https://github.com/axios/axios#handling-errors
                    if (error.response) {
                        if (error.response.status === 401) {
                            message = "Sorry, you don't have permission to delete this post.";
                        } else {
                            message = "Failed to delete post, error code: " + error.response.status;
                        }
                    } else if (error.request) {
                        message = "No response received from the server, please try again.";
                    } else {
                        message = error.message;
                    }

                    this.$emit('showmessage', {
                        message: message,
                        type: 'danger',
                        timeout: 5000
                    });
                }

                this.deleteId = null;
            }
        }
    }
</script>


