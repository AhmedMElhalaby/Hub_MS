<?php

namespace App\View\Components\Tabs;

use Illuminate\View\Component;

class Tab extends Component
{
    public $name;
    public $active;
    public $icon;
    public $badge;
    public $badgeColor;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param bool $active
     * @param string|null $icon
     * @param string|null $badge
     * @param string $badgeColor
     */
    public function __construct($name, $active = false, $icon = null, $badge = null, $badgeColor = 'red')
    {
        $this->name = $name;
        $this->active = $active;
        $this->icon = $icon;
        $this->badge = $badge;
        $this->badgeColor = $badgeColor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tabs.tab');
    }
}