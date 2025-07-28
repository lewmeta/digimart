<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Password::broker('admins')->sendResetLink(
        //     $this->only('email'), 
        //     function ($user, $token) {
        //     $notification = new ResetPassword($token);
        //     $notification->createUrlUsing(function () use ($token, $user) {
        //         return route('admin.password.reset', [
        //             'token' => $token,
        //             'email' => $user->email,
        //         ]);
        //     });
        //     dd($user['name'], $notification);
        //     // Send the notification
        //     $user->notify($notification);

        // });
            Password::broker('admins')->sendResetLink(
        $this->only('email'), 
        function ($user, $token) {
            $notification = new ResetPassword($token);

            $notification->createUrlUsing(function () use ($token, $user) {
                return route('admin.password.reset', [
                    'token' => $token,
                    'email' => $user->email,
                ]);
            });

            // Send the notification
            $user->notify($notification);
            // You can dd here to confirm
            // dd('Notification sent to: ' . $user->email);
        }
    );


        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Email Address')" type="email" required autofocus
            placeholder="email@example.com" />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Email password reset link') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Or, return to') }}</span>
        <flux:link :href="route('admin.login')" wire:navigate>{{ __('log in') }}</flux:link>
    </div>
</div>
