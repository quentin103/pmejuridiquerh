<a href="{{ $link }}" {{ $attributes->merge(['class' => 'tw-bg-[#7366FF] tw-p-2 px-3 hover:tw-bg-[#7366FF]/70  hover:tw-text-white  tw-rounded-md !tw-text-white']) }}>
    @if ($icon != '')
        <i class="fa fa-{{ $icon }} mr-1"></i>
    @endif
    {{ $slot }}
</a>
