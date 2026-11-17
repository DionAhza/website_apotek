<?php

namespace App\Models;

use Database\Factories\MedicineFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Medicine extends Model
{
    /** @use HasFactory<MedicineFactory> */
    use HasFactory;

    /** Folder di disk "public" untuk gambar yang diunggah lewat aplikasi. */
    public const IMAGE_DIR = 'medicine-images';

    /** Obat dianggap "segera kedaluwarsa" jika sisa waktunya sebanyak ini (hari). */
    public const EXPIRING_SOON_DAYS = 90;

    protected $fillable = [
        'name',
        'price',
        'description',
        'expired',
        'medicine_image',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'expired' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /* ---------------------------------------------------------------
     | Accessor
     |---------------------------------------------------------------*/

    /**
     * URL gambar. Mendukung dua sumber agar data lama tetap tampil:
     * - "medicine-images/xxx.jpg" => Laravel Storage (disk public)
     * - "nama-file.jpg"           => file lama di public/gambars
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            $path = $this->medicine_image;

            if (! $path) {
                return null;
            }

            if (str_starts_with($path, self::IMAGE_DIR.'/')) {
                return Storage::disk('public')->url($path);
            }

            return asset('gambars/'.rawurlencode($path));
        });
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::get(fn () => 'Rp '.number_format($this->price, 0, ',', '.'));
    }

    protected function isExpired(): Attribute
    {
        return Attribute::get(fn () => $this->expired->lt(today()));
    }

    protected function isExpiringSoon(): Attribute
    {
        return Attribute::get(fn () => ! $this->is_expired
            && $this->expired->lte(today()->addDays(self::EXPIRING_SOON_DAYS)));
    }

    /* ---------------------------------------------------------------
     | Scope untuk pencarian, filter, dan pengurutan
     |---------------------------------------------------------------*/

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $q) => $q->where('name', 'like', '%'.$term.'%'));
    }

    public function scopeInCategory(Builder $query, ?int $categoryId): Builder
    {
        return $query->when($categoryId, fn (Builder $q) => $q->where('category_id', $categoryId));
    }

    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'harga_asc' => $query->orderBy('price')->orderBy('id'),
            'harga_desc' => $query->orderByDesc('price')->orderBy('id'),
            'nama' => $query->orderBy('name')->orderBy('id'),
            default => $query->orderByDesc('id'),
        };
    }

    public function scopeExpiringSoon(Builder $query): Builder
    {
        return $query->whereBetween('expired', [
            today()->toDateString(),
            today()->addDays(self::EXPIRING_SOON_DAYS)->toDateString(),
        ]);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expired', '<', today()->toDateString());
    }

    /* ---------------------------------------------------------------
     | Helper file gambar
     |---------------------------------------------------------------*/

    /**
     * Hapus file gambar hanya jika berasal dari Laravel Storage
     * (file lama di public/gambars tidak pernah disentuh).
     */
    public static function deleteStoredFile(?string $path): void
    {
        if ($path && str_starts_with($path, self::IMAGE_DIR.'/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
