@component('mail::message')
# Hey {{ $user->name ?? 'there' }} 👋

Thanks for signing up at **HORIZON**!

Please click the button below to verify your email and start exploring.

@component('mail::button', ['url' => $actionUrl])
Activate My Account
@endcomponent

Didn't create an account? No problem — just ignore this message.

Thanks,<br>
The HORIZON Team
@endcomponent
