@component('mail::message')
# {{ config('app.name') }}

{!! nl2br(e($replyBody)) !!}

@component('mail::panel')
Original message from {{ $contactMessage->name }} ({{ $contactMessage->email }}):  
{{ $contactMessage->message }}
@endcomponent

Thank you,  
{{ config('app.name') }}
@endcomponent
