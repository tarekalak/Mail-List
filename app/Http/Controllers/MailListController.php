<?php

namespace App\Http\Controllers;

use App\Jobs\MailListJob;
use App\Mail\MailListMail;
use App\Models\MailList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mails=MailList::select('*')->orderby('send_date','DeSC')->paginate(0);
        return view('MailList.index',['mails'=>$mails]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users=User::all();
        return view('MailList.crup',['users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'send_to' => 'required',
            'send_title' => 'required',
            'send_body' => 'required',
        ]);
        $count=5;
        $mail_create['send_to']=$request->send_to;
        $mail_create['send_cc']=$request->send_cc;
        $mail_create['send_bcc']=($request->send_all=='true'?User::get()->pluck('email'):$request->send_bcc);
        $mail_create['send_title']=$request->send_title;
        $mail_create['send_date']=$request->send_date==null?now():$request->send_date;
        $mail_create['send_body']=$request->send_body;
        $mail_create['sender_username']=Auth::user()->name;

        // save file in databese
        if($request->has('send_file')){

        $mail_create['send_file']=$this->saveFile($request->send_file);
        }
        $mail_id=MailList::create($mail_create);
        MailListJob::dispatch($mail_id);
        return redirect(route('mailList.index'))->with(['count'=>$count]);

    }

    /**
     * Display the specified resource.
     */
    public function show(MailList $mailList)
    {
        return view('MailList.show',['mail'=>$mailList]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailList $mailList)
    {
        $users=User::all();
        $number_of_users=User::get()->count();
        $number_of_bcc=($mailList->send_bcc!=null)?count($mailList->send_bcc):0;
        $check=$number_of_bcc==$number_of_users?true:false;
        return view('MailList.crup',['mail'=>$mailList,'users'=>$users,'check'=>$check]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $mail_update['send_to']=$request->send_to;
        $mail_update['send_cc']=$request->send_cc;
        $mail_update['send_bcc']=($request->send_all=='true'?User::get()->pluck('email'):$request->send_bcc);
        $mail_update['send_title']=$request->send_title;
        $mail_update['send_body']=$request->send_body;
        $mail_update['send_date']=$request->send_date==null?now():$request->send_date;
        $mail_update['sender_username']=Auth::user()->name;
        if($request->has('send_file')){
            $this->saveFile($request->send_file);
        }
        MailList::where(['id'=>$id])->delete($mail_update);
        $mail_id=MailList::create($mail_update);
        MailListJob::dispatch($mail_id);
        return redirect(route('mailList.index'));
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailList $mailList)
    {
        MailList::where(['id'=>$mailList->id])->delete();
        return redirect(route('mailList.index'));
    }

    public function saveFile($fileFromReuqest) {
        $file_to_save=$fileFromReuqest;
        $extinsion=strtolower($file_to_save->getClientOriginalExtension());
        $file_name=time().rand(1,10000).".".$extinsion;
        $file_to_save->move(public_path('uploads'),$file_name);
        return $file_name;
    }
}
