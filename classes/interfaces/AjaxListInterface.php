<?php

interface AjaxListInterface
{
    public function getProviderKey(): string;

    public function getOriginEntityType(): int;

    public function getOriginIdEntity(): int;

    public function getTargetEntityType(): int;

    public function getTargetIdEntity(): int;

    public function getDefaultInitLimit(): int;

    public function getDefaultStepLimit(): int;

    public function getPublicListUrl(int $offset, int $stepLimit): string;

    /**
     * @return array<int, mixed>
     */
    public function getItems(int $limit, int $offset): array;

    public function renderItem(array $item): string;

    public function getItemsTotal(): int;

    public function getItemsLabel(): string;

    public function getItemsContainerClass(): string;
}
