<?php

namespace App\Enums;

enum Specialization: string
{
    case Student = 'student';
    case Teacher = 'teacher';
    case Developer = 'developer';
    case Designer = 'designer';
}
