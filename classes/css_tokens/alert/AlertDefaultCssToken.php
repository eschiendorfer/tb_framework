<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class AlertDefaultCssToken extends CssTokenDefinition {
    protected const TYPE = 'alert';
    protected const NAME = 'alert_default';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_alert tbfw_alert_default',
    ];
}

