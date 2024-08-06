<?php

namespace App\Providers;

use App\Events\AreaNameUpdated;
use App\Events\CityNameUpdated;
use App\Events\ClassifiedAdSaved;
use App\Events\ClassifiedAdDeleted;
use App\Events\NotificationCreated;
use App\Events\HelplineQuestionSaved;
use Illuminate\Support\Facades\Event;
use App\Listeners\ProcessNotification;
use Illuminate\Auth\Events\Registered;
use App\Events\HelplineQuestionDeleted;
use App\Events\BusinessUserProfileSaved;
use App\Events\BusinessUserProfileDeleted;
use App\Events\ClassifiedBrandNameUpdated;
use App\Listeners\IndexClassifiedAdToSolr;
use App\Events\HelplineCategoryNameUpdated;
use App\Listeners\ReindexClassifiedAdToSolr;
use App\Events\ClassifiedCategoryNameUpdated;
use App\Listeners\DeleteClassifiedAdFromSolr;
use App\Listeners\IndexHelplineQuestionToSolr;
use App\Listeners\ReindexHelplineQuestionToSolr;
use App\Listeners\DeleteHelplineQuestionFromSolr;
use App\Listeners\IndexBusinessUserProfileToSolr;
use App\Listeners\ReindexBusinessUserProfileToSolr;
use App\Events\BusinessDirectoryCategoryNameUpdated;
use App\Listeners\DeleteBusinessUserProfileFromSolr;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
