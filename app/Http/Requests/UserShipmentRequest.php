<?php

namespace App\Http\Requests;

use App\Rules\UsRule;
use App\Rules\USZipCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class UserShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to.name' => 'required|string|max:255',
            'to.street1' => 'required|string|max:255',
            'to.city' => 'required|string|max:100',
            'to.state' => ['required', 'string', 'max:2', new UsRule()],
            'to.zip' => ['required', 'string', 'max:20', new USZipCodeRule()],
            'to.country' => 'required|string|max:100',
            'to.phone' => 'required|string|max:20',
            'to.email' => 'required|email|max:255',

            'from.name' => 'required|string|max:255',
            'from.street1' => 'required|string|max:255',
            'from.city' => 'required|string|max:100',
            'from.state' => ['required', 'string', 'max:2', new UsRule()],
            'from.zip' => ['required', 'string', 'max:20', new USZipCodeRule()],
            'from.country' => 'required|string|max:100',
            'from.phone' => 'required|string|max:20',
            'from.email' => 'required|email|max:255',

            'parcel.length' => 'required|numeric|min:0',
            'parcel.width' => 'required|numeric|min:0',
            'parcel.height' => 'required|numeric|min:0',
            'parcel.weight' => 'required|numeric|min:0'
        ];
    }


    public function validatedShipment(): array
    {
        return [
            'from' => $this->validated()['from'],
            'to' => $this->validated()['to'],
            'parcel' => $this->validated()['parcel'],
        ];
    }
}
