<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incident_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'incident_code',
        'employee_id',
        'department',
        'incident_type',
        'item_name',
        'property_serial_no',
        'description',
        'location',
        'date_of_incident',
        'severity',
        'estimated_cost',
        'status',
        'action_taken',
        'reported_by',
        'remarks',
        'attachment_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_incident' => 'date',
        'estimated_cost' => 'decimal:2',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee tied to the incident report.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who filed the report.
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get attachments for the incident report.
     */
    public function attachments()
    {
        return $this->hasMany(IncidentAttachment::class, 'incident_report_id');
    }

    /**
     * Scope to filter by a search term.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($builder) use ($term) {
            $builder->where('incident_code', 'like', "%{$term}%")
                ->orWhere('department', 'like', "%{$term}%")
                ->orWhere('incident_type', 'like', "%{$term}%")
                ->orWhere('item_name', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")
                ->orWhereHas('employee', function ($employeeQuery) use ($term) {
                    $employeeQuery->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('employee_number', 'like', "%{$term}%");
                });
        });
    }

    /**
     * Scope to filter by incident type.
     */
    public function scopeOfType($query, ?string $type)
    {
        return $type ? $query->where('incident_type', $type) : $query;
    }

    /**
     * Scope to filter by severity.
     */
    public function scopeOfSeverity($query, ?string $severity)
    {
        return $severity ? $query->where('severity', $severity) : $query;
    }

    /**
     * Scope to filter by status.
     */
    public function scopeOfStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    /**
     * Human readable employee name.
     */
    public function getEmployeeNameAttribute(): string
    {
        $employee = $this->employee;

        if (! $employee) {
            return 'Unassigned';
        }

        return trim(implode(' ', array_filter([
            $employee->first_name,
            $employee->middle_name,
            $employee->last_name,
            $employee->suffix,
        ])));
    }

    /**
     * Human readable incident number.
     */
    public function getDisplayCodeAttribute(): string
    {
        return $this->incident_code ?: 'Pending';
    }
}
