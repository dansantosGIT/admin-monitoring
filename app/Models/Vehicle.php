<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\VehicleFactory::new();
    }

    protected $fillable = [
        'call_sign', 'vehicle_type', 'vehicle_type_other', 'vehicle_model', 'brand', 'plate_number', 'year', 'team',
        'drive_link', 'remarks', 'driver_id', 'status', 'last_known_location', 'last_updated_at',
        'next_due_date',
        'photo_path',
    ];

    protected $casts = [
        'year' => 'integer',
        'last_updated_at' => 'datetime',
        'next_due_date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    public function tasks()
    {
        return $this->hasMany(VehicleTask::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activityLogs()
    {
        return $this->hasMany(VehicleActivityLog::class)->latest();
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($builder) use ($term) {
            $builder->where('call_sign', 'like', "%{$term}%")
                ->orWhere('vehicle_type', 'like', "%{$term}%")
                ->orWhere('vehicle_model', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%")
                ->orWhere('plate_number', 'like', "%{$term}%")
                ->orWhere('team', 'like', "%{$term}%");
        });
    }

    public function scopeOfStatus($query, ?string $status)
    {
        return $status ? $query->where('status', strtolower($status)) : $query;
    }

    public function scopeOfTeam($query, ?string $team)
    {
        return $team ? $query->where('team', $team) : $query;
    }

    public function getTeamLabelAttribute(): string
    {
        return $this->team ?: 'Unassigned';
    }

    public function getStatusLabelAttribute(): string
    {
        return match (strtolower((string) $this->status)) {
            'offline' => 'Offline / Under Repair',
            'idle' => 'Idle',
            default => 'Active',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match (strtolower((string) $this->status)) {
            'offline' => 'offline',
            'idle' => 'idle',
            default => 'active',
        };
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? Storage::url($this->photo_path) : null;
    }

}