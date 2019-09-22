@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<style>
    .imgHeight{
        height: 150px !important;
    }
    .folder{
        padding: 10px;
        font-size: 18px;
        border: 1px solid #ccc;
        cursor: pointer;
        color: #010101;
    }
    .folder:hover{
        box-shadow: 2px 2px #ddd;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url()->previous() }}" class="float-right">Back</a>
                    <a href="{{ route('home') }}">Home</a>
                </div>

                <div class="card-body">
                    <div class="row" style="margin-bottom: 15px;">
                       @foreach($folders as $folder)
                       <div class="col-md-2">
                           <a href="{{ route('home', ['parent_id' =>$folder->id]) }}">
                               <div class="folder">
                                    <i class="fa fa-folder" aria-hidden="true"></i>
                                    <span>{{ $folder->folder_name }}</span>
                               </div>
                           </a>
                       </div>
                       @endforeach
                   </div>
                   <div class="row">
                        @foreach($images as $image)
                        <div class="col-md-2">
                            <div class="thumbnail">
                                <a href="{{ asset($image->image_path) }}">
                                    <img class="img-thumbnail imgHeight" src="{{ asset($image->image_path) }}" alt="{{ $image->image_name }}" style="width:100%">
                                  <div class="caption">
                                    <p>{{ $image->image_name }}</p>
                                  </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
