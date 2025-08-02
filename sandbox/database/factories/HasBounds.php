<?php

namespace Database\Factories;

trait HasBounds
{
    private float $latNw = -90;

    private float $lngNw = -180;

    private float $latSe = 90;

    private float $lngSe = 180;

    public function lat(): float
    {
        return fake()->latitude($this->latNw, $this->latSe);
    }

    public function lng(): float
    {
        return fake()->longitude($this->lngNw, $this->lngSe);
    }

    public function inBounds(
        float $latNw,
        float $lngNw,
        float $latSe,
        float $lngSe,
    ): self {
        $this->latNw = $latNw;
        $this->lngNw = $lngNw;
        $this->latSe = $latSe;
        $this->lngSe = $lngSe;

        return $this;
    }
}
