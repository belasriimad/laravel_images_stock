@extends('layouts.app')


@section('title')
    Login
@endsection


@section('content')
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    Sign in
                </div>
                <div class="card-body">
                    @include('layouts.alerts')
                    <form method="POST" action="{{route('login')}}">
                        @csrf
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                          <input type="email" id="email" name="email" class="form-control" autocomplete="off"/>
                          <label class="form-label" for="email">Email address</label>
                        </div>
                      
                        <!-- Password input -->
                        <div class="form-outline mb-4">
                          <input type="password" id="password" name="password" class="form-control" autocomplete="off"/>
                          <label class="form-label" for="password">Password</label>
                        </div>

                        <div class="row mb-4">
                            <div class="col d-flex justify-content-center">
                              <!-- Checkbox -->
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                    {{old('remember') ? 'checked' : ''}} />
                                <label class="form-check-label" for="remember"> Remember me </label>
                              </div>
                            </div>
                        </div>
                      
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection