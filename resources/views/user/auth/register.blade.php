<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
</head>
@extends('layouts.app')
@section('title')
    Register
@endsection
@section('content')
<div class="page-title">
    <div class="container">
      <div class="row">
          <div class="col-lg-12">
            <ul class="breadcrumbs">
                <li><a href="/">Home</a> </li>
                <li class="separator"></li>
                <li>Login/Register</li>
              </ul>
          </div>
      </div>
    </div>
  </div>
    <div class="container padding-bottom-3x mb-1">
        <div class="row ">
            <div class="col-md-6">
                <div class="card register-area">
                    <div class="card-body ">
                        <h4 class="margin-bottom-1x text-center">Login</h4>
                        <form class="row" id="loginSubmit" action="{{ route('user.make.login') }}"
                            method="POST">
                            @csrf
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reg-email">E-mail Address</label>
                                    <input class="form-control" type="email" name="email_login" placeholder="E-mail Address"
                                        id="reg-email" value="{{ old('email_login') }}">
                                        @error('email_login')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reg-pass">Password</label>
                                    <input class="form-control" type="password" name="password_login" placeholder="Password"
                                        id="login-pass">
                                        @error('password_login')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                {{-- <input type="checkbox" class="checkbox" name="" id="show-password-checkbox"> <label for="show-password-checkbox">Show password</label> --}}
                            </div>

                            <div class="col-6 text-end">
                              <a class="btn btn-primary margin-bottom-none" href="{{route('password.request')}}">Reset Password</a>
                            </div>
                            <div class="col-6 text-left">
                                <button class="btn btn-primary margin-bottom-none" id="loginBtn"
                                    type="submit"><span>Login</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card register-area">
                    <div class="card-body ">
                        <h4 class="margin-bottom-1x text-center">Register</h4>
                        <form class="row" id="" action="{{ route('user.make.register') }}"
                            method="POST">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="reg-fn">First Name</label>
                                    <input class="form-control" type="text" name="first_name" placeholder="First Name"
                                        id="reg-fn" value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="reg-ln">Last Name</label>
                                    <input class="form-control" type="text" name="last_name" placeholder="Last Name"
                                        id="reg-ln" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="reg-email">E-mail Address</label>
                                    <input class="form-control" type="email" name="email" placeholder="E-mail Address"
                                        id="reg-email" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="reg-phone">Phone Number</label>
                                    <input class="form-control" name="phone" type="text" placeholder="Phone Number"
                                        id="reg-phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="reg-pass">Password</label>
                                    <input class="form-control" type="password" name="password" placeholder="Password"
                                        id="reg-pass">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="reg-pass-confirm">Confirm Password</label>
                                    <input class="form-control" type="password" name="password_confirmation"
                                        placeholder="Confirm Password" id="reg-pass-confirm">
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary margin-bottom-none"
                                    type="submit"><span>Register</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById('login-pass');
        const showPasswordCheckbox = document.getElementById('show-password-checkbox');
    
        showPasswordCheckbox.addEventListener('change', function () {
            passwordInput.type = this.checked ? 'text' : 'password';
        });


    document.getElementById('loginBtn').addEventListener('click', function(event) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Change Password !'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with the form submission or the action you want to perform
            // For example:
            document.getElementById('loginSubmit').submit();
        }
    });
});
    </script>
    
    
    
@endsection
