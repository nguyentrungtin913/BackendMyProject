<?php


namespace App\Transformers;


use App\Models\CodeOTP;
use League\Fractal\Manager;

class CodeOTPTransformer extends BaseTransformer
{

    public function __construct(Manager $fractal, CodeOTP $codeOTP)
    {
        parent::__construct($fractal, $codeOTP);
    }
    public function transform($data)
    {
        $transform = parent::transform($data);
        
        return $transform;
    }
}