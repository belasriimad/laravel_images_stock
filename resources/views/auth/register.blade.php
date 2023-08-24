@extends('layouts.app')


@section('title')
    Sign up
@endsection


@section('content')
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    Sign up
                </div>
                <div class="card-body">
                    @include('layouts.alerts')
                    <form method="POST" action="{{route('register')}}">
                        @csrf
                        <!-- Name input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="name" name="name" class="form-control" autocomplete="off" />
                            <label class="form-label" for="name">First name</label>
                        </div>
                      
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                          <input type="email" id="email" name="email" class="form-control" autocomplete="off"/>
                          <label class="form-label" for="email">Email address</label>
                        </div>
                      
                        <!-- Password input -->
                        <div class="form-outline mb-4">
                          <input type="password" id="password" name="password" class="form-control" />
                          <label class="form-label" for="password">Password</label>
                        </div>

                        <!-- Password confirm -->
                        <div class="form-outline mb-4">
                            <input type="password" id="password_confirmation" 
                                name="password_confirmation" class="form-control" />
                            <label class="form-label" for="password_confirmation">Password Confirmation</label>
                          </div>
                      
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">Sign up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection