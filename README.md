## Laravel Persona

This is NOT a first-party package. This package adds Visitor and Persona concepts to your Laravel application.

**Requirements:** Tested on Laravel 12. It should work on lower versions as well.

**Note:** Docs could use more information, but I made this package mainly for myself. If you happen to use it, hit me up, and I'll update the docs with better explanations.

---

**Visitor:** Non-registered user which still needs to be tracked and stored into the database. This is achieved using UUID stored in a cookie.

**Persona:** Umbrella model for Users and Visitors. When calling `request()->persona()`, it will return currently logged in `User` if it exists. If not, it will return currently tracked `Visitor` if it exists. If not, it will return `null`.

**Installation:**
- Run `composer require gtcrais/laravel-persona`
- Run `php artisan laravel-persona:install`
- Add `... implements GTCrais\LaravelPersona\Models\Contracts\Persona` Contract to your `User` model
- Add `use GTCrais\LaravelPersona\Models\Concerns\UserPersona` Concern to your `User` model
- Migrate the database
- Register middleware:
```
use App\Http\Middleware\AuthPersona;
use App\Http\Middleware\EnsurePersonaExists;
use App\Http\Middleware\ResolvePersona;

$middleware->appendToGroup('web', [ResolvePersona::class]);
$middleware->appendToGroup('api', [ResolvePersona::class]);
$middleware->alias(['authPersona' => AuthPersona::class]);
```
- Upon successful login:
```
use App\Http\Middleware\Concerns\InteractsWithVisitor;

...

if ($visitor = auth()->guard('web')->user()->visitor) {
    $this->setVisitorUuidCookie($visitor->uuid);
    $this->injectVisitorData(request(), $visitor);
}
```
- Upon successful registration:
```
use App\Http\Middleware\Concerns\InteractsWithVisitor;

...

$visitor = $this->optionallyCreateVisitor();

$this->associateVisitorWithUser($visitor, $user);
$this->injectVisitorData(request(), $visitor);
```