<x-mail::message>
# Welcome to {{ config('app.name') }}!

Hello **{{ $user->name }}**,

Your account has been created by an administrator. Here are your login credentials:

<x-mail::panel>
**Email:** {{ $user->email }}

**Password:** {{ $password }}
</x-mail::panel>

<x-mail::button :url="$loginUrl">
Login Now
</x-mail::button>

**Important:** For your security, we recommend changing your password after your first login.

If you have any questions, feel free to contact our support team.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
