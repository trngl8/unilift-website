<?php

namespace App\Enum;

enum TopicStatus: string {
    case New = 'new';
    case Progress = 'progress';
    case Completed = 'completed';
}