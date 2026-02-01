<?php
namespace App\Config;

enum GenderType: string {
    case Male = 'Male';
    case Female = 'Female';
    case Trans = 'Transgender';
    case Undecided = 'Undecided';
    case Other = 'Other';
}