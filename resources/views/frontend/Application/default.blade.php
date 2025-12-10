<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>default</h1>
</body>
</html>

@extends('frontend.layouts2.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>{{ $application->name ?? '' }}</h1>

          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Create {{ $application->name ?? '' }}</h4>
                  </div>
                <div class="card-body">
                   <h1>No Form Avaiable</h1>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>
@endsection

