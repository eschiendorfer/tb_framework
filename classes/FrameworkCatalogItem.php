<?php

require_once(dirname(__FILE__).'/ComponentDefinition.php');
require_once(dirname(__FILE__).'/CssTokenDefinition.php');
require_once(dirname(__FILE__).'/enums/FrameworkCatalogItemKindEnum.php');

class FrameworkCatalogItem {
    private FrameworkCatalogItemKindEnum $kind;
    private string $type;
    private string $name;
    private array $outputChannels;
    private array $dataInputModes;
    private array $targetEntityTypeKeys;
    private ?ComponentDefinition $component;
    private ?CssTokenDefinition $cssToken;

    public function __construct(
        FrameworkCatalogItemKindEnum $kind,
        string $type,
        string $name,
        array $outputChannels,
        array $dataInputModes,
        array $targetEntityTypeKeys = [],
        ?ComponentDefinition $component = null,
        ?CssTokenDefinition $cssToken = null
    ) {
        $this->kind = $kind;
        $this->type = $type;
        $this->name = $name;
        $this->outputChannels = $this->uniqueEnumValues($outputChannels);
        $this->dataInputModes = $this->uniqueEnumValues($dataInputModes);
        $this->targetEntityTypeKeys = $this->normalizeStringList($targetEntityTypeKeys);
        $this->component = $component;
        $this->cssToken = $cssToken;
    }

    public function getKind(): FrameworkCatalogItemKindEnum {
        return $this->kind;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getTargetEntityTypeKeys(): array {
        return $this->targetEntityTypeKeys;
    }

    public function getDataInputModes(): array {
        return $this->dataInputModes;
    }

    public function getComponent(): ?ComponentDefinition {
        return $this->component;
    }

    public function getCssToken(): ?CssTokenDefinition {
        return $this->cssToken;
    }

    public function isAvailableForChannels(array $selectedChannels): bool {
        if (empty($selectedChannels)) {
            return true;
        }

        if ($this->kind === FrameworkCatalogItemKindEnum::CSS_TOKEN) {
            return in_array(\CoreExtension\OutputChannelEnum::WEB, $selectedChannels, true);
        }

        foreach ($this->outputChannels as $channel) {
            if (in_array($channel, $selectedChannels, true)) {
                return true;
            }
        }

        return false;
    }

    public function matchesDataInputModes(array $selectedDataInputModes): bool {
        if (empty($selectedDataInputModes)) {
            return true;
        }

        foreach ($this->dataInputModes as $dataInputMode) {
            if (in_array($dataInputMode, $selectedDataInputModes, true)) {
                return true;
            }
        }

        return false;
    }

    public function matchesTargetEntityTypeKeys(array $selectedTargetEntityTypeKeys): bool {
        if (empty($selectedTargetEntityTypeKeys)) {
            return true;
        }

        if (empty($this->targetEntityTypeKeys) && in_array('none', $selectedTargetEntityTypeKeys, true)) {
            return true;
        }

        return !empty(array_intersect($this->targetEntityTypeKeys, $selectedTargetEntityTypeKeys));
    }

    private function uniqueEnumValues(array $values): array {
        $uniqueValues = [];

        foreach ($values as $value) {
            if (!$value instanceof BackedEnum) {
                continue;
            }

            $uniqueValues[$value->value] = $value;
        }

        return array_values($uniqueValues);
    }

    private function normalizeStringList(array $values): array {
        $normalizedValues = [];

        foreach ($values as $value) {
            $value = strtolower(trim((string)$value));
            if ($value === '') {
                continue;
            }

            $normalizedValues[$value] = $value;
        }

        return array_values($normalizedValues);
    }
}
