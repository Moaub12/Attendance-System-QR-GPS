<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'year_id',
        'departement_id',
        'code',
        'semester_id',
    ];
    // public function students()
    // {
    //     return $this->belongsToMany(Student::class);
    // }

    public function professors()
    {
        return $this->belongsToMany(Professor::class);
    }
    public function Year()
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

}
