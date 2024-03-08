<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;
    protected $fillable = ['file_number','dob','image','user_id',];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_professors');
    }
}
