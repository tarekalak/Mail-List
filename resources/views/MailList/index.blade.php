@extends('app')
@section('title')

@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

@endsection
@section('content')

    <div class="m-3">
    <h5 class="mt-3">Email Details</h5>
    <h5 class="mt-3"><a class="btn btn-outline-primary" href="{{ route('mailList.create') }}">New message</a></h5>
    </div>
    <div class="row m-3">
        @foreach ($mails as $mail)
        <a class="divShow" href="{{ route('mailList.show',$mail->id) }}">
        <div class=" col-md-6 col-lg-4 col-xl-3">
            <div class="card my-3 article">
                <div class="card-header ">

                    @if ($mail->send_date > now())
                    <div class="row">
                        <p class="text-warning">Waiting... </p>

                        <div class="spinner mx-2 border border-warning">
                            <div class="hand bg-warning"></div>
                        </div>
                    </div>
                    @else
                    <p class="text-success">Sent... <span class="fa fa-lg fa-check-circle-o "></span></p>
                    @if(session('job_failed_message'))
    <div class="alert alert-danger">
        {{ session('job_failed_message') }}
    </div>
@endif
                    @endif
                </div>
                <div class="card-body">
                    <p><strong>Sender : </strong>{{ $mail->sender_username }}</p>
                    <p><strong>Subject : </strong>{{ $mail->send_title }}</p>

                    <p>
                        @if ($mail->send_date > now())
                        <strong>send date : </strong>
                        <lable class="text-warning">{{ $mail->send_date }}</label>
                        @else
                        <strong>send date : </strong>
                        <label class="text-success">{{ $mail->send_date }}</label>
                        @endif
                    </p>
                </div>
                <div class="row m-3">
                    <div class="mx-1">
                        @if($mail->send_date > now())
                            <a href="{{ route('mailList.edit',$mail->id) }}" class="btn btn-outline-warning mt-3"><i class="fa fa-edit"></i></a>
                        @else
                            <a href="{{ route('mailList.edit',$mail->id) }}" class="btn btn-outline-success mt-3"><i class="fa fa-mail-forward"></i></a>
                        @endif
                    </div>
                    <div class="mx-1">
                        <a href="{{ route('mailList.show',$mail->id) }}" class="btn btn-outline-primary mt-3">show</a>
                    </div>
                    <div class="mx-1">
                        <form method="POST" action="{{ route('mailList.destroy',$mail->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger mt-3">delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </a>
        @endforeach
    </div>

@endsection
