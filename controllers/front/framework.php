<?php

class tb_frameworkFrameworkModuleFrontController extends ModuleFrontController {

    public $errors;

    public function __construct() {

        parent::__construct();

        // Disable left and right column
        $this->display_column_left = false;
        $this->display_column_right = false;

    }

    public function init() {

        parent::init();

        if (!$this->hasFrameworkAccess()) {
            Tools::redirect('index.php?controller=404');
        }

    }

    public function initContent() {

        parent::initContent();

        $type = $this->resolveRequestedType();
        $component = (bool)(int)Tools::getValue('component');
        $style = (string)Tools::getValue('style');

        $this->context->smarty->assign(array(
            'nobots' => true, // Google shouldn't index this pages -> if we still see a lot of page hits, it's likely a bad crawler or hacking attempt
            'nofollow' => true, // Google shouldn't indes this pages -> if we still see a lot of page hits, it's likely a bad crawler or hacking attempt
            'meta_title' => 'TB FrontOffice Framework',
            'meta_description' => 'bla',
            'site_title' => 'TB Framework (Beta)',
            'site_description' => 'CSS returns just classnames, while Web and Mail return full html components',
            'site_image' => '',
            'framework_content' => $type ? $this->renderFrameworkContentByType($type, $component, $style) : '',
            'framework_component_navigation' => $this->buildComponentNavigation(),
        ));

        $this->setTemplate('framework.tpl');
    }


    private function renderFrameworkContentByType($type, $component = false, $style = '') {

        if ($component) {
            $componentsMap = [];

            foreach (FrameworkRegistry::all() as $registeredComponent) {
                if ($registeredComponent->getType() !== $type) {
                    continue;
                }

                $styles = method_exists($registeredComponent, 'getStyles')
                    ? $registeredComponent->getStyles()
                    : ['default'];

                foreach ($registeredComponent->getChannels() as $channel) {
                    foreach ($styles as $componentStyle) {
                        $output = $registeredComponent::fetchDemo($channel, $componentStyle);

                        if ($channel === ComponentChannel::CSS_CLASSES) {
                            $className = htmlspecialchars((string)$output, ENT_QUOTES, 'UTF-8');
                            $componentName = htmlspecialchars($registeredComponent->getName(), ENT_QUOTES, 'UTF-8');
                            $output = '<span class="'.$className.'">'.$componentName.'</span>';
                        }

                        $channelName = strtoupper($channel->value);
                        $componentKey = $registeredComponent->getName().'_'.$channelName;

                        if (!isset($componentsMap[$componentKey])) {
                            $componentsMap[$componentKey] = [
                                'name' => $registeredComponent->getName(),
                                'channel' => $channelName,
                                'variants' => [],
                            ];
                        }

                        $componentsMap[$componentKey]['variants'][] = [
                            'style' => $componentStyle,
                            'output' => $output,
                        ];
                    }
                }
            }

            $components = array_values($componentsMap);

            $this->context->smarty->assign(array(
                'title' => ucwords($type),
                'components' => $components,
            ));
        }

        $typeTemplate = _PS_MODULE_DIR_.'tb_framework/views/templates/helper/'.$type.'.tpl';
        if (file_exists($typeTemplate)) {
            return $this->context->smarty->fetch($typeTemplate);
        }

        return $this->context->smarty->fetch(_PS_MODULE_DIR_."tb_framework/views/templates/helper/components.tpl");
    }

    private function resolveRequestedType(): string {
        $type = trim((string)Tools::getValue('type'));
        if ($type === '') {
            return '';
        }

        if (!preg_match('/^[a-z0-9_]+$/', $type)) {
            Tools::redirect('index.php?controller=404');
        }

        if (!in_array($type, $this->getAvailableFrameworkTypes(), true)) {
            Tools::redirect('index.php?controller=404');
        }

        return $type;
    }

    private function getAvailableFrameworkTypes(): array {
        $types = [];

        foreach (FrameworkRegistry::all() as $component) {
            $type = $component->getType();
            if ($type === '') {
                continue;
            }

            if (!preg_match('/^[a-z0-9_]+$/', $type)) {
                continue;
            }

            $types[$type] = true;
        }

        return array_keys($types);
    }

    private function hasFrameworkAccess(): bool {
        if (!empty($this->context->employee) && Validate::isLoadedObject($this->context->employee)) {
            return true;
        }

        if (empty($this->context->customer) || !Validate::isLoadedObject($this->context->customer)) {
            return false;
        }

        if (!class_exists('SpielezarHelper')) {
            return false;
        }

        $idCustomer = (int)$this->context->customer->id;

        return SpielezarHelper::checkIfTeamMember($idCustomer)
            || SpielezarHelper::checkIfCustomerIsEmployeeWithInvoiceRights($idCustomer);
    }

    private function buildComponentNavigation() {
        $navigation = [];

        foreach (FrameworkRegistry::all() as $component) {
            $type = $component->getType();
            $channels = $component->getChannels();

            if (!isset($navigation[$type])) {
                $navigation[$type] = [
                    'type' => $type,
                    'label' => ucwords(str_replace('_', ' ', $type)),
                    'has_web' => false,
                    'has_email' => false,
                    'has_css' => false,
                    'route_type' => $type,
                    'route_component' => 0,
                ];
            }

            if (in_array(ComponentChannel::WEB, $channels, true)) {
                $navigation[$type]['has_web'] = true;
                $navigation[$type]['route_type'] = $type;
                $navigation[$type]['route_component'] = 1;
            }

            if (in_array(ComponentChannel::EMAIL, $channels, true)) {
                $navigation[$type]['has_email'] = true;
                $navigation[$type]['route_type'] = $type;
                $navigation[$type]['route_component'] = 1;
            }

            if (in_array(ComponentChannel::CSS_CLASSES, $channels, true)) {
                $navigation[$type]['has_css'] = true;
                $navigation[$type]['route_type'] = $type;
                $navigation[$type]['route_component'] = 1;
            }
        }

        uasort($navigation, function ($left, $right) {
            return strcmp($left['label'], $right['label']);
        });

        return array_values($navigation);
    }

}
