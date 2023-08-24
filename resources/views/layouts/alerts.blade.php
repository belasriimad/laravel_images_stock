@if ($errors->any())
    <div class="alert alert-danger pb-1">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success">
        {{session()->get('success')}}
    </div>
@endif