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

    public function renderList(bool $ajaxPlaceholder = false, ?int $firstLimit = null, ?int $steps = null): string
    {
        $firstLimit = $this->normalizeLimit($firstLimit ?? $this->provider->getDefaultLimit());
        $steps = $this->normalizeLimit($steps ?? $firstLimit);

        if ($ajaxPlaceholder) {
            return $this->renderListPlaceholder($firstLimit, $steps);
        }

        return $this->renderListInit($firstLimit, 0, $steps);
    }

    public function renderByAction(
        string $action,
        ?int $limit = null,
        ?int $offset = null,
        ?int $stepLimit = null
    ): string
    {
        if ($action === self::ACTION_APPEND) {
            return $this->renderListAppend($limit, $offset);
        }

        $limit = $this->normalizeLimit($limit ?? $this->provider->getDefaultLimit());
        $stepLimit = $this->normalizeLimit($stepLimit ?? $limit);

        return $this->renderListInit($limit, $offset ?? 0, $stepLimit);
    }

    private function renderListAppend(?int $limit = null, ?int $offset = null): string
    {
        $limit = $this->normalizeLimit($limit ?? $this->provider->getDefaultLimit());
        $offset = max(0, $offset ?? 0);
        $items = $this->provider->getItems($limit, $offset);

        $this->context->smarty->assign([
            'items_html' => $this->renderItems($items),
        ]);

        return $this->renderTemplate('listAppend.tpl');
    }

    private function renderListPlaceholder(int $firstLimit, int $steps): string
    {
        $this->context->smarty->assign([
            'ajax_list_url' => $this->getUrl(self::ACTION_INIT, 0, $firstLimit, $steps),
        ]);

        return $this->renderTemplate('listPlaceholder.tpl');
    }

    private function renderListInit(?int $limit = null, int $offset = 0, ?int $stepLimit = null): string
    {
        $limit = $this->normalizeLimit($limit ?? $this->provider->getDefaultLimit());
        $stepLimit = $this->normalizeLimit($stepLimit ?? $limit);
        $offset = max(0, $offset);
        $offsetNext = $offset + $limit;
        $items = $this->provider->getItems($limit, $offset);

        $this->context->smarty->assign([
            'items_html'    => $this->renderItems($items),
            'itemsTotal'    => $this->provider->getItemsTotal(),
            'limit_next'    => $stepLimit,
            'offset_next'   => $offsetNext,
            'ajax_list_url' => $this->getUrl(self::ACTION_APPEND, $offsetNext, $stepLimit),
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
        ]);

        return (string)$this->context->smarty->fetch($templatePath);
    }

    private function getUrl(string $action, int $offset, int $limit, ?int $stepLimit = null): string
    {
        $params = [
            'action'             => $action,
            'provider'           => $this->provider->getProviderKey(),
            'origin_entity_type' => $this->provider->getOriginEntityType(),
            'origin_id_entity'   => $this->provider->getOriginIdEntity(),
            'target_entity_type' => $this->provider->getTargetEntityType(),
            'target_id_entity'   => $this->provider->getTargetIdEntity(),
            'offset'             => $offset,
            'limit'              => $limit,
        ];

        if ($stepLimit !== null && $stepLimit > 0) {
            $params['step_limit'] = $stepLimit;
        }

        return $this->context->link->getModuleLink(
            'tb_framework',
            'ajaxlist',
            $params
        );
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
    private function renderItems(array $items): array
    {
        $itemsHtml = [];

        foreach ($items as $item) {
            if (is_array($item)) {
                $itemsHtml[] = $this->provider->renderItem($item);
            }
        }

        return $itemsHtml;
    }
}
