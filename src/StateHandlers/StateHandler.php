<?php

namespace Kpebedko22\FilamentYandexMap\StateHandlers;

interface StateHandler
{
    public function formatMagellanState(mixed $state): array;

    public function formatJsonState(array $state): array;

    public function dehydrateMagellanState(array $state): mixed;

    public function dehydrateJsonState(array $state): array;

    public function usingLatLngAttributes(string $latAttr, string $lngAttr): StateHandler;
}
