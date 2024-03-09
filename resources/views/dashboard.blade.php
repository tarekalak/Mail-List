@extends('app')
@section('title')

@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<x-app-layout>

    <div class="container ">
        <a href="{{ route('mailList.index') }}" class="btn btn-success "><div class="m-5 p-5">Mails</div></a>
      </div>
    </x-app-layout>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
 $(document).ready(function() {
      // Initialize Select2 for the select elements
      $('#to').select2();
      $('#cc').select2();
      $('#bcc').select2();

      // Checkbox event handler
      $('#bccCheckbox').change(function() {
        if($(this).is(":checked")) {
          $('#bcc').prop('disabled', true);
        } else {
          $('#bcc').prop('disabled', false);
        }
      });
    });
</script>
@endsection

