<?php

class AjaxListRender
{
    private const TEMPLATE_BASE_PATH = _PS_MODULE_DIR_.'tb_framework/views/templates/helper/';
    private const ACTION_INIT = 'init';
    private const ACTION_APPEND = 'append';

    private Context $context;
    private AjaxListInterface $provider;

    public function __construct(AjaxListInterface $provider)
    {
        $this->context = Context::getContext();
        $this->provider = $provider;
    }

    public static function handleListActionRequest(
        ModuleFrontController $controller,
        AjaxListInterface $provider
    ): bool
    {
        $listAction = strtolower((string)Tools::getValue('list_action', Tools::getValue('action')));

        if (
            !Tools::getValue('ajax')
            || !in_array($listAction, [self::ACTION_INIT, self::ACTION_APPEND], true)
        ) {
            return false;
        }

        $offset = max(0, (int)Tools::getIntValue('offset'));

        $renderer = new self($provider);
        $content['page'] = $renderer->renderByAction($listAction, $offset);
        die(json_encode($content));
    }

    public function renderList(
        bool $ajaxPlaceholder = false,
        ?int $initLimit = null,
        ?int $stepLimit = null,
        int $offset = 0
    ): string
    {
        $initLimit = $this->normalizeLimit($initLimit ?? $this->provider->getDefaultInitLimit());
        $stepLimit = $this->normalizeLimit($stepLimit ?? $this->provider->getDefaultStepLimit());
        $offset = max(0, $offset);

        if ($ajaxPlaceholder) {
            return $this->renderListPlaceholder($initLimit, $stepLimit, $offset);
        }

        return $this->renderListInit($initLimit, $offset, $stepLimit);
    }

    public function renderByAction(
        string $action,
        ?int $offset = null
    ): string
    {
        $requestedInitLimit = Tools::getIsset('init_limit') ? (int)Tools::getIntValue('init_limit') : null;
        $requestedStepLimit = Tools::getIsset('step_limit') ? (int)Tools::getIntValue('step_limit') : null;

        if ($action === self::ACTION_APPEND) {
            return $this->renderListAppend($offset, $requestedStepLimit);
        }

        $initLimit = $this->normalizeLimit($requestedInitLimit ?? $this->provider->getDefaultInitLimit());
        $stepLimit = $this->normalizeLimit($requestedStepLimit ?? $this->provider->getDefaultStepLimit());

        return $this->renderListInit($initLimit, $offset ?? 0, $stepLimit);
    }

    private function renderListAppend(?int $offset = null, ?int $stepLimit = null): string
    {
        $stepLimit = $this->normalizeLimit($stepLimit ?? $this->provider->getDefaultStepLimit());
        $offset = max(0, $offset ?? 0);
        $items = $this->provider->getItems($stepLimit, $offset);

        $this->context->smarty->assign([
            'items_html' => $this->renderItems($items, $offset),
        ]);

        return $this->renderTemplate('listAppend.tpl');
    }

    private function renderListPlaceholder(int $initLimit, int $stepLimit, int $offset = 0): string
    {
        $this->context->smarty->assign([
            'ajax_list_url' => $this->resolveAjaxUrl(self::ACTION_INIT, max(0, $offset), $initLimit, $stepLimit),
        ]);

        return $this->renderTemplate('listPlaceholder.tpl');
    }

    private function renderListInit(?int $initLimit = null, int $offset = 0, ?int $stepLimit = null): string
    {
        $initLimit = $this->normalizeLimit($initLimit ?? $this->provider->getDefaultInitLimit());
        $stepLimit = $this->normalizeLimit($stepLimit ?? $this->provider->getDefaultStepLimit());
        $offset = max(0, $offset);
        $offsetNext = $offset + $initLimit;
        $items = $this->provider->getItems($initLimit, $offset);

        $this->context->smarty->assign([
            'items_html'    => $this->renderItems($items, $offset),
            'itemsTotal'    => $this->provider->getItemsTotal(),
            'step_limit_next' => $stepLimit,
            'offset_next'   => $offsetNext,
            'ajax_list_url' => $this->resolveAjaxUrl(self::ACTION_APPEND, $offsetNext, null, $stepLimit),
            'public_list_url' => $this->provider->getPublicListUrl($offsetNext, $stepLimit),
            'itemsLabel'    => $this->provider->getItemsLabel(),
        ]);

        return $this->renderTemplate('listInit.tpl');
    }

    private function renderTemplate(string $templateName): string
    {
        $templatePath = self::TEMPLATE_BASE_PATH.$templateName;

        $this->context->smarty->assign([
            'ajax_list_unique_key' => $this->buildUniqueKey(),
            'origin_entity_type' => $this->provider->getOriginEntityType(),
            'origin_id_entity' => $this->provider->getOriginIdEntity(),
            'target_entity_type' => $this->provider->getTargetEntityType(),
            'target_id_entity' => $this->provider->getTargetIdEntity(),
            'ajax_list_items_class' => (string)$this->provider->getItemsContainerClass(),
            'ajax_list_back_restore_enabled' => $this->provider->isAjaxListBackRestoreEnabled() ? 1 : 0,
        ]);

        return (string)$this->context->smarty->fetch($templatePath);
    }

    private function getUrl(
        string $action,
        int $offset,
        ?int $initLimit = null,
        ?int $stepLimit = null
    ): string
    {
        $params = [
            'list_action'        => $action,
            'provider'           => $this->provider->getProviderKey(),
            'offset'             => $offset,
        ];

        if ($this->provider->getOriginEntityType() > 0) {
            $params['origin_entity_type'] = $this->provider->getOriginEntityType();
        }

        if ($this->provider->getOriginIdEntity() > 0) {
            $params['origin_id_entity'] = $this->provider->getOriginIdEntity();
        }

        if ($this->provider->getTargetEntityType() > 0) {
            $params['target_entity_type'] = $this->provider->getTargetEntityType();
        }

        if ($this->provider->getTargetIdEntity() > 0) {
            $params['target_id_entity'] = $this->provider->getTargetIdEntity();
        }

        if (
            $action === self::ACTION_INIT
            && $initLimit !== null
            && $initLimit !== $this->provider->getDefaultInitLimit()
        ) {
            $params['init_limit'] = $initLimit;
        }

        if (
            $stepLimit !== null
            && $stepLimit !== $this->provider->getDefaultStepLimit()
        ) {
            $params['step_limit'] = $stepLimit;
        }

        return $this->context->link->getModuleLink(
            'tb_framework',
            'ajaxlist',
            $params
        );
    }

    private function resolveAjaxUrl(
        string $action,
        int $offset,
        ?int $initLimit = null,
        ?int $stepLimit = null
    ): string
    {
        if (method_exists($this->provider, 'getAjaxListUrl')) {
            $customUrl = (string)$this->provider->getAjaxListUrl($action, $offset, $initLimit, $stepLimit);

            if ($customUrl !== '') {
                return $customUrl;
            }
        }

        return $this->getUrl($action, $offset, $initLimit, $stepLimit);
    }

    private function normalizeLimit(int $limit): int
    {
        return max(1, $limit);
    }

    private function buildUniqueKey(): string
    {
        return implode('_', [
            $this->provider->getProviderKey(),
            $this->provider->getOriginEntityType(),
            $this->provider->getOriginIdEntity(),
            $this->provider->getTargetEntityType(),
            $this->provider->getTargetIdEntity(),
        ]);
    }

    /**
     * @param array<int, mixed> $items
     * @return array<int, string>
     */
    private function renderItems(array $items, int $batchOffset = 0): array
    {
        $itemsHtml = [];
        $batchOffset = max(0, $batchOffset);

        foreach ($items as $item) {
            if (is_array($item)) {
                $itemHtml = $this->provider->renderItem($item);
                if ($itemHtml === '') {
                    continue;
                }

                if ($this->provider->isAjaxListBackRestoreEnabled()) {
                    $itemMarker = trim((string)$this->provider->getAjaxListBackRestoreMarker($item));
                    if ($itemMarker !== '') {
                        $itemHtml = '<div data-ajax-list-item-marker="' . htmlspecialchars($itemMarker, ENT_QUOTES, 'UTF-8') . '" data-ajax-list-item-offset="' . (int)$batchOffset . '">' . $itemHtml . '</div>';
                    }
                }

                $itemsHtml[] = $itemHtml;
            }
        }

        return $itemsHtml;
    }
}
