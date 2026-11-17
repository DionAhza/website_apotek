<?php

namespace App\Http\Requests\Concerns;

trait IndonesianValidationMessages
{
    /**
     * Pesan validasi umum dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'email' => 'Format :attribute tidak valid.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute melebihi batas maksimal (:max).',
            'date' => ':attribute bukan tanggal yang valid.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
        ];
    }
}
