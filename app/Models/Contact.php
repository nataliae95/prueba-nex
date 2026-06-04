<?php

namespace App\Models;

use App\Enums\Position;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory, HasAudit;

    protected $casts = [
        'position' => Position::class,
    ];

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'position',
        'is_primary',
        'created_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::saving(function ($contact) {
            if ($contact->isDirty('is_primary') && $contact->is_primary) {
                static::where('client_id', $contact->client_id)
                    ->whereKeyNot($contact->getKey())
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });
    }

    public function scopeSearch(Builder $query, array $filters): Builder
    {
        $search = trim($filters['search'] ?? '');

        return $query
            ->when($filters['position'] ?? null, fn($q, $position) => $q->where('position', $position))
            ->when(
                strlen($search) >= 2,
                fn($q) =>
                $q->where(
                    fn($sub) =>
                    $sub->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                )
            );
    }
}
