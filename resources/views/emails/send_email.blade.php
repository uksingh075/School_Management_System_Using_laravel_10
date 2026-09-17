@component('mail::message')
    Hello {{ $user->name }} {{ $user->last_name }},

    {!! $user->send_message !!}

    thanks,
    {{ config('app.name') }}
@endcomponent
