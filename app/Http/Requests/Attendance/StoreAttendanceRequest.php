<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('attendance-create') ?? false;
    }

    public function rules(): array
    {
        return [
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'attendance_date' => ['required', 'date_format:Y-m-d'],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('attendances', 'attendance_date')
                    ->where(fn ($query) => $query->where('user_id', $this->input('user_id'))),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $start = $this->input('start_time');
            $end = $this->input('end_time');

            if ($start && $end && $start === $end) {
                $validator->errors()->add('end_time', 'End time must be different from start time.');
            }
        });
    }
}
