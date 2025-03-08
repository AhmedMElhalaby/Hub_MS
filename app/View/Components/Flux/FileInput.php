<?php

namespace App\View\Components\Flux;

use Illuminate\View\Component;

class FileInput extends Component
{
    public function __construct(
        public ?string $label = null,
        public ?string $error = null
    ) {}

    public function render()
    {
        return view('components.flux.file-input');
    }
}