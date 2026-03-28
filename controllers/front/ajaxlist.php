<?php

class tb_frameworkAjaxlistModuleFrontController extends ModuleFrontController
{
    private const ACTION_INIT = 'init';
    private const ACTION_APPEND = 'append';
    private const PROVIDERS_HOOK = 'actionRegisterAjaxListProviders';
    private const DEFAULT_LIMIT = 5;
    private const MAX_LIMIT = 50;

    /** @var array<string, array<string, string>>|null */
    private ?array $providersByKey = null;

    public function initContent()
    {
        parent::initContent();

        if (!$this->ajax) {
            $this->ajaxError('Ajax requests only.');
            return;
        }

        $providerKey = pSQL((string)Tools::getValue('provider'));
        $action = strtolower((string)Tools::getValue('action', self::ACTION_INIT));

        if (!in_array($action, [self::ACTION_INIT, self::ACTION_APPEND], true)) {
            $this->ajaxError('Invalid action.');
            return;
        }

        $provider = $this->buildProvider($providerKey);
        if (!$provider) {
            $this->ajaxError('Provider is not allowed.');
            return;
        }

        $providerDefaultLimit = max(1, (int)$provider->getDefaultLimit());
        $defaultLimit = max(1, min($providerDefaultLimit ?: self::DEFAULT_LIMIT, self::MAX_LIMIT));
        $limit = max(1, min((int)Tools::getIntValue('limit', $defaultLimit), self::MAX_LIMIT));
        $offset = max(0, (int)Tools::getIntValue('offset'));
        $stepLimit = max(1, min((int)Tools::getIntValue('step_limit', $limit), self::MAX_LIMIT));

        $renderer = new AjaxListRender($provider);
        $content['page'] = $renderer->renderByAction($action, $limit, $offset, $stepLimit);

        $this->ajaxDie(json_encode($content));
    }

    private function buildProvider(string $providerKey): ?AjaxListInterface
    {
        $providerConfig = $this->getProvidersByKey()[$providerKey] ?? null;
        if (!$providerConfig) {
            return null;
        }

        $moduleName = $providerConfig['module'] ?? '';
        if (!$moduleName || !Module::isEnabled($moduleName)) {
            return null;
        }

        $providerFile = $providerConfig['provider_file'] ?? '';

        if ($providerFile !== '') {
            if (!$this->isProviderFileAllowed($providerFile, $moduleName)) {
                return null;
            }

            require_once $providerFile;
        }

        $providerClass = $providerConfig['provider_class'] ?? '';
        if (!$providerClass || !class_exists($providerClass)) {
            return null;
        }

        $entity = EntityResolver::getEntityByContext();

        $originEntityType = (int)Tools::getIntValue('origin_entity_type', (int)$entity->origin_entity_type);
        $originIdEntity = (int)Tools::getIntValue('origin_id_entity', (int)$entity->origin_id_entity);
        $targetEntityType = (int)Tools::getIntValue('target_entity_type', (int)$entity->target_entity_type);
        $targetIdEntity = (int)Tools::getIntValue('target_id_entity', (int)$entity->target_id_entity);

        $provider = new $providerClass(
            $originEntityType,
            $originIdEntity,
            $targetEntityType,
            $targetIdEntity
        );

        if (!$provider instanceof AjaxListInterface) {
            return null;
        }

        if ($provider->getProviderKey() !== $providerKey) {
            return null;
        }

        return $provider;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function getProvidersByKey(): array
    {
        if (is_array($this->providersByKey)) {
            return $this->providersByKey;
        }

        $providersByKey = [];
        $hookResults = Hook::exec(self::PROVIDERS_HOOK, [], null, true);

        if (!is_array($hookResults)) {
            $this->providersByKey = [];
            return $this->providersByKey;
        }

        foreach ($hookResults as $moduleName => $moduleProviders) {
            if (!is_array($moduleProviders)) {
                continue;
            }

            if (array_key_exists('provider_key', $moduleProviders)) {
                $moduleProviders = [$moduleProviders];
            }

            foreach ($moduleProviders as $providerData) {
                if (!is_array($providerData)) {
                    continue;
                }

                $registeredProviderKey = trim((string)($providerData['provider_key'] ?? ''));
                $providerClass = trim((string)($providerData['provider_class'] ?? ''));
                $providerFile = trim((string)($providerData['provider_file'] ?? ''));

                if ($registeredProviderKey === '' || $providerClass === '') {
                    continue;
                }

                if (isset($providersByKey[$registeredProviderKey])) {
                    trigger_error(
                        sprintf(
                            'AjaxList provider key "%s" is registered more than once.',
                            $registeredProviderKey
                        ),
                        E_USER_WARNING
                    );
                    continue;
                }

                $providersByKey[$registeredProviderKey] = [
                    'module' => (string)$moduleName,
                    'provider_class' => $providerClass,
                    'provider_file' => $providerFile,
                ];
            }
        }

        $this->providersByKey = $providersByKey;
        return $this->providersByKey;
    }

    private function isProviderFileAllowed(string $providerFile, string $moduleName): bool
    {
        if ($providerFile === '' || $moduleName === '') {
            return false;
        }

        $providerFileRealPath = realpath($providerFile);
        $moduleBasePath = realpath(_PS_MODULE_DIR_.$moduleName);

        if (!$providerFileRealPath || !$moduleBasePath) {
            return false;
        }

        $providerFilePathNormalized = str_replace('\\', '/', $providerFileRealPath);
        $moduleBasePathNormalized = rtrim(str_replace('\\', '/', $moduleBasePath), '/').'/';

        return strpos($providerFilePathNormalized, $moduleBasePathNormalized) === 0;
    }

    private function ajaxError(string $message): void
    {
        $this->ajaxDie(json_encode([
            'hasError' => true,
            'errors' => [$message],
        ]));
    }
}
