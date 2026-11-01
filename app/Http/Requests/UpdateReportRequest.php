<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('laporan'));
    }

    public function rules(): array
    {
        return [
            'waktu_kejadian' => ['required', 'date', 'before_or_equal:now'],
            'deskripsi' => ['required', 'string', 'min:20', 'max:2000'],
            'gejala' => ['required', 'string', 'max:1000'],
            'jumlah_terdampak' => ['required', 'integer', 'min:1', 'max:1000'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
}