<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subject = $this->route('subject');
        $id = is_object($subject) ? $subject->id : $subject;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($id)],
            'name' => ['required', 'string', 'max:150'],
            'units' => ['required', 'integer', 'between:1,6'],
        ];
    }
}
