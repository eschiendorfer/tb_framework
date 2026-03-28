<?php

interface AjaxListInterface
{
    public function getProviderKey(): string;

    public function getOriginEntityType(): int;

    public function getOriginIdEntity(): int;

    public function getTargetEntityType(): int;

    public function getTargetIdEntity(): int;

    public function getDefaultLimit(): int;

    /**
     * @return array<int, mixed>
     */
    public function getItems(int $limit, int $offset): array;

    public function renderItem(array $item): string;

    public function getItemsTotal(): int;

    public function getItemsLabel(): string;
}
