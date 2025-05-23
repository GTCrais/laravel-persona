<?php

namespace GTCrais\LaravelPersona\Models\Contracts;

interface Persona
{
	public function isUser(): bool;
	public function isVisitor(): bool;
}