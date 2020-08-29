@extends('frontend.layouts.master')

@section('title')
LOGIN
@endsection

@section('body')
<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li><a href="index.html"><i class="fa fa-home"></i></a></li>
        <li><a href="login.html">Account</a></li>
        <li><a href="login.html">Login</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
          <h1 class="title">Account Login</h1>
          <div class="row">
            <div class="col-sm-6">
              <h2 class="subtitle">New Customer</h2>
              <p><strong>Register Account</strong></p>
              <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
              <a href="{{ url('/register') }}" class="btn btn-primary">Continue</a> </div>
            <div class="col-sm-6">
              <h2 class="subtitle">Returning Customer</h2>
              <p><strong>I am a returning customer</strong></p>
              <form method="POST" action="{{ route('login') }}">
              @csrf
                <div class="form-group">
                  <label class="control-label" for="input-email">E-Mail Address</label>
                  <input type="text" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" id="input-email" class="form-control" />
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                  <label class="control-label" for="input-password">Password</label>
                  <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control" />
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                  <br />
                  <a href="#">Forgotten Password</a></div>
                <input type="submit" value="Login" class="btn btn-primary" />
                </form>
            </div>
          </div>
        </div>
        <!--Middle Part End -->
        <!--Right Part Start -->
        <aside id="column-right" class="col-sm-3 hidden-xs">
          <h3 class="subtitle">Account</h3>
          <div class="list-group">
            <ul class="list-item">

              <li><a href="{{ url('register') }}">Register</a></li>

            </ul>
          </div>
        </aside>
        <!--Right Part End -->
      </div>
    </div>
  </div>
@endsection

