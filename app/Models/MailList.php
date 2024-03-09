<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailList extends Model
{
    use HasFactory;
    protected $fillable=['id','sender_username','send_to','send_cc','send_bcc','send_date','send_title','send_body','send_file'];
    protected $casts=['send_to'=>'json','send_cc'=>'json','send_bcc'=>'json'];
}
