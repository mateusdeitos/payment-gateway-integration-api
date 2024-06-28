<?php

namespace App\Connector\Payment\ACI\Model;

class RiskResponseModel {
    protected ?string $score = null;

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;
        return $this;
    }
}
