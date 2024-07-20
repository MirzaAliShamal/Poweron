@extends('layouts.app')

@section('title', 'Email Templates')
@section('page-title', 'Email Templates')

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">Email Marketing</li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('email.templates.index') }}" class="text-muted text-hover-primary">Email Templates</a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-dark">Add Record</li>
    </ul>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3>Add Email Template</h3>
            </div>
        </div>
        <div class="card-body py-10">
            <form action="{{ route('email.templates.save') }}" method="POST" class="add-form">
                @csrf
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}"
                            />
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Content</label>
                            <textarea name="content" id="content" cols="30" rows="10"></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <button type="submit" class="btn btn-primary me-2">
                            Save Changes
                        </button>
                        <a href="{{ route('email.templates.index') }}" class="btn btn-secondary">
                            Go Back
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var laravelErrors = {!! $errors->toJson() !!};
    </script>
    <script src="{{ asset('assets/js/emailTemplates/add.js?v='.rand()) }}"></script>
@endsection
