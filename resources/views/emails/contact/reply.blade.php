@component('mail::message')
# {{ config('clinic.name') }}

{!! nl2br(e($replyBody)) !!}

@component('mail::panel')
Original message from {{ $contactMessage->name }} ({{ $contactMessage->email }}):  
{{ $contactMessage->message }}
@endcomponent

Thank you,  
{{ config('clinic.name') }}
@endcomponent
