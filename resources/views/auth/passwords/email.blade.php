@extends('layouts.auth')

@section('content')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<!-- Heading -->
<h1 class="display-4 text-center mb-3">
Password reset
</h1>

<!-- Subheading -->
<p class="text-muted text-center mb-5">
Enter your email to get a password reset link.
</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label for="email">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <button type="submit" class="btn btn-lg btn-block btn-primary mb-3">
        {{ __('Send Password Reset Link') }}
    </button>

    <div class="text-center">
        <small class="text-muted text-center">
        Remember your password? <a href="/login">Log in</a>.
        </small>
    </div>
        
</form>
@endsection
