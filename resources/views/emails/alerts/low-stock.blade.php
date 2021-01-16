@component('mail::message')
# Low Stock Alert

Low stock alert for dish {{ $name }}. Remaining stock {{ $quantity }}.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
