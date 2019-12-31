<?php

namespace App\Http\Requests;

use App\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        $request = app('request');
        if( $user ) {
            if (!$user->isUser()) {
                return true;
            }
            if ($request->has('id')) {
                $report = Report::find($request->id);
                if ($report && $report->user_id === $user->id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Report::getValidationRules();
    }
}
