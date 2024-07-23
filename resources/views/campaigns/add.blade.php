@extends('layouts.app')

@section('title', 'Campaigns')
@section('page-title', 'Campaigns')

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
            <a href="{{ route('campaigns.index') }}" class="text-muted text-hover-primary">Campaigns</a>
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
                <h3>Add Campaign</h3>
            </div>
        </div>
        <div class="card-body py-10">
            <form action="{{ route('campaigns.save') }}" method="POST" class="add-form">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="First campaign" value="{{ old('name') }}"/>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="This is first campaign" value="{{ old('subject') }}"/>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Sender Email</label>
                            <select name="sender_email" class="form-select" data-control="select2" data-placeholder="Choose Sender Email">
                                <option></option>
                                @foreach ($senders as $s)
                                    <option value="{{ $s['Email'] }}" {{ old('sender_email') == $s['Email'] ? 'selected' : '' }}>{{ $s['Email'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Email Template</label>
                            <select name="email_template_id" class="form-select" data-control="select2" data-placeholder="Choose Email Template">
                                <option></option>
                                @foreach ($emailTemplates as $et)
                                    <option value="{{ $et->id }}" {{ old('email_template_id') == $et->id ? 'selected' : '' }}>{{ $et->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group mb-5">
                            <label class="required form-label">Email List</label>
                            <select name="email_list_id[]" class="form-select" data-control="select2" data-placeholder="Choose Email List" multiple>
                                <option></option>
                                @foreach ($emailLists as $el)
                                    <option value="{{ $el->id }}" {{ old('email_list_id') == $el->id ? 'selected' : '' }}>{{ $el->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group mb-5">
                            <label class="form-label">Schedule at <small>(Note: If null then campaign will be regular)</small></label>
                            <input type="text" name="scheduled_at" class="form-control flat-datetime" placeholder="Choose datetime to schedule"/>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <button type="submit" class="btn btn-primary me-2">
                            Save Changes
                        </button>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
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
    <script src="{{ asset('assets/js/campaigns/add.js?v='.rand()) }}"></script>
@endsection
