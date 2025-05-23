<?php

namespace GTCrais\LaravelPersona\Models\Concerns;

trait UserPersona
{
	public function isUser(): bool
	{
		return true;
	}

	public function isVisitor(): bool
	{
		return false;
	}
}