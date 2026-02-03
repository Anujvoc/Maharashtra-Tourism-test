<?php

namespace App\Models\frontend\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\frontend\ApplicationForm\Application;
class ApplicationMovement extends Model
{
    use HasFactory;
    protected $table = 'application_movements';

    protected $fillable = [
        'application_id',
        'desk_number',
        'officer_name',
        'action',
        'action_datetime',
        'remarks'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'registration_id');
    }
}
