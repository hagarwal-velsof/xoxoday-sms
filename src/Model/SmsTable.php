<?php

namespace Xoxoday\SMS\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTable extends Model
{
    use HasFactory;
    protected $fillable = ['message','message','status','mobile'];
}
