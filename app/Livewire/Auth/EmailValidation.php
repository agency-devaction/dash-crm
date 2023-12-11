<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class EmailValidation extends Component
{
    public string $code = '';
    public function render(): View
    {
        return view('livewire.auth.email-validation');
    }

    public function handle(): void
    {
        $this->validate([
            'code' => function (string $attribute, $value, \Closure $fail) {
                $user = auth()->user();

                if ($user === null) {
                    $fail('User is not authenticated');

                    return;
                }

                if (!is_numeric($value)) {
                    $fail('Invalid code format');

                    return;
                }

                if ($user->email_verification_code !== (int)$value) {
                    $fail('Invalid code');
                }
            },
        ]);
    }
}
