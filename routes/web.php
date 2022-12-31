<?php

    Route::get('migrate', function() {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh -—seed');
    });

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

//-----------------------------------------//
//             SITEMAP ROUTES              //
//-----------------------------------------//
// Sitemap & Robots
Route::get('/sitemap.xml', 'SitemapController@index')->name('sitemap');
Route::get('/sitemap_blogs.xml', 'SitemapController@blogs')->name('sitemap.blogs');
Route::get('/sitemap_events.xml', 'SitemapController@events')->name('sitemap.events');
Route::get('/sitemap_groups.xml', 'SitemapController@groups')->name('sitemap.groups');
Route::get('/sitemap_pages.xml', 'SitemapController@pages')->name('sitemap.pages');
Route::get('/sitemap_topics.xml', 'SitemapController@topics')->name('sitemap.topics');
Route::get('/robots.txt', 'SitemapController@robots')->name('robots');

//-----------------------------------------//
//             INSTALLER ROUTES            //
//-----------------------------------------//
Route::group(['namespace' => 'Installer'], function () {
    Route::get('/install', 'InstallController@welcome')->name('installer.welcome');
    Route::get('/install/requirements', 'InstallController@requirements')->name('installer.requirements');
    Route::get('/install/permissions', 'InstallController@permissions')->name('installer.permissions');
    Route::get('/install/environment', 'InstallController@environment')->name('installer.environment');
    Route::post('/install/environment/save', 'InstallController@save_environment')->name('installer.environment.save');
    Route::get('/install/database', 'InstallController@database')->name('installer.database');
    Route::get('/install/final', 'InstallController@finish')->name('installer.final');

    // Updated
    Route::get('/update', 'UpdateController@welcome')->name('updater.welcome');
    Route::get('/update/overview', 'UpdateController@overview')->name('updater.overview');
    Route::get('/update/database', 'UpdateController@database')->name('updater.database');
    Route::get('/update/final', 'UpdateController@finish')->name('updater.final');
});

//-----------------------------------------//
//              PAGE ROUTES                //
//-----------------------------------------//
// Landing
Route::get('/', 'IndexController@index')->name('home');
Route::get('/demo', 'IndexController@demo')->name('demo');
Route::get('/change-language/{locale}', 'IndexController@change_language')->name('change_language');

// Blog Pages
Route::get('/blog', 'Application\BlogController@index')->name('blog');
Route::get('/blog/tags/{blog_category}', 'Application\BlogController@tags')->name('blog.tags');
Route::get('/blog/{blog}', 'Application\BlogController@show')->name('blog.show');

// Static Pages
Route::get('/pages/{page}', 'Application\PageController@show')->name('page.show');

// Event & Group Search Page
Route::get('/find', 'Application\FindController@index')->name('find');

// Topics
Route::get('/topics', 'Application\TopicController@index')->name('topics');

//-----------------------------------------//
//             CHECKOUT ROUTES             //
//-----------------------------------------//
Route::group(['namespace' => 'Application\Checkout', 'middleware' => 'auth'], function () {
    Route::get('/checkout/plans', 'CheckoutController@plans')->name('checkout.plans');
    Route::get('/checkout/payment', 'CheckoutController@payment')->name('checkout.payment');

    // Dummy Payment
    Route::get('/checkout/dummy/payment', 'Dummy\PaymentController@payment')->name('checkout.dummy.payment');

    // Stripe
    Route::post('/checkout/stripe/payment', 'Stripe\PaymentController@payment')->name('checkout.stripe.payment');
    Route::get('/checkout/stripe/callback', 'Stripe\PaymentController@callback')->name('checkout.stripe.callback');
    Route::get('/checkout/stripe/remove-payment-method', 'Stripe\PaymentController@remove_payment_method')->name('checkout.stripe.remove_payment_method');
});

//-----------------------------------------//
//             WEBHOOKS ROUTES             //
//-----------------------------------------//
Route::post('/webhooks/stripe', 'Application\Checkout\Stripe\WebhookController@handleWebhook')->name('webhooks.stripe');
Route::post('/webhooks/paypal/{token?}', 'Application\Checkout\Paypal\WebhookController@handleWebhook')->name('webhooks.paypal');

//-----------------------------------------//
//              AUTH ROUTES                //
//-----------------------------------------//
Route::impersonate();
Route::middleware(ProtectAgainstSpam::class)->group(function () {
    Auth::routes();
    Route::get('/auth/{provider}/redirect', 'Auth\LoginController@redirectToProvider')->name('social_login.redirect');
    Route::get('/auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social_login.callback');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});

//-----------------------------------------//
//              GROUP ROUTES               //
//-----------------------------------------//
Route::group(['namespace' => 'Application\Group'], function () {
    // Start a Group
    Route::get('/start', 'StartController@index')->name('start.index');
    Route::get('/start/group', 'StartController@create')->middleware('auth')->name('start.create');

    // Group Profile
    Route::get('/g/{group}', 'AboutController@index')->name('groups.about');

    // Group Members
    Route::get('/g/{group}/members', 'MemberController@index')->name('groups.members');

    // Group Photos
    Route::get('/g/{group}/photos', 'PhotoController@index')->name('groups.photos');
    Route::post('/g/{group}/photos', 'PhotoController@store')->middleware(['auth', 'blocked_at_demo'])->name('groups.photos.store');
    Route::get('/g/{group}/photos/{photo}/delete', 'PhotoController@delete')->middleware(['auth', 'blocked_at_demo'])->name('groups.photos.delete');

    // Group Discussions
    Route::get('/g/{group}/discussions', 'DiscussionController@index')->name('groups.discussions');
    Route::post('/g/{group}/discussions', 'DiscussionController@store')->middleware(['auth', 'blocked_at_demo'])->name('groups.discussions.store');
    Route::get('/g/{group}/discussions/{discussion}', 'DiscussionController@details')->name('groups.discussions.details');
    Route::get('/g/{group}/discussions/{discussion}/delete', 'DiscussionController@delete')->middleware(['auth', 'blocked_at_demo'])->name('groups.discussions.delete');
    
    // Discussion Comments
    Route::post('/g/{group}/discussions/{discussion}/comments', 'DiscussionCommentController@store')->middleware(['auth', 'blocked_at_demo'])->name('groups.discussions.comments.store');
    Route::get('/g/{group}/discussions/{discussion}/comments/{comment}/delete', 'DiscussionCommentController@delete')->middleware(['auth', 'blocked_at_demo'])->name('groups.discussions.comments.delete');

    // Group Events
    Route::get('/g/{group}/events', 'EventController@upcoming_events')->name('groups.events');
    Route::get('/g/{group}/events/draft', 'EventController@draft_events')->middleware('auth')->name('groups.events.draft');
    Route::get('/g/{group}/events/past', 'EventController@past_events')->name('groups.events.past');

    Route::get('/g/{group}/events/create', 'EventController@create')->middleware('auth')->name('groups.events.create');
    Route::get('/g/{group}/events/{event}', 'EventController@show')->name('groups.events.show');
    Route::get('/g/{group}/events/{event}/edit', 'EventController@edit')->middleware('auth')->name('groups.events.edit');
    Route::get('/g/{group}/events/{event}/close-rsvp', 'EventController@close_rsvp')->middleware(['auth', 'blocked_at_demo'])->name('groups.events.close_rsvp');
    Route::get('/g/{group}/events/{event}/open-rsvp', 'EventController@open_rsvp')->middleware(['auth', 'blocked_at_demo'])->name('groups.events.open_rsvp');
    Route::post('/g/{group}/events/{event}/cancel', 'EventController@cancel')->middleware(['auth', 'blocked_at_demo'])->name('groups.events.cancel');
    Route::post('/g/{group}/events/{event}/announce', 'EventController@announce')->middleware(['auth', 'blocked_at_demo'])->name('groups.events.announce');
    Route::get('/g/{group}/events/{event}/attendees', 'EventController@attendees')->middleware(['auth'])->name('groups.events.attendees');
    Route::get('/g/{group}/events/{event}/attendees/csv', 'EventController@export_attendees')->middleware(['auth', 'blocked_at_demo'])->name('groups.events.attendees.csv');

    // Group Settings
    Route::group(['namespace' => 'Settings', 'middleware' => 'auth'], function () {
        // >> Basic Settings
        Route::get('/g/{group}/settings', 'BasicSettingsController@index')->name('groups.settings');
        Route::post('/g/{group}/settings', 'BasicSettingsController@update')->middleware(['blocked_at_demo'])->name('groups.settings.basic.update');
        Route::get('/g/{group}/settings/delete', 'BasicSettingsController@delete_view')->name('groups.settings.basic.delete_view');
        Route::post('/g/{group}/settings/delete', 'BasicSettingsController@delete')->middleware(['blocked_at_demo'])->name('groups.settings.basic.delete');

        // >> Member Settings
        Route::get('/g/{group}/settings/members', 'MemberSettingsController@index')->name('groups.settings.members');
        Route::post('/g/{group}/settings/members', 'MemberSettingsController@update')->middleware(['blocked_at_demo'])->name('groups.settings.members.update');

        // >> Topic Settings
        Route::get('/g/{group}/settings/topics', 'TopicSettingsController@index')->name('groups.settings.topics');

        // >> Content Visibility Settings
        Route::get('/g/{group}/settings/content-visibility', 'ContentVisibilitySettingsController@index')->name('groups.settings.content_visibility');
        Route::post('/g/{group}/settings/content-visibility', 'ContentVisibilitySettingsController@update')->middleware(['blocked_at_demo'])->name('groups.settings.content_visibility.update');

        // >> Optional Settings
        Route::get('/g/{group}/settings/optional', 'OptionalSettingsController@index')->name('groups.settings.optional');
        Route::post('/g/{group}/settings/optional', 'OptionalSettingsController@update')->middleware(['blocked_at_demo'])->name('groups.settings.optional.update');

        // >> Integrations Settings
        Route::get('/g/{group}/settings/integrations', 'IntegrationSettingsController@index')->name('groups.settings.integrations');
        Route::get('/g/{group}/settings/integrations/{integration}', 'IntegrationSettingsController@details')->name('groups.settings.integrations.details');
        Route::post('/g/{group}/settings/integrations/{integration}', 'IntegrationSettingsController@details_update')->middleware(['blocked_at_demo'])->name('groups.settings.integrations.details.update');
 
        // >> Sponsor Settings
        Route::get('/g/{group}/settings/sponsors', 'SponsorSettingsController@index')->name('groups.settings.sponsors');
        Route::get('/g/{group}/settings/sponsors/new', 'SponsorSettingsController@create')->name('groups.settings.sponsors.create');
        Route::post('/g/{group}/settings/sponsors/new', 'SponsorSettingsController@store')->middleware(['blocked_at_demo'])->name('groups.settings.sponsors.store');
        Route::get('/g/{group}/settings/sponsors/{sponsor}/edit', 'SponsorSettingsController@edit')->name('groups.settings.sponsors.edit');
        Route::post('/g/{group}/settings/sponsors/{sponsor}/edit', 'SponsorSettingsController@update')->middleware(['blocked_at_demo'])->name('groups.settings.sponsors.update');
        Route::get('/g/{group}/settings/sponsors/{sponsor}/delete', 'SponsorSettingsController@delete')->middleware(['blocked_at_demo'])->name('groups.settings.sponsors.delete');
    });
});

//-----------------------------------------//
//       ACCOUNT & PROFILE ROUTES          //
//-----------------------------------------//
// Profile
Route::get('/members/{user:username}', 'Application\Account\ProfileController@index')->name('profile');

// My Events
Route::get('/events', 'Application\Account\EventController@index')->middleware('auth')->name('events');

// My Groups
Route::get('/groups', 'Application\Account\GroupController@index')->middleware('auth')->name('groups');

// Account Settings
Route::group(['namespace' => 'Application\Account\Settings', 'middleware' => 'auth'], function () {
    // Account Settings
    Route::get('/account/general', 'GeneralSettingsController@index')->name('account.settings.general');
    Route::get('/account/general/details', 'GeneralSettingsController@details')->name('account.settings.general.details');
    Route::post('/account/general/details', 'GeneralSettingsController@details_update')->middleware(['blocked_at_demo'])->name('account.settings.general.details.update');
    Route::get('/account/general/address', 'GeneralSettingsController@address')->name('account.settings.general.address');
    Route::post('/account/general/address', 'GeneralSettingsController@address_update')->middleware(['blocked_at_demo'])->name('account.settings.general.address.update');
    Route::get('/account/general/social', 'GeneralSettingsController@social')->name('account.settings.general.social');
    Route::post('/account/general/social', 'GeneralSettingsController@social_update')->middleware(['blocked_at_demo'])->name('account.settings.general.social.update');
    Route::get('/account/general/password', 'GeneralSettingsController@password')->name('account.settings.general.password');
    Route::post('/account/general/password', 'GeneralSettingsController@password_update')->middleware(['blocked_at_demo'])->name('account.settings.general.password.update');

    // Interests
    Route::get('/account/interests', 'InterestSettingsController@index')->name('account.settings.interests');

    // Notification Settings
    Route::get('/account/notifications', 'NotificationSettingsController@index')->name('account.settings.notifications');
    Route::post('/account/notifications', 'NotificationSettingsController@update')->middleware(['blocked_at_demo'])->name('account.settings.notifications.update');

    // Privacy Settings
    Route::get('/account/privacy', 'PrivacySettingsController@index')->name('account.settings.privacy');
    Route::post('/account/privacy', 'PrivacySettingsController@update')->middleware(['blocked_at_demo'])->name('account.settings.privacy.update');

    // Organizer Settings
    Route::get('/account/organizer', 'OrganizerSettingsController@index')->name('account.settings.organizer');
    Route::get('/account/organizer/payment-history', 'OrganizerSettingsController@payment_history')->name('account.settings.organizer.payment_history');
    Route::get('/account/organizer/payment-methods', 'OrganizerSettingsController@payment_methods')->name('account.settings.organizer.payment_methods');
    Route::get('/account/organizer/cancel', 'OrganizerSettingsController@cancel')->name('account.settings.organizer.cancel');
    Route::post('/account/organizer/cancel', 'OrganizerSettingsController@cancel_store')->middleware(['blocked_at_demo'])->name('account.settings.organizer.cancel_store');
    Route::get('/account/organizer/activate', 'OrganizerSettingsController@activate')->middleware(['blocked_at_demo'])->name('account.settings.organizer.activate');
});

//-----------------------------------------//
//              ADMIN ROUTES               //
//-----------------------------------------//
Route::group(['namespace' => 'Admin', 'prefix' => '/admin', 'middleware' => ['auth', 'admin', 'impersonate.protect']], function () {
    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');

    // Plans
    Route::get('/plans', 'PlanController@index')->name('admin.plans');
    Route::get('/plans/create', 'PlanController@create')->name('admin.plans.create');
    Route::post('/plans/create', 'PlanController@store')->middleware(['blocked_at_demo'])->name('admin.plans.store');
    Route::get('/plans/{plan}/edit', 'PlanController@edit')->name('admin.plans.edit');
    Route::post('/plans/{plan}/edit', 'PlanController@update')->middleware(['blocked_at_demo'])->name('admin.plans.update');
    Route::get('/plans/{plan}/delete', 'PlanController@delete')->middleware(['blocked_at_demo'])->name('admin.plans.delete');

    // Orders
    Route::get('/orders', 'OrderController@index')->name('admin.orders');

    // Subscriptions
    Route::get('/subscriptions', 'SubscriptionController@index')->name('admin.subscriptions');
    Route::get('/subscriptions/{subscription}/cancel', 'SubscriptionController@cancel')->middleware(['blocked_at_demo'])->name('admin.subscriptions.cancel');

    // Groups
    Route::get('/groups', 'GroupController@index')->name('admin.groups');
    Route::get('/groups/{group}/delete', 'GroupController@delete')->middleware(['blocked_at_demo'])->name('admin.groups.delete');

    // Events
    Route::get('/events', 'EventController@index')->name('admin.events');
    Route::get('/events/{event}/delete', 'EventController@delete')->middleware(['blocked_at_demo'])->name('admin.events.delete');

    // Topic Categories
    Route::get('/topic-categories', 'TopicCategoryController@index')->name('admin.topic_categories');
    Route::get('/topic-categories/create', 'TopicCategoryController@create')->name('admin.topic_categories.create');
    Route::post('/topic-categories/create', 'TopicCategoryController@store')->middleware(['blocked_at_demo'])->name('admin.topic_categories.store');
    Route::get('/topic-categories/{topic_category}/edit', 'TopicCategoryController@edit')->name('admin.topic_categories.edit');
    Route::post('/topic-categories/{topic_category}/edit', 'TopicCategoryController@update')->middleware(['blocked_at_demo'])->name('admin.topic_categories.update');
    Route::get('/topic-categories/{topic_category}/delete', 'TopicCategoryController@delete')->middleware(['blocked_at_demo'])->name('admin.topic_categories.delete');
    Route::get('/topic-categories/delete-demo', 'TopicCategoryController@delete_demo_topic_categories')->middleware(['blocked_at_demo'])->name('admin.topic_categories.delete_demo_topic_categories');

    // Topics
    Route::get('/topics', 'TopicController@index')->name('admin.topics');
    Route::get('/topics/create', 'TopicController@create')->name('admin.topics.create');
    Route::post('/topics/create', 'TopicController@store')->middleware(['blocked_at_demo'])->name('admin.topics.store');
    Route::get('/topics/{topic}/edit', 'TopicController@edit')->name('admin.topics.edit');
    Route::post('/topics/{topic}/edit', 'TopicController@update')->middleware(['blocked_at_demo'])->name('admin.topics.update');
    Route::get('/topics/{topic}/delete', 'TopicController@delete')->middleware(['blocked_at_demo'])->name('admin.topics.delete');
    Route::get('/topics/delete-demo', 'TopicController@delete_demo_topics')->middleware(['blocked_at_demo'])->name('admin.topics.delete_demo_topics');

    // Users
    Route::get('/users', 'UserController@index')->name('admin.users');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('admin.users.edit');
    Route::post('/users/{user}/edit', 'UserController@update')->middleware(['blocked_at_demo'])->name('admin.users.update');
    Route::get('/users/{user}/delete', 'UserController@delete')->middleware(['blocked_at_demo'])->name('admin.users.delete');

    // Blog Categories
    Route::get('/blog-categories', 'BlogCategoryController@index')->name('admin.blog_categories');
    Route::get('/blog-categories/create', 'BlogCategoryController@create')->name('admin.blog_categories.create');
    Route::post('/blog-categories/create', 'BlogCategoryController@store')->middleware(['blocked_at_demo'])->name('admin.blog_categories.store');
    Route::get('/blog-categories/{blog_category}/edit', 'BlogCategoryController@edit')->name('admin.blog_categories.edit');
    Route::post('/blog-categories/{blog_category}/edit', 'BlogCategoryController@update')->middleware(['blocked_at_demo'])->name('admin.blog_categories.update');
    Route::get('/blog-categories/{blog_category}/delete', 'BlogCategoryController@delete')->middleware(['blocked_at_demo'])->name('admin.blog_categories.delete');

    // Blog
    Route::get('/blog', 'BlogController@index')->name('admin.blogs');
    Route::get('/blog/create', 'BlogController@create')->name('admin.blogs.create');
    Route::post('/blog/create', 'BlogController@store')->middleware(['blocked_at_demo'])->name('admin.blogs.store');
    Route::get('/blog/{blog}/edit', 'BlogController@edit')->name('admin.blogs.edit');
    Route::post('/blog/{blog}/edit', 'BlogController@update')->middleware(['blocked_at_demo'])->name('admin.blogs.update');
    Route::get('/blog/{blog}/delete', 'BlogController@delete')->middleware(['blocked_at_demo'])->name('admin.blogs.delete');

    // Pages
    Route::get('/pages', 'PageController@index')->name('admin.pages');
    Route::get('/pages/create', 'PageController@create')->name('admin.pages.create');
    Route::post('/pages/store', 'PageController@store')->middleware(['blocked_at_demo'])->name('admin.pages.store');
    Route::get('/pages/{page}/edit', 'PageController@edit')->name('admin.pages.edit');
    Route::post('/pages/{page}/edit', 'PageController@update')->middleware(['blocked_at_demo'])->name('admin.pages.update');
    Route::get('/pages/{page}/delete', 'PageController@delete')->middleware(['blocked_at_demo'])->name('admin.pages.delete');

    // Settings
    Route::get('/settings/{tab}', 'SettingController@index')->name('admin.settings');
    Route::post('/settings/{tab}', 'SettingController@update')->middleware(['blocked_at_demo'])->name('admin.settings.update');
    Route::get('/settings/currencies/{code}/enable', 'SettingController@currencies_enable')->name('admin.settings.currencies.enable');
    Route::get('/settings/currencies/{code}/disable', 'SettingController@currencies_disable')->name('admin.settings.currencies.disable');

    // Languages
    Route::get('/languages', 'LanguageController@index')->name('admin.languages');
    Route::get('/languages/create', 'LanguageController@create')->name('admin.languages.create');
    Route::post('/languages/create', 'LanguageController@store')->middleware(['blocked_at_demo'])->name('admin.languages.store');
    Route::get('/languages/{language}/default', 'LanguageController@set_default')->middleware(['blocked_at_demo'])->name('admin.languages.set_default');
    Route::get('/languages/{language}/translations', 'LanguageTranslationController@index')->name('admin.languages.translations');
    Route::post('/languages/{language}', 'LanguageTranslationController@update')->name('admin.languages.translations.update');

    // TinyMCE Image Uploading
    Route::post('/tinymce/upload', 'TinyMCEController@upload')->name('tiny.upload');
});