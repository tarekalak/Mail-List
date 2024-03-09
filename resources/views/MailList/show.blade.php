@extends('app')
@section('title')
 show
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endsection
@section('content')
<div class="container">
<div class="card my-4">
    <div class="card-header">

        @if ($mail->send_date > now())
        <div class="row">
            <p class="text-warning">Waiting... </p>
            <div class="spinner mx-2 border border-warning">
                <div class="hand bg-warning"></div>
            </div>
        </div>
        @else
        <p class="text-success">Sent... <span class="fa fa-lg fa-check-circle-o "></span></p>
        @endif
    </div>

    <div class="card-body">

        <p><strong>To:</strong></p>
        <div>
        @foreach ( $mail->send_to as $to)
            {{ $to }},
        @endforeach
        </div>
        <p><strong>CC:</strong></p>
        <p>
            @if($mail->send_cc!=null)
            @foreach ( $mail->send_cc as $cc)
                {{ $cc }},
            @endforeach
            @else
            Not entered
            @endif
        </p>
        <p><strong>BCC:</strong></p>
        <p>
            @if($mail->send_bcc!=null)
            @foreach ( $mail->send_bcc as $bcc)
                {{ $bcc }},
            @endforeach
            @else
            Not entered
            @endif
        </p>
        <p><strong>Sender: </strong></p>
        <p>{{ $mail->sender_username }}</p>
        <p><strong>Subject: </strong></p>
        <p>{{ $mail->send_title }}</p>
        <p><strong>Message: </strong></p>
        <p>{!! $mail->send_body !!}</p>
        <p><strong>file: </strong></p>
        <embed src="{{asset('uploads/'. $mail->send_file)}}" width="500px" height="300px">
        <p><strong>send date:</strong>
            @if($mail->send_date > now() )
            <strong class="text-warning">{{ $mail->send_date }}</strong>
            @else
            <strong class="text-success">{{ $mail->send_date }}</strong>
            @endif
        </p>
    </div>
    <div class="mb-3">
        <a href="{{ route('mailList.index') }}" class="btn btn-secondary m-3 mx-5 float-right">back</i></a>

    </div>
</div>
@endsection
