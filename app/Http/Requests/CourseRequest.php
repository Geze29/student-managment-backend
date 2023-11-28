<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PharIo\Manifest\Requirement;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'courseCode'=>"required",
            'courseName'=>'required',
            'instructor_id'=>'integer',
            'maxCapacity'=>'integer',
            'enrollmentNumber'=>'integer',
            'fee'=>'required',
            'status'=>'',
            'classStartDate'=>'date|after:now',
            'classEndDate'=>'',
            'enrollmentType'=>'required',
            'dayTaken'=>'',
            'backgroundURL'=>'',
            'requirement'=>'',
            'description'=>'',
            'contents'=>'',
            'material'=>''
        ];
    }
    
}