@php
use App\Enums\CardBrandEnum;

$brand = $record->brand;
$iconPath = match ($brand) {
    CardBrandEnum::VISA => 'images/card-brands/visa.svg',
    CardBrandEnum::MASTERCARD => 'images/card-brands/mastercard.svg',
    CardBrandEnum::AMERICAN_EXPRESS => 'images/card-brands/american-express.svg',
};

$iconColor = match ($brand) {
    CardBrandEnum::VISA => '#1A1F71',
    CardBrandEnum::MASTERCARD => '#EB001B',
    CardBrandEnum::AMERICAN_EXPRESS => '#006FCF',
};
@endphp

<div class="flex items-center gap-2">
    @if($iconPath)
        <img
            src="{{ asset($iconPath) }}"
            alt="{{ $brand->value }}"
            class="h-6 w-auto"
        />
    @endif
    <span class="text-sm font-medium">{{ Str::upper($brand->value) }}</span>
</div>