<?php

namespace GTCrais\LaravelPersona\Http\Middleware\Concerns;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Cookie;
use Ramsey\Uuid\Uuid;

trait InteractsWithVisitor
{
	protected function visitorUuid()
	{
		$visitorUuid = Context::get('visitorUuid') ?: Cookie::get('visitor_uuid');

		if (!Uuid::isValid((string) $visitorUuid)) {
			$visitorUuid = Uuid::uuid4()->toString();
			$this->setVisitorUuidCookie($visitorUuid);
		}

		return $visitorUuid;
	}

	protected function setVisitorUuidCookie($visitorUuid)
	{
		Cookie::queue(Cookie::forever('visitor_uuid', $visitorUuid));
		Context::add('visitorUuid', $visitorUuid);
	}

	protected function injectVisitorData(Request $request, ?Visitor $visitor)
	{
		$request->merge([
			'_visitor' => $visitor,
			'_visitorUuid' => $this->visitorUuid()
		]);
	}

	protected function optionallyCreateVisitor($uuid = null)
	{
		return Visitor::firstOrCreate(['uuid' => $uuid ?? $this->visitorUuid()]);
	}

	protected function associateVisitorWithUser(Visitor $visitor, User $user)
	{
		$visitor->user()->associate($user);
		$visitor->save();
		$visitor->associatedWithUser($user);
	}
}