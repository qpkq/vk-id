<?php

namespace SocialiteProviders\VKID;

use SocialiteProviders\Manager\SocialiteWasCalled;

class VKIDExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite(Provider::IDENTIFIER, Provider::class);
    }
}
