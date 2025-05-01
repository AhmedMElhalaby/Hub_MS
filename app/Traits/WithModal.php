<?php

namespace App\Traits;

trait WithModal
{
    public $showModal = false;
    public $showFilter = false;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function triggerFilter()
    {
        $this->showFilter = !$this->showFilter;
    }
}
