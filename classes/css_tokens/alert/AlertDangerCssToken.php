<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class AlertDangerCssToken extends CssTokenDefinition {
    protected const TYPE = 'alert';
    protected const NAME = 'alert_danger';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_alert tbfw_alert_danger',
    ];
}

