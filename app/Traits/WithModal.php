<?php

namespace App\Traits;

trait WithModal
{
    public $showModal = false;
    public $showDeleteModal = false;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function openDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }
}
