<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CalendarCompactComponent extends ComponentDefinition {
    protected const TYPE = 'calendar';
    protected const NAME = 'calendar_compact';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'date_active' => date('Y-m-d'),
            'date_active_link' => 'https://www.domain.com/events/?date_active=',
            'dates_marked' => [
                date('Y-m-d', strtotime('-3 days')),
                date('Y-m-d', strtotime('+8 days')),
                date('Y-m-d', strtotime('+9 days')),
                date('Y-m-d', strtotime('+10 days')),
                date('Y-m-d', strtotime('+21 days')),
                date('Y-m-d', strtotime('+24 days')),
            ],
        ];
    }
}



