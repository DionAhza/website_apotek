<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
{
    use IndonesianValidationMessages {
        messages as private baseMessages;
    }

    public function authorize(): bool
    {
        // Akses dijaga oleh middleware auth + admin pada route.
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'expired' => ['required', 'date'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'medicine_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama obat',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'expired' => 'Tanggal kedaluwarsa',
            'category_id' => 'Kategori',
            'medicine_image' => 'Gambar obat',
        ];
    }

    public function messages(): array
    {
        return array_merge($this->baseMessages(), [
            'medicine_image.max' => 'Ukuran gambar maksimal 2 MB.',
            'medicine_image.mimes' => 'Gambar harus berformat JPG, PNG, atau WEBP.',
        ]);
    }
}
