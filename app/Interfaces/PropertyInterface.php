<?php

namespace App\Interfaces;

interface PropertyInterface
{
    public function getId(): int;
    public function getName(): string;
    public function getAddress(): string;
    public function getImage(): string;
    public function getCreatedAt(): string;
    public function getUpdatedAt(): string;
}
