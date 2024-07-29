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
                <li>reset - password</li>
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
                        <form class="row" id="passSubmit" action="{{ route('reset-password') }}"
                            method="POST">
                            @csrf

                            <input type="hidden" name="token" value="{{$token}}">
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reg-email">E-mail Address</label>
                                    <input class="form-control" type="email" name="email" placeholder="E-mail Address"
                                        id="reg-email" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reg-pass">New Password</label>
                                    <input class="form-control" type="password" name="password" placeholder="Password"
                                        id="login-pass">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                {{-- <input type="checkbox" class="checkbox" name="" id="show-password-checkbox"> <label for="show-password-checkbox">Show password</label> --}}
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reg-pass">confirmation Password</label>
                                    <input class="form-control" type="password" name="password_confirmation" placeholder="Password"
                                        id="login-pass">
                                        @error('password_confirmation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                {{-- <input type="checkbox" class="checkbox" name="" id="show-password-checkbox"> <label for="show-password-checkbox">Show password</label> --}}
                            </div>

                          
                            <div class="col-6 text-center">
                                <button class="btn btn-primary margin-bottom-none" id="changePass"
                                    type="submit"><span>change Password</span></button>
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
    
    document.getElementById('changePass').addEventListener('click', function(event) {
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
            document.getElementById('passSubmit').submit();
        }
    });
});

    </script>
    
    
    
@endsection
