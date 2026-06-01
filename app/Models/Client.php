<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Client extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => Status::class,
    ];

    protected $fillable = [
        'name',
        'taxId',
        'status',
        'created_by',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSearch(Builder $query, array $filters): Builder
    {
        $search = trim($filters['search'] ?? '');

        return $query
        ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
        ->when(strlen($search) >= 2, fn($q) => 
            $q->where(fn($sub) => 
                $sub->where('name', 'LIKE', "{$search}%")
                    ->orWhere('taxId', 'LIKE', "%{$search}%")
            )
        );
    }
}
