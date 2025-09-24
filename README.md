<p align="center">
<svg width="1280" height="128" viewBox="0 0 204 100" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M125 81H135.266V21H125V81Z" fill="black"/>
<path d="M148.55 81H171.688C189.996 81 203 68.5745 203 50.9575C203 33.4255 189.996 21 171.688 21H148.55V81ZM158.816 71.6383V30.3617H171.688C183.922 30.3617 192.306 38.7872 192.306 50.9575C192.306 63.2128 183.922 71.6383 171.688 71.6383H158.816Z" fill="black"/>
<path d="M0 48C0 25.3726 0 14.0589 7.02944 7.02944C14.0589 0 25.3726 0 48 0H52C74.6274 0 85.9411 0 92.9706 7.02944C100 14.0589 100 25.3726 100 48V52C100 74.6274 100 85.9411 92.9706 92.9706C85.9411 100 74.6274 100 52 100H48C25.3726 100 14.0589 100 7.02944 92.9706C0 85.9411 0 74.6274 0 52V48Z" fill="#0077FF"/>
<path d="M53.2084 72.0418C30.4167 72.0418 17.4168 56.4169 16.8752 30.4169H28.2918C28.6668 49.5002 37.0833 57.5835 43.7499 59.2501V30.4169H54.5003V46.8751C61.0836 46.1668 67.9995 38.6669 70.3328 30.4169H81.0831C79.2915 40.5835 71.7914 48.0835 66.4581 51.1668C71.7914 53.6668 80.3335 60.2085 83.5835 72.0418H71.7498C69.2082 64.1252 62.8753 58.0001 54.5003 57.1668V72.0418H53.2084Z" fill="white"/>
</svg>
</p>

<p align="center">
<a href="https://packagist.org/packages/qpkq/vk-id"><img src="https://img.shields.io/packagist/php-v/qpkq/vk-id" alt="PHP Version"></a>
<a href="https://packagist.org/packages/qpkq/vk-id"><img src="https://img.shields.io/packagist/v/qpkq/vk-id?label=stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/qpkq/vk-id"><img src="https://img.shields.io/packagist/l/qpkq/vk-id" alt="License"></a>
</p>

# VK ID Provider for Laravel Socialite

```bash
composer require qpkq/vkid
```

## Register an application

Create new application at [vk.ru](https://id.vk.ru/about/business/go).

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'VK_ID' => [
  'client_id' => env('VK_ID_CLIENT_ID'),
  'client_secret' => env('VK_ID_CLIENT_SECRET'),
  'redirect' => env('VK_ID_REDIRECT_URI')
],
```

### Add provider event listener

#### Laravel 11+

In Laravel 11, the default `EventServiceProvider` provider was removed. Instead, add the listener using the `listen` method on the `Event` facade, in your `AppServiceProvider` `boot` method.

* Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

```php
Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
    $event->extendSocialite('VK_ID', \SocialiteProviders\VKID\Provider::class);
});
```
<details>
<summary>
Laravel 10 or below
</summary>
Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \SocialiteProviders\VKID\VKIDExtendSocialite::class.'@handle',
    ],
];
```
</details>

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('VK_ID')->redirect();
```

### Returned User fields
- ``id``
- ``name``
- ``email``
- ``avatar``

### Reference

- [VK ID Reference](https://id.vk.ru/about/business/go/docs/ru/vkid/latest/methods)
