<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        wire:ignore
        ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-yandex-map', 'kpebedko22/filament-yandex-map') }}"
        x-data="filamentYandexMapField({
            apiKey: @js($getApiKey()),
            suggestApiKey: @js($getSuggestApiKey()),
            lang: @js($getLang()),
            zoom: @js($getZoom()),
            center: @js($getCenter()),
            geoObjectProperties: @js($getGeoObjectProperties()),
            geoObjectOptions: @js($getGeoObjectOptions()),
            isDisabled: @js($isDisabled()),
            mode: @js($getMode()),
            deleteBtnParameters: @js($getDeleteBtnParameters()),
            drawBtnParameters: @js($getDrawBtnParameters()),
            editBtnParameters: @js($getEditBtnParameters()),
            statePath: @js($getStatePath()),
            setStateUsing: (path, state) => {
                return $wire.set(path, state)
            },
            getStateUsing: (path) => {
                return $wire.get(path)
            },
            mapEl: $refs.map,
            state: $wire.entangle('{{ $getStatePath() }}'),
        })"
    >
        <div
            x-ref="map"
            class="w-full"
            style="
                height: {{ $getHeight() }};
                min-height: 20vh;
                z-index: 1 !important;
            "
        ></div>
    </div>
</x-dynamic-component>
