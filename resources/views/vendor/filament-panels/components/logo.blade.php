<?php

use App\Models\Settings;

$settings = Settings::first();

?>

@if ($settings?->logo)
    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-10" height="10">
@else
    <img src="{{ asset('images/logo-nameque.png') }}" alt="Logo" class="h-10" height="10">
@endif
