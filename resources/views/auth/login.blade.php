@extends('layouts.auth')
@section('content')
<!-- Heading -->
<h1 class="display-4 text-center mb-3">Sign in</h1>

<!-- Subheading -->
<p class="text-muted text-center mb-5">
Access more functionality with sublime access
</p>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="password">{{ __('Password') }}</label>
            </div>
            <div class="col-auto">
                @if (Route::has('password.request'))
                    <a class="form-text small text-muted" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </div>
       
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group row">
        <div class="col-md-6 offset-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-lg btn-block btn-primary mb-3">
        {{ __('Login') }}
    </button>

    <p class="text-center">
        <small class="text-muted text-center">
            Don't have an account yet? <a href="/">Go home</a>.
        </small>
    </p>
</form>
@endsection