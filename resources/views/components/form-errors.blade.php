{{--
    Validation summary + flash message.
    `$errors` is shared by the web middleware group on every HTTP request;
    the null-guard keeps the component safe when a view is rendered outside
    one (console rendering, mail previews, tests).
--}}

@php $bag = $errors ?? null; @endphp

@if ($bag && $bag->any())
    <x-alert type="danger" title="Please check the form">
        <ul>
            @foreach ($bag->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

@if (session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="danger">{{ session('error') }}</x-alert>
@endif
