<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $origin
 * @property int $expiration
 * @property int $max_follows
 */
class StoreLinkRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'origin' => ['required', 'string', 'url'],
            'expiration' => ['required', 'integer'],
            'max_follows' => ['required', 'integer', 'min:0']
        ];
    }
}
