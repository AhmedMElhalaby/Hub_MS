<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatCard extends Component
{
    public function __construct(
        public string $title,
        public string $value,
        public ?string $description = null,
        public ?string $trend = null,
        public ?string $color = null
    ) {}

    public function render()
    {
        return view('components.stat-card');
    }
}
