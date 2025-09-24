<?php

namespace App\Providers;

use App\Traits\NotificationTrait;
use App\Traits\UserInitialsTrait;
use App\Traits\OnboardingTrait;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Laravel\Passport\Passport;
use stdClass;

class AppServiceProvider extends ServiceProvider
{
    use NotificationTrait, UserInitialsTrait, OnboardingTrait;

    public function boot(): void
    {
        Paginator::useBootstrap();
        
        Passport::tokensExpireIn(now()->addWeek());
        Passport::refreshTokensExpireIn(now()->addWeek());
        Passport::personalAccessTokensExpireIn(now()->addWeek());

        View::composer('*', function ($view) {
            // Get data from traits
            $notificationData = $this->getNotificationData();
            $avatarData = $this->getUserAvatarData();
            $onboardingData = $this->getOnboardingData();
            
            // Settings (will be GlobalSettings model in future)
            $settings = new stdClass();

            $view->with(array_merge(
                $notificationData,
                $avatarData,
                $onboardingData,
                ['settings' => $settings]
            ));
        });
    }
}