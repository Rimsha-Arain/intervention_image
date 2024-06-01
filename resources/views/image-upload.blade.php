<!doctype html>
<html lang="en">
    <head>
        <title>Laravel 11 image Intervention</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
<div class="container py-4">
    <div class="row">
        <div class="col-xl-6-m-auto">
            {{-- Alert response --}}
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @elseif (Session::has('error'))   
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div> 
            @endif

     <form action="{{ route('image.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
     <div class="card">
            <div class="card-header">
                <h4 class="card-title">Image Intervention in Laravel 11</h4>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="image">Image </label>
                    <input type="file" class="from-control" name="image" id="image"/>
                @error('image')
                    <p class= "text-danger">{{ $message }}</p>
                @enderror
                </div>
            </div>

          <div class="card-footer">
            <button type="submit" class="btn-btn-primary">Upload</button>
          </div>
        </div>
    </form>
        </div>
    </div>
</div>


        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>

