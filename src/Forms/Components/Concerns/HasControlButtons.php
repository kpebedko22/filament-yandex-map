<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Kpebedko22\FilamentYandexMap\Buttons\ButtonData;
use Kpebedko22\FilamentYandexMap\Buttons\ButtonOptions;
use Closure;

trait HasControlButtons
{
    protected ButtonData|Closure|array $deleteBtnData = [];

    protected ButtonOptions|Closure|array $deleteBtnOptions = [];

    protected ButtonData|Closure|array $drawBtnData = [];

    protected ButtonOptions|Closure|array $drawBtnOptions = [];

    protected ButtonData|Closure|array $editBtnData = [];

    protected ButtonOptions|Closure|array $editBtnOptions = [];

    public function deleteBtnParameters(
        ButtonData|Closure|array    $data = [],
        ButtonOptions|Closure|array $options = [],
    ): static {
        $this->deleteBtnData = $data;
        $this->deleteBtnOptions = $options;

        return $this;
    }

    public function getDeleteBtnParameters(): array
    {
        return $this->prepareBtnParameters(
            $this->evaluate($this->deleteBtnData),
            $this->evaluate($this->deleteBtnOptions),
        );
    }

    public function drawBtnParameters(
        ButtonData|Closure|array    $data = [],
        ButtonOptions|Closure|array $options = [],
    ): static {
        $this->drawBtnData = $data;
        $this->drawBtnOptions = $options;

        return $this;
    }

    public function getDrawBtnParameters(): array
    {
        return $this->prepareBtnParameters(
            $this->evaluate($this->drawBtnData),
            $this->evaluate($this->drawBtnOptions),
        );
    }

    public function editBtnParameters(
        ButtonData|Closure|array    $data = [],
        ButtonOptions|Closure|array $options = [],
    ): static {
        $this->editBtnData = $data;
        $this->editBtnOptions = $options;

        return $this;
    }

    public function getEditBtnParameters(): array
    {
        return $this->prepareBtnParameters(
            $this->evaluate($this->editBtnData),
            $this->evaluate($this->editBtnOptions),
        );
    }

    protected function prepareBtnParameters(mixed $data, mixed $options): array
    {
        if ($data instanceof ButtonData) {
            $data = $data->toArray();
        }
        if ($options instanceof ButtonOptions) {
            $options = $options->toArray();
        }

        return [
            'data' => $data,
            'options' => $options,
        ];
    }
}
