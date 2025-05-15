<?php

namespace App\Livewire\Plans;

use App\Models\MikrotikProfile;
use App\Repositories\PlanRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\PlanType;
use App\Models\Setting;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;

class CreatePlan extends Component
{
    use WithModal, NotificationService;

    public $type = '';
    public $price = '';
    public $mikrotik_profile = '';
    public $availableProfiles = [];

    protected PlanRepository $planRepository;

    public function boot(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function mount()
    {
        if (Setting::get('mikrotik_enabled')) {
            $this->availableProfiles = MikrotikProfile::where('tenant_id', app()->get('tenant')->id)
                ->pluck('name')
                ->toArray();
        }
    }

    public function store()
    {
        $validated = $this->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
            'mikrotik_profile' => Setting::get('mikrotik_enabled') ? 'required|string' : 'nullable',
        ]);

        try {
            $this->planRepository->create($validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('plan-created');
            $this->notifySuccess(__('crud.plans.messages.created'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.plans.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.plans.create-plan', [
            'types' => PlanType::cases()
        ]);
    }

    #[On('open-create-plan')]
    public function open()
    {
        $this->openModal();
    }
}
