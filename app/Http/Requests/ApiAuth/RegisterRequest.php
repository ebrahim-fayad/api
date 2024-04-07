<?php

namespace App\Http\Requests\ApiAuth;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator) {
        if ($this->is('api/*')) {
            $response= ApiResponse::sendResponse(422, 'Register Validation Errors', $validator->messages()->all());
            throw new ValidationException($validator, $response);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:225'],
            'email'=>['required','email','max:225','unique:'.User::class],
            'password'=>['required','confirmed',Rules\Password::defaults()],
        ];
    }
}
