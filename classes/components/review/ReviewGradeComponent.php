<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ReviewGradeComponent extends ComponentDefinition {
    protected const TYPE = 'review';
    protected const NAME = 'review_grade';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'review_grade' => rand(1*10,5*10)/10,
            'selector' => true,
            'id' => 'grade',
            'input_name' => 'grade[1]',
        ];
    }
}



