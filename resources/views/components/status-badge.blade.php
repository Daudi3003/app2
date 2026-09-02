@props(['status' => ''])

@php
    [$tone, $label] = match (strtolower((string) $status)) {
        'published', 'active', 'graded', 'completed', 'open' => ['success', ucfirst($status)],
        'pending', 'draft', 'grading', 'in_progress'         => ['warning', str_replace('_', ' ', ucfirst($status))],
        'submitted'                                          => ['info',    'Submitted'],
        'overdue', 'suspended', 'cancelled', 'closed'        => ['danger',  ucfirst($status)],
        'not_started', 'inactive'                            => ['',        str_replace('_', ' ', ucfirst($status))],
        default                                              => ['',        ucfirst((string) $status)],
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge badge--dot '.($tone ? 'badge--'.$tone : '')]) }}>
    {{ $label }}
</span>
