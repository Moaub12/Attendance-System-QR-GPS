<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_number', 'dob', 'image', 'user_id', 'year_id',
        'departement_id',
        'semester_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    // public function courses()
    // {
    //     return $this->belongsToMany(Student::class);
    // }
}
