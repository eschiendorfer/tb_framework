<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class AlertWarningCssToken extends CssTokenDefinition {
    protected const TYPE = 'alert';
    protected const NAME = 'alert_warning';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_alert tbfw_alert_warning',
    ];
}

