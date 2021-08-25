<?php


namespace App\Validators;

use Illuminate\Http\Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Underscore\Types\Strings;
use Respect\Validation\Validator;
class BaseValidator
{
    protected $errors;

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
    
    public function setError($statusCode = 400, $code = null, $msg = null, $clientMsg = null)
    {
        $this->errors[$statusCode][] = compact('code', 'msg', 'clientMsg');
    }

    public function getErrors()
    {
        if ($this->errors) {
            foreach ($this->errors as $status => $errors) {
                return compact('status', 'errors');
            }
        }
        return $this->errors;
    }

   public function requireParam($key,
    $mess)
   {
      if ($this->request->get($key) === null || $this->request->get($key) === '') {
            $this->setError(400, 'missing_param', "Missing param: `{$key}`", $mess ? $mess : 'Please enter your ' . str_replace('_', ' ', Strings::toSnakeCase($key)));
            return false;
        }
       return true;
   }

   public function checkNumeric($key, $mess)
   {
        if (!is_numeric($this->request->get($key)) || $this->request->get($key) <= 0) {
            $this->setError(400, 'invalid_param', "Invalid param: `{$key}`", $mess ? $mess : 'Please enter number your ' . str_replace('_', ' ', Strings::toSnakeCase($key)));
            return false;
        }
       return true;
   }

    public function checkExitFile($image)
    {
        if (!file_exists($image)) 
        {
            $this->setError(400, 'not exit', "file not exit", 'Error');
            return false;
        }
       return true;
    }

}