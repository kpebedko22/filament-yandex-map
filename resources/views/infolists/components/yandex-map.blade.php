<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div
        wire:ignore
        ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-yandex-map-entry', 'kpebedko22/filament-yandex-map') }}"
        x-data="filamentYandexMapEntry({
            apiKey: @js($getApiKey()),
            suggestApiKey: @js($getSuggestApiKey()),
            state: @js($getState()),
            lang: @js($getLang()),
            zoom: @js($getZoom()),
            center: @js($getCenter()),
            geoObjectProperties: @js($getGeoObjectProperties()),
            geoObjectOptions: @js($getGeoObjectOptions()),
            mode: @js($getMode()),
            mapEl: $refs.map,
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
