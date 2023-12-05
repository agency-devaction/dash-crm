<?php

use App\Livewire\Dev;
use App\Models\User;
use Illuminate\Support\Facades\Process;

use function Pest\Laravel\{actingAs, get};

it('should show a current branch in the page', function () {

    Process::fake([
        'git branch --show-current' => Process::result('master'),
    ]);

    Livewire::test(Dev\BranchEnv::class)
        ->assertSet('branch', 'master')
        ->assertSee('master');

    Process::assertRan('git branch --show-current');
});

it('should not load livewire component or production environment', function () {
    $user = User::factory()->create();

    actingAs($user);

    app()->detectEnvironment(fn () => 'production');

    get(route('welcome'))
        ->assertDontSeeLivewire('dev.branch-env');
});
