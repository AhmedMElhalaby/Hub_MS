<?php

namespace App\View\Components\Tabs;

use Illuminate\View\Component;

class Container extends Component
{
    public $activeTab;
    public $id;

    /**
     * Create a new component instance.
     *
     * @param string|null $activeTab
     * @param string|null $id
     */
    public function __construct($activeTab = null, $id = null)
    {
        $this->activeTab = $activeTab;
        $this->id = $id ?? 'tabs-' . uniqid();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tabs.container');
    }
}
