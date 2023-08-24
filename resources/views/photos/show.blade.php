@extends('layouts.app')

@section('title')
    Image Details
@endsection

@section('content')
    <!-- Gallery -->
    <div class="row my-4">
        @include('layouts.alerts')
        <div class="col-lg-6 col-md-8 mb-4">
            <img
                src="{{asset($photo->url)}}"
                class="w-100 h-100 shadow-1-strong rounded mb-4"
                alt="{{$photo->url}}"
            />
        </div>
        <div class="col-lg-6 col-md-4">
            <div class="d-flex flex-column justify-content-start">
                <div class="d-flex justify-content-start align-items-center">
                    <img
                        src="{{$photo->user->profile_image}}"
                        class="img-fluid rounded me-3"
                        width="60"
                        height="60"
                        alt="Profile image"
                    />
                    <div class="d-flex flex-column justify-content-start">
                        <span class="fw-bold">
                            {{$photo->user->name}}
                        </span>
                        @if (!$photo->is_free)
                            <span class="text-danger fw-bold">
                                ${{$photo->price}}
                            </span>
                        @endif
                    </div>
                </div>
                <p class="mt-2">
                    <i class="text-muted">
                        {{$photo->body}}
                    </i>
                </p>
                <div class="mt-2">
                    @if ($photo->is_free)
                        <a href="{{asset($photo->url)}}" 
                            class="btn btn-primary" download>
                            Download
                        </a>
                    @else 
                        @guest
                            <a href="{{route('login')}}" 
                                class="btn btn-primary">
                                Buy image
                            </a>
                        @endguest
                        @auth
                            @if (auth()->user()->orders->where('photo_id', $photo->id)->count())
                                <a href="{{asset($photo->url)}}" 
                                    class="btn btn-primary" download>
                                    Download
                                </a>
                            @else 
                                <a href="{{route('stripe.form', $photo->id)}}" 
                                    class="btn btn-primary">
                                    Buy image
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Gallery -->
@endsection