<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentAttachment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'incident_report_id',
        'file_path',
        'original_filename',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function incidentReport()
    {
        return $this->belongsTo(Report::class, 'incident_report_id');
    }
}