<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    //
};
?>

<div class="max-w-md mx-auto text-center">
    <div class="mb-4 text-sm text-white-600">
        {{ __('Online password reset is disabled for all accounts.') }}
    </div>

    <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">
            Contact Your Administrator
        </h2>

        <p class="text-gray-700 text-sm">
            To reset your password, please visit or contact your school administrator.
            Your account password will be reset manually for security verification.
        </p>
    </div>

    <div class="mt-4">
        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
            ← Back to Login
        </a>
    </div>
</div>
