<?php

namespace App\View\Components\Tabs;

use Illuminate\View\Component;

class Content extends Component
{
    public $name;
    public $active;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param bool $active
     */
    public function __construct($name, $active = false)
    {
        $this->name = $name;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tabs.content');
    }
}