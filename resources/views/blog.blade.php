@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Blog</div>
                    <div class="card-body">
                        <blog-loading v-if="loading"></blog-loading>
                        <blog-alert v-if="error" :message="error" type="danger"></blog-alert>
                        <div v-if="!loading">
                            <router-view :posts="posts"></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection