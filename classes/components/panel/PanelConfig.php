<?php

require_once(dirname(__DIR__, 2).'/enums/PanelRenderModeEnum.php');
require_once(dirname(__DIR__, 2).'/enums/PanelMobileModeEnum.php');
require_once(dirname(__DIR__, 2).'/enums/PanelNavLayerEnum.php');
require_once(dirname(__DIR__, 2).'/enums/PanelPopoverPositionEnum.php');
require_once(dirname(__DIR__, 2).'/enums/PanelPopoverZIndexEnum.php');

final class PanelConfig
{
    public PanelRenderModeEnum $renderMode;
    public PanelMobileModeEnum $mobileMode;
    public PanelNavLayerEnum $navLayer;
    public PanelPopoverPositionEnum $popoverPosition;
    public PanelPopoverZIndexEnum $popoverZIndex;
    public int $popoverMargin;
    public bool $popoverAutoPosition;
    public string $anchorElementId;
    public bool $autoShow;
    public bool $toggleOnItemClick;
    public bool $closeOnBackdrop;
    public bool $closeOnOutsideClick;
    public bool $showCloseButton;
    public bool $closeOnSwipeDown;
    public bool $pushHistoryState;
    /** @var float[] */
    public array $sheetSnapRatios;
    public int $sheetStartSnapIndex;

    /**
     * @param float[]|null $sheetSnapRatios
     */
    public function __construct(
        PanelRenderModeEnum $renderMode = PanelRenderModeEnum::DIALOG,
        PanelMobileModeEnum $mobileMode = PanelMobileModeEnum::DIALOG,
        PanelNavLayerEnum $navLayer = PanelNavLayerEnum::OVER,
        PanelPopoverPositionEnum $popoverPosition = PanelPopoverPositionEnum::BOTTOM_CENTER,
        PanelPopoverZIndexEnum $popoverZIndex = PanelPopoverZIndexEnum::DEFAULT,
        int $popoverMargin = 10,
        bool $popoverAutoPosition = true,
        string $anchorElementId = '',
        bool $autoShow = false,
        bool $toggleOnItemClick = true,
        bool $closeOnBackdrop = true,
        bool $closeOnOutsideClick = true,
        bool $showCloseButton = true,
        bool $closeOnSwipeDown = true,
        bool $pushHistoryState = true,
        ?array $sheetSnapRatios = null,
        int $sheetStartSnapIndex = 1,
    ) {
        $this->renderMode = $renderMode;
        $this->mobileMode = $mobileMode;
        $this->navLayer = $navLayer;
        $this->popoverPosition = $popoverPosition;
        $this->popoverZIndex = $popoverZIndex;
        $this->popoverMargin = max(0, $popoverMargin);
        $this->popoverAutoPosition = $popoverAutoPosition;
        $this->anchorElementId = trim($anchorElementId);
        $this->autoShow = $autoShow;
        $this->toggleOnItemClick = $toggleOnItemClick;
        $this->closeOnBackdrop = $closeOnBackdrop;
        $this->closeOnOutsideClick = $closeOnOutsideClick;
        $this->showCloseButton = $showCloseButton;
        $this->closeOnSwipeDown = $closeOnSwipeDown;
        $this->pushHistoryState = $pushHistoryState;

        $ratios = $sheetSnapRatios ?? [0.4, 0.7, 0.92];
        $this->sheetSnapRatios = $this->normalizeSnapRatios($ratios);
        $this->sheetStartSnapIndex = $this->normalizeSnapIndex($sheetStartSnapIndex, count($this->sheetSnapRatios));
    }

    public static function dialogSheet(): self
    {
        return new self(
            renderMode: PanelRenderModeEnum::DIALOG,
            mobileMode: PanelMobileModeEnum::SHEET,
        );
    }

    public static function dialog(): self
    {
        return new self(
            renderMode: PanelRenderModeEnum::DIALOG,
            mobileMode: PanelMobileModeEnum::DIALOG,
        );
    }

    public static function popover(): self
    {
        return new self(
            renderMode: PanelRenderModeEnum::POPOVER,
            mobileMode: PanelMobileModeEnum::DIALOG,
            closeOnBackdrop: false,
            showCloseButton: false,
            closeOnSwipeDown: false,
            pushHistoryState: false,
        );
    }

    public function toArray(): array
    {
        return [
            'render_mode' => $this->renderMode->value,
            'mobile_mode' => $this->mobileMode->value,
            'nav_layer' => $this->navLayer->value,
            'popover_position' => $this->popoverPosition->value,
            'popover_z_index' => $this->popoverZIndex->value,
            'popover_margin' => $this->popoverMargin,
            'popover_auto_position' => $this->popoverAutoPosition,
            'anchor_element_id' => $this->anchorElementId,
            'auto_show' => $this->autoShow,
            'toggle_on_item_click' => $this->toggleOnItemClick,
            'close_on_backdrop' => $this->closeOnBackdrop,
            'close_on_outside_click' => $this->closeOnOutsideClick,
            'show_close_button' => $this->showCloseButton,
            'close_on_swipe_down' => $this->closeOnSwipeDown,
            'push_history_state' => $this->pushHistoryState,
            'sheet_snap_ratios' => $this->sheetSnapRatios,
            'sheet_start_snap_index' => $this->sheetStartSnapIndex,
        ];
    }

    /**
     * @param float[] $ratios
     * @return float[]
     */
    private function normalizeSnapRatios(array $ratios): array
    {
        $normalized = [];

        foreach ($ratios as $ratio) {
            if (!is_numeric($ratio)) {
                continue;
            }

            $ratioFloat = (float)$ratio;
            if ($ratioFloat <= 0.0 || $ratioFloat >= 1.0) {
                continue;
            }

            $normalized[] = $ratioFloat;
        }

        $normalized = array_values(array_unique($normalized));
        sort($normalized);

        if (count($normalized) < 2) {
            throw new PrestaShopException('PanelConfig requires at least two valid sheet snap ratios.');
        }

        return $normalized;
    }

    private function normalizeSnapIndex(int $index, int $count): int
    {
        if ($count <= 0) {
            return 0;
        }

        if ($index < 0) {
            return 0;
        }

        if ($index > ($count - 1)) {
            return $count - 1;
        }

        return $index;
    }
}
