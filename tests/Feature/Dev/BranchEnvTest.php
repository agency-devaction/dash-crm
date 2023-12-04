<?php

use App\Livewire\Dev;
use Illuminate\Support\Facades\Process;

it('should show a current branch in the page', function () {

    Process::fake([
        'git branch --show-current' => Process::result('master'),
    ]);

    Livewire::test(Dev\BranchEnv::class)
        ->assertSet('branch', 'master')
        ->assertSee('master');

    Process::assertRan('git branch --show-current');
});
