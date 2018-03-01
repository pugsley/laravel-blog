@extends('layouts.app')

@section('content')
    <div>
        <transition name="fade">
            <blog-alert v-if="message.text" :message="message.text" :type="message.type"></blog-alert>
        </transition>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div>
                            <blog-loading v-if="loading"></blog-loading>
                            <router-view v-if="!loading"
                                         v-on:setloading="setLoading"
                                         v-on:postsaved="postSaved"
                                         v-on:postdeleted="postDeleted"
                                         v-on:showmessage="showMessage"
                                         :posts="posts"
                                         :user="user"
                            ></router-view>
                        </div>
                    </div>

                    <div class="mt-2" v-if="!loading">
                        <p v-if="user.id">
                            <span v-if="$route.path.match(/admin/)">← <router-link :to="{ name: 'list'}" class="text-muted">Back to the blog</router-link></span>
                            <span v-else><router-link :to="{ name: 'admin'}" class="text-muted">Access admin</router-link> →</span>
                        </p>
                        <p v-if="!user.id"><a :href="'/login?intended=' + this.$router.resolve('admin').href">Login</a> to access the admin area</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection