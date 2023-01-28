<?php

return [
    'database' => [
        'cancel_migrations_autoloading' => false,
    ],

    'feature_tickets' => env('SCRIBE_FEATURE_TICKETS', false),

    'models' => [

        'feature' => \Appfinesse\Scribe\Models\Feature::class,

        'feature_consumption' => \Appfinesse\Scribe\Models\FeatureConsumption::class,

        'feature_ticket' => \Appfinesse\Scribe\Models\FeatureTicket::class,

        'feature_plan' => \Appfinesse\Scribe\Models\FeaturePlan::class,

        'plan' => \Appfinesse\Scribe\Models\Plan::class,

        'subscriber' => [
            'uses_uuid' => env('SCRIBE_SUBSCRIBER_USES_UUID', false),
        ],

        'subscription' => \Appfinesse\Scribe\Models\Subscription::class,

        'subscription_renewal' => \Appfinesse\Scribe\Models\SubscriptionRenewal::class,
    ],
];
