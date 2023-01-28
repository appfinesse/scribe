<?php

namespace Appfinesse\Scribe\Events;

use Appfinesse\Scribe\Models\Feature;
use Appfinesse\Scribe\Models\FeatureTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeatureTicketCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public $subscriber,
        public Feature $feature,
        public FeatureTicket $featureTicket,
    ) {
        //
    }
}
