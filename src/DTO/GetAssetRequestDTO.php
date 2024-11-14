<?php

declare(strict_types=1);

namespace App\DTO;

class GetAssetRequestDTO
{
    private ?int $id = null;

    private ?string $label = null;

    private ?string $currency = null;

    private ?float $value = null;

    private ?int $userId = null;

    private ?float $valueInUsd = null;

    private ?float $exchangeRate = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getValueInUsd(): float
    {
        return $this->valueInUsd;
    }

    public function setValueInUsd(float $valueInUsd): self
    {
        $this->valueInUsd = $valueInUsd;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(?float $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }
}