<?php

namespace App\Livewire\Plans;

use App\Repositories\PlanRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\PlanType;
use App\Models\MikrotikProfile;
use App\Models\Setting;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;

class EditPlan extends Component
{
    use WithModal, NotificationService;

    public $planId;
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

    public function update()
    {
        $validated = $this->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
            'mikrotik_profile' => Setting::get('mikrotik_enabled') ? 'required|string' : 'nullable',
        ]);

        try {
            $this->planRepository->update($this->planId, $validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('plan-updated');
            $this->notifySuccess(__('crud.plans.messages.updated'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.plans.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.plans.edit-plan', [
            'types' => PlanType::cases()
        ]);
    }

    #[On('open-edit-plan')]
    public function open($planId)
    {
        $plan = $this->planRepository->findById($planId);
        $this->planId = $plan->id;
        $this->type = $plan->type->value;
        $this->price = $plan->price;
        $this->mikrotik_profile = $plan->mikrotik_profile;
        $this->openModal();
    }
}
