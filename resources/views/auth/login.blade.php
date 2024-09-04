@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="bg-gray-200 min-h-screen mt-5 flex justify-center items-center flex-col">

                            <div class="d-flex flex-column align-items-center">
                                <!-- Facebook Login Button -->
                                <a href="{{ url('auth/facebook') }}">
                                    <button class="btn btn-primary btn-block d-flex align-items-center mb-2">
                                        <span class="me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M22.675 0h-21.35c-.733 0-1.325.592-1.325 1.325v21.351c0 .733.592 1.324 1.325 1.324h11.495v-9.294h-3.125v-3.622h3.125v-2.672c0-3.1 1.894-4.788 4.659-4.788 1.325 0 2.464.099 2.797.143v3.243h-1.918c-1.503 0-1.796.715-1.796 1.763v2.311h3.59l-.467 3.622h-3.123v9.294h6.116c.732 0 1.324-.591 1.324-1.324v-21.351c0-.733-.592-1.325-1.325-1.325z" />
                                            </svg>
                                        </span>
                                        <span>Login with Facebook</span>
                                    </button>
                                </a>

                                <!-- Google Login Button -->
                                <a href="{{ url('auth/google') }}">
                                    <button class="btn btn-danger btn-block d-flex align-items-center">
                                        <span class="me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 48 48">
                                                <path
                                                    d="M44.5 20H24v8.5h11.9C34.7 32.3 30.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.8 1.1 7.9 3l5.8-5.8C33.3 6.9 28.9 5 24 5 13.5 5 5 13.5 5 24s8.5 19 19 19c10.3 0 19-8.3 19-19 0-1.3-.1-2.7-.5-4z" />
                                            </svg>
                                        </span>
                                        <span>Login with Google</span>
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
