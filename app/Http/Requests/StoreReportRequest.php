<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sekolah_id' => ['required', 'integer', 'exists:sekolah,sekolah_id'],
            'distribusi_id' => [
                'required',
                'integer',
                Rule::exists('distribusi', 'distribusi_id')
                    ->where('sekolah_id', $this->input('sekolah_id')),
            ],
            'waktu_kejadian' => ['required', 'date', 'before_or_equal:now'],
            'deskripsi' => ['required', 'string', 'min:20', 'max:2000'],
            'gejala' => ['required', 'string', 'max:1000'],
            'jumlah_terdampak' => ['required', 'integer', 'min:1', 'max:1000'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
}