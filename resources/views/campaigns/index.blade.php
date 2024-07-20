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
        <li class="breadcrumb-item text-dark">Campaigns</li>
    </ul>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-0 pt-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path
                                    d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                    fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>
                    </span>
                    <input type="text" id="search" data-kt-customer-table-filter="search"
                        class="form-control form-control-solid w-250px ps-15" placeholder="Search Records"
                        autocomplete="off">
                </div>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('campaigns.add') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="server-datatables table align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bolder text-muted bg-light">
                            <th class="ps-4 rounded-start">ID</th>
                            <th>Name</th>
                            <th>Email Template</th>
                            <th>Subscribers</th>
                            <th>Sent</th>
                            <th>Bounced</th>
                            <th>Open</th>
                            <th>Status</th>
                            <th class="pe-4 text-end rounded-end">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="schedule_campaign" aria-hidden="true">
        <div class="modal-dialog mw-600px">
            <div class="modal-content">
                <div class="modal-header" id="schedule_campaign_modal_header">
                    <h2 class="fw-bolder">Schedule Campaign</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" onclick="closeModal('#add_details_modal')">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                    <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                                    <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2" rx="1" />
                                </g>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5">
                    <form class="form" method="POST" action="">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="form-group mb-5">
                                    <label class="required form-label">Select Timezone</label>
                                    <select name="timezone" class="form-select" data-control="select2" data-placeholder="Choose timezone" required>
                                        <option></option>
                                        @foreach (timezones() as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="form-group mb-5">
                                    <label class="required form-label">Schedule at</label>
                                    <input type="text" name="schedule_at" class="form-control flat-datetime" placeholder="Select Date time" value="{{ now() }}" required/>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="reset" class="btn btn-light me-3" onclick="closeModal('#schedule_campaign')">Discard</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/campaigns/index.js?v='.rand()) }}"></script>
@endsection
