<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class CodeOTP extends BaseModel
{
    public $timestamps = false;
    protected $table = 'code_otp';
    protected $primaryKey = 'code_otp_id';
    protected $fillable = [
        'code_otp_email',
        'code_otp_num',
        'code_otp_end'
    ];

    const ALIAS = [
        'code_otp_id'           => 'id',
        'code_otp_email'        => 'email',
        'code_otp_num'          => 'otp',
        'code_otp_end'          => 'timeEnd'
    ];

}
