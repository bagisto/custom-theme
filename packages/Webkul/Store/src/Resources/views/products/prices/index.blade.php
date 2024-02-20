@if ($prices['final']['price'] < $prices['regular']['price'])
    <p class="font-medium text-[#6E6E6E] line-through">
        {{ $prices['regular']['formatted_price'] }}
    </p>

    <p class="font-semibold">
        {{ $prices['final']['formatted_price'] }}
    </p>
@else
    <p class="font-semibold text-[#cf3917]">
        {{ $prices['regular']['formatted_price'] }}
    </p>
@endif