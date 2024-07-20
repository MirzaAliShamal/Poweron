@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <form class="form w-100" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="text-center mb-10">
            <h1 class="text-dark mb-3">Sign In</h1>
        </div>
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
            <input
                class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                type="email" name="email" autocomplete="off" placeholder="e.g. admin@example.com" value="{{ old('email') }}" />
            @error('email')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
        <div class="fv-row mb-10">
            <div class="d-flex flex-stack mb-2">
                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                {{-- <a href="" class="link-primary fs-6 fw-bolder">Forgot Password ?</a> --}}
            </div>
            <input
                class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                type="password" name="password" autocomplete="off" placeholder="********" />
            @error('password')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                Continue
            </button>
        </div>
    </form>
@endsection

