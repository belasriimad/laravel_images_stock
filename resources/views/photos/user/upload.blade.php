@extends('layouts.app')


@section('title')
    Upload Photo
@endsection


@section('content')
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    Upload
                </div>
                <div class="card-body">
                    @include('layouts.alerts')
                    <form method="POST" action="{{route('photos.store')}}" 
                        enctype="multipart/form-data">
                        @csrf
                        <!-- File input -->
                        <div class="mb-4">
                            <label class="form-label" for="image">Photo*</label>
                            <input type="file" 
                                onchange="readUrl(this, 'image_preview')"
                                id="image" name="image" class="form-control"/>
                        </div>
                      
                        <div class="mt-2">
                            <img src="#" alt="" 
                                id="image_preview" 
                                class="d-none img-fluid rounded mb-2"
                                width="100"
                                height="100">
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <textarea rows="4" id="body" name="body" class="form-control" autocomplete="off"></textarea>
                            <label class="form-label" for="body">Description*</label>
                        </div>

                        <div class="row mb-4">
                            <div class="col d-flex justify-content-around">
                              <!-- Checkbox -->
                              <div class="form-check">
                                <input class="form-check-input" 
                                    onchange="checkIfFree(0)"
                                    type="radio" name="is_free" 
                                    id="free" 
                                    value="1" checked />
                                <label class="form-check-label" for="free"> Free </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" 
                                    onchange="checkIfFree(1)"
                                    type="radio" name="is_free" 
                                    id="paid" 
                                    value="0" />
                                <label class="form-check-label" for="paid"> Paid </label>
                              </div>
                            </div>
                        </div>

                        <div class="form-outline d-none mb-4" id="priceField">
                            <input type="number" id="price" name="price" class="form-control"/>
                            <label class="form-label" for="price">Price</label>
                        </div>
                      
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">
                            Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function checkIfFree(value) {
            if(value) {
                document.getElementById('priceField').classList.remove('d-none');
            }else {
                document.getElementById('priceField').classList.add('d-none');
            }
        }

        function readUrl(input, image) {
            if(input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(image).classList.remove('d-none');
                    document.getElementById(image).setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection