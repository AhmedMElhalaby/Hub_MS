<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Workspace;
use App\Enums\WorkspaceStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspacesApiTest extends TestCase
{
    use RefreshDatabase;
    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
        $this->actingAs($this->authUser, 'sanctum');
    }

    public function test_can_list_workspaces()
    {
        Workspace::factory()->count(3)->create();

        $response = $this->getJson('/api/workspaces');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'desk',
                            'status',
                            'status_label',
                            'created_at'
                        ]
                    ],
                    'pagination' => [
                        'total',
                        'current_page',
                        'per_page',
                        'last_page',
                        'from',
                        'to'
                    ]
                ]
            ]);
    }

    public function test_can_create_workspace()
    {
        $workspaceData = [
            'desk' => fake()->numberBetween(1, 100),
            'status' => WorkspaceStatus::Available->value
        ];

        $response = $this->postJson('/api/workspaces', $workspaceData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'desk' => $workspaceData['desk'],
                    'status' => $workspaceData['status']
                ]
            ]);

        $this->assertDatabaseHas('workspaces', [
            'desk' => $workspaceData['desk']
        ]);
    }

    public function test_can_show_workspace()
    {
        $workspace = Workspace::factory()->create();

        $response = $this->getJson("/api/workspaces/{$workspace->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $workspace->id,
                    'desk' => $workspace->desk,
                    'status' => $workspace->status->value
                ]
            ]);
    }

    public function test_can_update_workspace()
    {
        $workspace = Workspace::factory()->create();
        $updatedData = [
            'desk' => fake()->numberBetween(1, 100),
            'status' => WorkspaceStatus::Booked->value
        ];

        $response = $this->putJson("/api/workspaces/{$workspace->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'desk' => $updatedData['desk'],
                    'status' => $updatedData['status']
                ]
            ]);

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'desk' => $updatedData['desk']
        ]);
    }

    public function test_can_delete_workspace()
    {
        $workspace = Workspace::factory()->create();

        $response = $this->deleteJson("/api/workspaces/{$workspace->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);
    }

    public function test_can_list_available_workspaces()
    {
        Workspace::factory()->count(2)->create(['status' => WorkspaceStatus::Available]);
        Workspace::factory()->create(['status' => WorkspaceStatus::Booked]);

        $response = $this->getJson('/api/workspaces/available');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}