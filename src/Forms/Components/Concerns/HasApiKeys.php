<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;

trait HasApiKeys
{
    protected Closure|string|null $apiKey = null;

    protected Closure|string|null $suggestApiKey = null;

    public function getApiKey(): string
    {
        return $this->evaluate($this->apiKey);
    }

    public function apiKey(Closure|string|null $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getSuggestApiKey(): string
    {
        return $this->evaluate($this->suggestApiKey);
    }

    public function suggestApiKey(Closure|string|null $suggestApiKey): static
    {
        $this->suggestApiKey = $suggestApiKey;

        return $this;
    }
}
