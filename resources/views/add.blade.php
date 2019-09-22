@extends('layouts.app')

@section('style')
    <style>
        .fade.in {
            opacity: 1;
        }
        @media (max-width: 767px) {
          .description {
            display: none;
          }
        }
        .preview img {
            width: 100px;
            height: 100px;
        }
    </style>
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css"/>
    <link rel="stylesheet" href="{{ asset('vendor/jquery-file-upload/css/jquery.fileupload.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/jquery-file-upload/css/jquery.fileupload-ui.css') }}"/>
    <noscript
      ><link rel="stylesheet" href="{{ asset('vendor/jquery-file-upload/css/jquery.fileupload-noscript.css') }}"
    /></noscript>
    <noscript
      ><link rel="stylesheet" href="{{ asset('vendor/jquery-file-upload/css/jquery.fileupload-ui-noscript.css') }}"
    /></noscript>
    
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add New</div>

                <div class="card-body">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <span>{{ $message }}</span>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <span>{{ $message }}</span>
                        </div>
                    @endif
<!--                    <form action="{{route('add-folder')}}" method="post" enctype="multipart/form-data">-->
                        <form id="fileupload" action="{{route('add-image')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                          <label for="parent_id">Select Folder</label>
                          <select class="form-control" id="parent_id" name="parent_id" placeholder="Select Parent Folder">
                              <option value="0">Other</option>
                              @foreach($options_list as $option)
                              <option value="{{ $option->id }}">{{ $option->folder_name }}</option>
                              @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="folder_name">Create New Folder</label>
                          <input type="text" class="form-control" id="folder_name" name="folder_name" placeholder="Enter Folder Name">
                        </div>
                        
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-12" style="margin-bottom: 15px">
                              <!-- The fileinput-button span is used to style the file input field as button -->
                              <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Add files...</span>
                                <input type="file" name="files[]" multiple />
                              </span>
                              <button type="submit" class="btn btn-primary start">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start upload</span>
                              </button>
                              <button type="reset" class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel upload</span>
                              </button>
                              <button type="button" class="btn btn-danger delete">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete selected</span>
                              </button>
                              <input type="checkbox" class="toggle" />
                              <!-- The global file processing state -->
                              <span class="fileupload-process"></span>
                            </div>
                            <!-- The global progress state -->
                            <div class="col-lg-12 fileupload-progress fade">
                              <!-- The global progress bar -->
                              <div
                                class="progress progress-striped active"
                                role="progressbar"
                                aria-valuemin="0"
                                aria-valuemax="100"
                              >
                                <div
                                  class="progress-bar progress-bar-success"
                                  style="width:0%;"
                                ></div>
                              </div>
                              <!-- The extended global progress state -->
                              <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped">
                          <tbody class="files"></tbody>
                        </table>

                        <div class="form-group">
                            <button class="btn btn-primary" id="addFolder" data-url="{{ route('add-folder') }}" type="button">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="{{ asset('vendor/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('vendor/jquery-file-upload/js/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('vendor/jquery-file-upload/js/jquery.fileupload.js') }}"></script>
<!-- The File Upload processing plugin -->
<script src="{{ asset('vendor/jquery-file-upload/js/jquery.fileupload-process.js') }}"></script>
 <!-- The File Upload validation plugin -->
<script src="{{ asset('vendor/jquery-file-upload/js/jquery.fileupload-validate.js') }}"></script>
<!-- The File Upload user interface plugin -->
<script src="{{ asset('vendor/jquery-file-upload/js/jquery.fileupload-ui.js') }}"></script>
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#addFolder').click(function(e){
        e.preventDefault();
        $('#fileupload').attr('action', $(this).data('url'));
        $('#fileupload').submit();
    });
});
</script>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
      <tr class="template-upload fade">
          <td>
              <span class="preview"></span>
          </td>
          <td>
              {% if (window.innerWidth > 480 || !o.options.loadImageFileTypes.test(file.type)) { %}
                  <p class="name">{%=file.name%}</p>
              {% } %}
              <strong class="error text-danger"></strong>
          </td>
          <td>
              <p class="size">Processing...</p>
              <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
          </td>
          <td>
              {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                    <i class="glyphicon glyphicon-edit"></i>
                    <span>Edit</span>
                </button>
              {% } %}
              {% if (!i && !o.options.autoUpload) { %}
                  <button class="btn btn-primary start" disabled>
                      <i class="glyphicon glyphicon-upload"></i>
                      <span>Start</span>
                  </button>
              {% } %}
              {% if (!i) { %}
                  <button class="btn btn-warning cancel">
                      <i class="glyphicon glyphicon-ban-circle"></i>
                      <span>Cancel</span>
                  </button>
              {% } %}
          </td>
      </tr>
  {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
      <tr class="template-download fade">
          <td>
              <span class="preview">
                  {% if (file.thumbnailUrl) { %}
                        <input type="hidden" name="uploadedFiles[]" value="{%=file.name%}" />
                        <input type="hidden" name="filePath[]" value="{%=file.path%}" />
                        <input type="hidden" name="fileSlug[]" value="{%=file.slug%}" />
                      <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                  {% } %}
              </span>
          </td>
          <td>
              {% if (window.innerWidth > 480 || !file.thumbnailUrl) { %}
                  <p class="name">
                      {% if (file.url) { %}
                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                      {% } else { %}
                          <span>{%=file.name%}</span>
                      {% } %}
                  </p>
              {% } %}
              {% if (file.error) { %}
                  <div><span class="label label-danger">Error</span> {%=file.error%}</div>
              {% } %}
          </td>
          <td>
              <span class="size">{%=o.formatFileSize(file.size)%}</span>
          </td>
          <td>
              {% if (file.deleteUrl) { %}
                  <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                      <i class="glyphicon glyphicon-trash"></i>
                      <span>Delete</span>
                  </button>
                  <input type="checkbox" name="delete" value="1" class="toggle">
              {% } else { %}
                  <button class="btn btn-warning cancel">
                      <i class="glyphicon glyphicon-ban-circle"></i>
                      <span>Cancel</span>
                  </button>
              {% } %}
          </td>
      </tr>
  {% } %}
</script>
<!-- The main application script -->
<script src="{{ asset('vendor/jquery-file-upload/js/demo.js') }}"></script>
@endsection
