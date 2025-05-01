<?php

namespace App\Livewire\Plans;

use App\Repositories\PlanRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\PlanType;
use App\Models\Setting;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Log;
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
            try {
                $mikrotik = new MikrotikService();
                $this->availableProfiles = collect($mikrotik->getHotspotProfiles())
                    ->pluck('name')
                    ->toArray();
            } catch (\Exception $e) {
                Log::error('Failed to fetch Mikrotik profiles', ['error' => $e->getMessage()]);
                $this->availableProfiles = [];
            }
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
            $this->notifySuccess('messages.plan.created');
        } catch (\Exception $e) {
            $this->notifyError('messages.plan.save_error');
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
