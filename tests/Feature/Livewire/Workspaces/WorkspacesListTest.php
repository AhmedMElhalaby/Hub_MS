<?php

namespace Tests\Feature\Livewire\Workspaces;

use App\Livewire\Workspaces\WorkspacesList;
use App\Models\User;
use App\Models\Workspace;
use App\Enums\WorkspaceStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WorkspacesListTest extends TestCase
{
    use RefreshDatabase;

    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
        $this->actingAs($this->authUser);
    }

    public function test_can_render_workspaces_list()
    {
        Workspace::factory()->count(3)->create();
        $response = $this->get('/workspaces');
        $response->assertStatus(200);
    }

    public function test_can_create_workspace()
    {
        Livewire::test(WorkspacesList::class)
            ->call('create')
            ->assertSet('workspaceId', null)
            ->set('desk', '101')
            ->set('status', WorkspaceStatus::Available)
            ->call('save')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('workspaces', [
            'desk' => '101',
            'status' => WorkspaceStatus::Available->value
        ]);
    }

    public function test_can_update_workspace()
    {
        $workspace = Workspace::factory()->create();

        Livewire::test(WorkspacesList::class)
            ->call('edit', $workspace->id)
            ->set('desk', '102')
            ->set('status', WorkspaceStatus::Booked)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('notify');

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'desk' => '102',
            'status' => WorkspaceStatus::Booked->value
        ]);
    }

    public function test_can_delete_workspace()
    {
        $workspace = Workspace::factory()->create();

        Livewire::test(WorkspacesList::class)
            ->call('confirmDelete', $workspace->id)
            ->call('delete')
            ->assertDispatched('notify');

        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);
    }

    public function test_can_search_workspaces()
    {
        $workspace1 = Workspace::factory()->create(['desk' => '101']);
        $workspace2 = Workspace::factory()->create(['desk' => '202']);

        Livewire::test(WorkspacesList::class)
            ->set('search', '101')
            ->assertSee('101')
            ->assertDontSee('202');
    }

    public function test_can_sort_workspaces()
    {
        Workspace::factory()->count(3)->create();

        Livewire::test(WorkspacesList::class)
            ->set('sortField', 'desk')
            ->set('sortDirection', 'asc')
            ->assertViewHas('workspaces');
    }

    public function test_can_filter_by_status()
    {
        $availableWorkspace = Workspace::factory()->create([
            'desk' => 'A101',
            'status' => WorkspaceStatus::Available
        ]);
        $bookedWorkspace = Workspace::factory()->create([
            'desk' => 'B892',
            'status' => WorkspaceStatus::Booked
        ]);

        $component = Livewire::test(WorkspacesList::class)
            ->set('statusFilter', WorkspaceStatus::Available->value)
            ->assertSet('statusFilter', WorkspaceStatus::Available->value);
        
        // Check that the filtered results contain the available workspace
        $component->assertSee('A101');
    }
}