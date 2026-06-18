<?php

namespace App\Http\Requests\Member;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'reserved_date' => ['required', 'date', 'after_or_equal:today'],
            'trip_type'     => ['required', Rule::in(['morning', 'afternoon', 'night'])],
            'num_people'    => ['required', 'integer', 'min:1', 'max:20'],
            'remarks'       => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'reserved_date' => '予約日',
            'trip_type'     => '便',
            'num_people'    => '人数',
            'remarks'       => '備考',
        ];
    }
}
