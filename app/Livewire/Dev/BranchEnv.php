<?php

namespace App\Livewire\Dev;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Process;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read string $branch
 * @property-read string $env
 */
class BranchEnv extends Component
{
    public function render(): View
    {
        return view('livewire.dev.branch-env');
    }

    #[Computed]
    public function branch(): string
    {
        return trim(Process::run('git branch --show-current')->output());
    }

    #[Computed]
    public function env(): string
    {
        return app()->environment();
    }
}
