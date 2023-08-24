@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')
    <div class="row my-5">
        <div class="col-md-4">
            @include('layouts.alerts')
            <img src="{{auth()->user()->profile_image}}"
                width="200"
                height="200"
                class="img-fluid rounded border border-dark"    
                alt="" srcset="">
            <form action="{{route('user.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="my-2">
                    <label class="form-label" for="image">Update profile image</label>
                    <input 
                        type="file" 
                        id="image" name="image" 
                        class="form-control"/>
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4">
                    Upload
                </button>
            </form>
        </div>
        <div class="col-md-6">
            <table class="table align-middle mb-0 bg-white caption-top">
                <caption>
                    My Photos
                </caption>
                <thead class="bg-light">
                    <tr>
                        <th>Photo</th>
                        <th>Price</th>
                        <th>Added</th>
                        <th>Action</th>
                    </tr>
                    <tbody>
                        @foreach (auth()->user()->photos as $photo)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{asset($photo->url)}}"
                                            width="45"
                                            height="45"
                                            class="rounded-circle"    
                                            alt="" srcset="">
                                    </div>
                                </td>
                                <td>
                                    @if ($photo->is_free)
                                        <span class="badge badge-primary rounded-pill d-inline">
                                            Free    
                                        </span>    
                                    @else 
                                        <span class="badge badge-danger rounded-pill d-inline">
                                            ${{$photo->price}}    
                                        </span>   
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success rounded-pill d-inline">
                                        {{$photo->created_at->diffForHumans()}}    
                                    </span>  
                                </td>
                                <td>
                                    <a href="{{route('photos.edit', $photo->id)}}" class="btn btn-warning btn-sm btn-rounded">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </thead>
            </table>
            <table class="table align-middle mb-0 bg-white caption-top">
                <caption>
                    My Orders
                </caption>
                <thead class="bg-light">
                    <tr>
                        <th>Photo</th>
                        <th>Purchased</th>
                        <th>Action</th>
                    </tr>
                    <tbody>
                        @foreach (auth()->user()->orders as $order)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{asset($order->photo->url)}}"
                                            width="45"
                                            height="45"
                                            class="rounded-circle"    
                                            alt="" srcset="">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-success rounded-pill d-inline">
                                        {{$order->created_at->diffForHumans()}}    
                                    </span>  
                                </td>
                                <td>
                                    <a href="{{asset($photo->url)}}" 
                                        class="btn btn-primary" download>
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </thead>
            </table>
        </div>
    </div>
@endsection