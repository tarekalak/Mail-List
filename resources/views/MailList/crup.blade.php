@extends('app')
@section('title')

@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
#container {

    margin: 20px auto;
}
.ck-editor__editable[role="textbox"] {
    /* Editing area */
    min-height: 200px;
}
.ck-content .image {
    /* Block images */
    max-width: 80%;
    margin: 20px auto;
}
.scrolling-dropdown .select2-results__options {
    max-height: 200px; /* Adjust this value as needed */
    overflow-y: auto;
}
</style>


@endsection
@section('content')
<x-app-layout>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container ">
        <div class="m-5 p-3 " >


      {{-- create or update --}}
        @if (isset($mail)&&$mail->send_date>now())
        <form method="post" action="{{route('mailList.update',$mail->id)}}" enctype="multipart/form-data">
          @method('PUT')
        @else
        <form method="post" action="{{route('mailList.store') }}" enctype="multipart/form-data">
        @endif
            @csrf

            {{-- TO --}}
            <div class="row mb-3">
                <div class="col-1 mb-1">
                    <label for="to" class="form-label">To</label>
                </div>
                <div class=" col-11">
                    <select name="send_to[]" class="form-select w-100 p-2" id="to" aria-label="To"  multiple="multiple">
                        <option value="{{ Auth::user()->email }}" selected>ME</option>
                        @if(isset($mail))
                            @foreach ($users as $user)
                            <option value="{{$user->email}}"
                                @if (in_array($user->email,$mail->send_to))
                                selected
                                @endif
                                >{{ $user->name }}</option>
                            @endforeach
                        @else
                            @foreach ($users as $user)
                                <option value="{{$user->email}}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>


            {{-- CC --}}
            <div class="row mb-3">
                <div class="col-1">
                    <label for="cc" class="form-label">CC</label>
                </div>
                <div class=" col-11">
                    <select name="send_cc[]" class="form-select w-100" id="cc" aria-label="CC"  multiple="multiple" >
                        @if(isset($mail)&& $mail->send_cc!=null)
                            @foreach ($users as $user)
                            <option value="{{$user->email}}"
                                @if (in_array($user->email,$mail->send_cc))
                                    selected
                                    @endif
                                    >{{ $user->name }}</option>

                            @endforeach
                        @else
                            @foreach ($users as $user)
                                <option value="{{$user->email}}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            {{-- BCC --}}
            <div class="row mb-5">
                <div class="col-1">
                    <label for="bcc" class="form-label">BCC</label>
                </div>
                <div class=" col-11">
                    <select name="send_bcc[]" class="form-select w-100" id="bcc" aria-label="BCC"  multiple="multiple">
                        <option>Select BCC</option>
                        @if(isset($mail) && $check!=1 && $mail->send_bcc!=null)
                        @foreach ($users as $user)
                        <option value="{{$user->email}}"
                        @if (in_array($user->email,$mail->send_bcc))
                             selected
                        @endif
                        >{{ $user->name }}</option>
                        @endforeach
                        @else
                        @foreach ($users as $user)
                        <option value="{{$user->email}}">{{ $user->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    {{-- select all --}}
                    <div class=" form-check">
                        <input name="send_all" type="checkbox" class="form-check-input" id="bccCheckbox"
                        @if(isset($check) && $check==1)
                         checked value="true"
                        @endif
                         >{{-- close chechbox tage  --}}
                        <label class="form-check-label" id="messageSelect" for="bccCheckbox">Select All</label>
                        <label class="form-check-label text-success" id="messageSelectAll" style="display: none;">Select All of BCC</label>
                    </div>
                </div>
            </div>


            {{-- date --}}
            <div class="mb-3">
              <label for="send_date" class="form-label">Date of sending </label>
              <input name="send_date" type="datetime-local" class="form-control" id="send_date" placeholder="send_date" value="{{isset($mail)?$mail->send_date:(old('send_date')==null?now():old('send_date'))}}">
              </div>


            {{-- title --}}
            <div class="mb-3">
              <label for="send_title" class="form-label">subject</label>
              <input name="send_title" type="text" class="form-control" id="send_title" placeholder="subject" value="{{isset($mail)?$mail->send_title:old('send_title')}}">
            </div>


            {{-- body --}}
            <div class="mb-3" id="container">
              <label for="send_body" class="form-label">Content</label>
                  <textarea name="send_body" id="ckeditor">{{isset($mail)?$mail->send_body:old('send_body')}}</textarea>
            </div>


            {{-- file --}}
            <div class="mb-3  mb-1 row">
                <div class="col-4">
                    <label for="send_file" class="form-label">File</label>
                    <input name="send_file" class="form-control" type="file" id="send_file" value="{{isset($mail)?$mail->send_file:old('send_file')}}">
                </div>
                <div class="col-8">
                    <img src="" alt="file" id="selectedImage" height="120px" width="80px">
                </div>
            </div>
            <div class="float-right">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-send"></i></button>
                <a class="btn btn-outline-dark" href="{{ route('mailList.index') }}">Back</a>
            </div>
        </form>

      </div>
    </div>
    </x-app-layout>
@endsection

@section('js')
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('ckfinder/ckfinder.js') }}"></script>

    <script>
 $(document).ready(function() {
      // Initialize Select2 for the select elements
      $('#to').select2({
            dropdownCssClass: 'scrolling-dropdown',
            // Adjust the maximum height as needed
            dropdownCss: { maxHeight: '20px' }
        });
      $('#cc').select2({
            dropdownCssClass: 'scrolling-dropdown',
            // Adjust the maximum height as needed
            dropdownCss: { maxHeight: '20px' }
        });
      $('#bcc').select2({
            dropdownCssClass: 'scrolling-dropdown',
            // Adjust the maximum height as needed
            dropdownCss: { maxHeight: '20px' }
        });
    });

    var editor = CKEDITOR.replace('ckeditor');
    CKFinder.setupCKEditor(editor);

      // Checkbox event handler
      $('#bccCheckbox').click(function() {
        if($(this).is(":checked")) {
          $('#bcc').prop('disabled', true);
          document.getElementById('bccCheckbox').value='true';
          $('#messageSelectAll').show();
          $('#messageSelect').hide();
        } else {
          $('#bcc').prop('disabled', false);
          document.getElementById('bccCheckbox').value='false';
          $('#messageSelectAll').hide();
          $('#messageSelect').show();
        }
      });

         // Function to display preview of the selected image
    function previewImage(input) {
        var preview = document.getElementById('selectedImage');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.style.display = 'block';
                preview.setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = null;
            preview.setAttribute('src', '#');
        }
    }

    // Event listener to call the previewImage function when the file input changes
    document.getElementById('send_file').addEventListener('change', function() {
        previewImage(this);
    });






</script>
@endsection

