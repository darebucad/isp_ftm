@extends('layouts.app')


@section('content')
<div class="animate form login_form">
  <section class="login_content">
    <form method="POST" action="{{ route('login') }}">
      @csrf

      <h1>Login Form</h1>

      <div>
        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username"  value="{{ old('username') }}" required autocomplete="username" placeholder="Username" autofocus />
        @error('username')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" />
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div>
        <button type="submit" class="btn btn-primary">
            {{ __('Login') }}
        </button>

        @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
      </div>

      <div class="clearfix"></div>

      <div class="separator">
        <p class="change_link">New to site?
          <a href="#signup" class="to_register"> Create Account </a>
        </p>

        <div class="clearfix"></div>
        <br />

        <div>
          <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
          <p>©2019 All Rights Reserved. Gentelella Alela!</p>
        </div>
      </div>
    </form>
  </section>
</div>

<div id="register" class="animate form registration_form">
  <section class="login_content">
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <h1>Create Account</h1>

      <div>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" id="username" required autocomplete="username" placeholder="Username" autofocus />
        @error('username')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Name" />
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" />
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password" />
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div>
        <input id="password_confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
      </div>

      <div>
        <button type="submit" class="btn btn-primary">
            {{ __('Register') }}
        </button>
      </div>

      <div class="clearfix"></div>

      <div class="separator">
        <p class="change_link">Already a member ?
          <a href="#signin" class="to_register"> Log in </a>
        </p>

        <div class="clearfix"></div>
        <br />

        <div>
          <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
          <p>©2019 All Rights Reserved. Gentelella Alela!</p>
        </div>
      </div>
    </form>
  </section>
</div>

@endsection
