(function () {
    if (typeof window.tbFrameworkCreatePanelComponent === 'function') {
        return;
    }

    window.tbFrameworkPanelQueue = window.tbFrameworkPanelQueue || [];

    function toBool(value, fallback) {
        if (typeof value === 'boolean') {
            return value;
        }
        if (typeof value === 'number') {
            return value !== 0;
        }
        if (typeof value === 'string') {
            var normalized = value.trim().toLowerCase();
            if (['1', 'true', 'yes', 'y', 'on'].indexOf(normalized) !== -1) {
                return true;
            }
            if (['0', 'false', 'no', 'n', 'off', ''].indexOf(normalized) !== -1) {
                return false;
            }
        }

        return fallback;
    }

    function normalizeSnapRatios(rawRatios) {
        var source = Array.isArray(rawRatios) ? rawRatios : [0.4, 0.7, 0.92];
        var values = [];

        source.forEach(function (ratioRaw) {
            var ratio = parseFloat(ratioRaw);
            if (!isFinite(ratio) || ratio <= 0 || ratio >= 1) {
                return;
            }
            if (values.indexOf(ratio) === -1) {
                values.push(ratio);
            }
        });

        values.sort(function (left, right) {
            return left - right;
        });

        if (values.length < 2) {
            return [0.4, 0.7, 0.92];
        }

        return values;
    }

    function normalizeSnapIndex(indexRaw, maxLength) {
        var index = parseInt(indexRaw, 10);
        if (!isFinite(index) || index < 0) {
            return 0;
        }

        if (index >= maxLength) {
            return maxLength - 1;
        }

        return index;
    }

    window.tbFrameworkCreatePanelComponent = function (options) {
        if (!options || !options.id) {
            return null;
        }

        var htmlElement = options.htmlElement || document.getElementById(options.id);
        if (!htmlElement) {
            return null;
        }

        var helperElement = options.helperElement || document.getElementById(options.id + '_helper');
        var configInput = options.config || {};
        var snapRatios = normalizeSnapRatios(configInput.sheetSnapRatios);
        var startSnapIndex = normalizeSnapIndex(configInput.sheetStartSnapIndex, snapRatios.length);

        var panel = {
            name: String(options.name || 'panel_default'),
            id: String(options.id),
            htmlElement: htmlElement,
            helperElement: helperElement,
            isOpen: false,
            config: {
                renderMode: String(configInput.renderMode || 'dialog'),
                mobileMode: String(configInput.mobileMode || 'dialog'),
                navLayer: String(configInput.navLayer || 'over'),
                autoShow: toBool(configInput.autoShow, false),
                toggleOnItemClick: toBool(configInput.toggleOnItemClick, true),
                closeOnBackdrop: toBool(configInput.closeOnBackdrop, true),
                closeOnOutsideClick: toBool(configInput.closeOnOutsideClick, true),
                showCloseButton: toBool(configInput.showCloseButton, true),
                closeOnSwipeDown: toBool(configInput.closeOnSwipeDown, true),
                pushHistoryState: toBool(configInput.pushHistoryState, true),
                popoverPosition: String(configInput.popoverPosition || 'bottom_center'),
                popoverMargin: Math.max(0, parseInt(configInput.popoverMargin || 10, 10) || 10),
                popoverAutoPosition: toBool(configInput.popoverAutoPosition, true),
                anchorElementId: String(configInput.anchorElementId || '').trim(),
                sheetSnapRatios: snapRatios,
                sheetStartSnapIndex: startSnapIndex
            },
            sheetState: {
                currentSnapIndex: startSnapIndex,
                isDragging: false,
                startY: 0,
                startHeight: 0,
                lastHeight: 0
            },
            _sheetResizeHandler: null,
            _dialogClickHandler: null,
            _dialogDragHandlersBound: false,
            _dialogCloseButtonHandlerBound: false,
            _popoverOutsideClickHandler: null,
            _popoverAnchorClickHandler: null,
            _popoverRepositionHandler: null
        };

        panel.isMobileSheetViewport = function () {
            try {
                return (window.matchMedia && window.matchMedia('(pointer: coarse)').matches) || window.innerWidth <= 768;
            } catch (error) {
                return window.innerWidth <= 768;
            }
        };

        panel.isMobileSheetActive = function () {
            return this.config.renderMode === 'dialog'
                && this.config.mobileMode === 'sheet'
                && this.isMobileSheetViewport();
        };

        panel.getSheetBottomOffset = function () {
            if (this.config.navLayer !== 'under') {
                return 0;
            }

            var menu = document.querySelector('nav#menu');
            var menuHeight = menu ? parseInt(menu.offsetHeight || 0, 10) : 0;
            return menuHeight > 0 ? menuHeight : 57;
        };

        panel.getSheetSnapHeights = function () {
            var bottomOffset = this.getSheetBottomOffset();
            var availableHeight = Math.max(320, window.innerHeight - bottomOffset);
            return this.config.sheetSnapRatios.map(function (ratio) {
                return Math.round(availableHeight * ratio);
            });
        };

        panel.setSheetHeight = function (heightPx, withTransition) {
            if (!this.helperElement) {
                return;
            }

            var snapHeights = this.getSheetSnapHeights();
            var minHeight = snapHeights[0];
            var maxHeight = snapHeights[snapHeights.length - 1];
            var normalizedHeight = Math.max(minHeight - 160, Math.min(maxHeight, Math.round(heightPx || minHeight)));

            this.helperElement.style.transition = withTransition === false ? 'none' : '';
            this.helperElement.style.height = normalizedHeight + 'px';
            this.htmlElement.style.setProperty('--panel-sheet-current-height', normalizedHeight + 'px');
            this.sheetState.lastHeight = normalizedHeight;
        };

        panel.syncSheetMode = function () {
            if (!this.htmlElement || !this.helperElement || this.config.renderMode !== 'dialog') {
                return;
            }

            if (!this.isOpen || !this.isMobileSheetActive()) {
                this.htmlElement.classList.remove('panel-sheet-mobile-enabled');
                this.htmlElement.style.removeProperty('--panel-sheet-bottom-offset');
                this.htmlElement.style.removeProperty('--panel-sheet-current-height');
                this.helperElement.style.height = '';
                this.helperElement.style.transition = '';
                return;
            }

            this.htmlElement.classList.add('panel-sheet-mobile-enabled');
            this.htmlElement.style.setProperty('--panel-sheet-bottom-offset', this.getSheetBottomOffset() + 'px');

            var snapHeights = this.getSheetSnapHeights();
            var snapIndex = normalizeSnapIndex(this.sheetState.currentSnapIndex, snapHeights.length);
            this.sheetState.currentSnapIndex = snapIndex;
            this.setSheetHeight(snapHeights[snapIndex], true);
        };

        panel.show = function () {
            if (this.config.renderMode !== 'dialog' || !this.htmlElement) {
                return;
            }

            this.isOpen = true;
            this.htmlElement.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            this.syncSheetMode();

            var menu = document.querySelector('nav#menu');
            if (menu && this.isMobileSheetActive()) {
                menu.style.zIndex = this.config.navLayer === 'under' ? '' : '0';
            }

            if (this.config.pushHistoryState && window.history.state !== 'panelWasOpened') {
                history.pushState('panelWasOpened', document.title, window.location.href);
            }

            var callbackOpen = this.htmlElement.getAttribute('data-callback-open');
            if (callbackOpen && typeof window[callbackOpen] === 'function') {
                window[callbackOpen](this.htmlElement);
            }
        };

        panel.close = function () {
            if (this.config.renderMode !== 'dialog' || !this.htmlElement || this.htmlElement.classList.contains('hidden')) {
                return;
            }

            this.isOpen = false;
            this.htmlElement.classList.add('hidden');
            this.syncSheetMode();

            var anyOpenDialog = false;
            if (window.tb_framework && window.tb_framework.panel_default) {
                window.tb_framework.panel_default.forEach(function (otherPanel) {
                    if (otherPanel !== panel && otherPanel.config && otherPanel.config.renderMode === 'dialog' && otherPanel.isOpen) {
                        anyOpenDialog = true;
                    }
                });
            }

            if (!anyOpenDialog) {
                document.body.style.overflow = '';
                var menu = document.querySelector('nav#menu');
                if (menu) {
                    menu.style.zIndex = '';
                }
            }

            var callbackClose = this.htmlElement.getAttribute('data-callback-close');
            if (callbackClose && typeof window[callbackClose] === 'function') {
                window[callbackClose](this.htmlElement);
            }
        };

        panel.toggle = function () {
            this.isOpen ? this.close() : this.show();
        };

        panel.getPopoverContentElement = function () {
            if (!this.htmlElement) {
                return null;
            }

            return this.htmlElement.querySelector('.panel_popover_content');
        };

        panel.getPopoverItemElement = function () {
            if (!this.htmlElement) {
                return null;
            }

            return this.htmlElement.querySelector('.panel_item');
        };

        panel.getPopoverAnchorElement = function () {
            if (this.config.anchorElementId) {
                var externalAnchor = document.getElementById(this.config.anchorElementId);
                if (externalAnchor) {
                    return externalAnchor;
                }
            }

            return this.getPopoverItemElement();
        };

        panel.syncPopoverAnchor = function () {
            if (this.config.renderMode !== 'popover' || !this.htmlElement) {
                return false;
            }

            var anchor = this.getPopoverAnchorElement();
            var item = this.getPopoverItemElement();
            if (!anchor || !item) {
                return false;
            }

            var rect = anchor.getBoundingClientRect();
            this.htmlElement.style.left = Math.round(rect.left) + 'px';
            this.htmlElement.style.top = Math.round(rect.top) + 'px';
            this.htmlElement.style.width = Math.max(1, Math.round(rect.width)) + 'px';
            this.htmlElement.style.height = Math.max(1, Math.round(rect.height)) + 'px';

            item.style.width = Math.max(1, Math.round(rect.width)) + 'px';
            item.style.height = Math.max(1, Math.round(rect.height)) + 'px';

            var hasExternalAnchor = !!this.config.anchorElementId;
            this.htmlElement.classList.toggle('panel_popover_external_anchor', hasExternalAnchor);

            return true;
        };

        panel.getPopoverPositionCandidates = function (preferredPosition) {
            var position = preferredPosition || 'bottom_center';

            if (!this.config.popoverAutoPosition) {
                return [position];
            }

            if (position === 'bottom_left') return ['bottom_left', 'bottom_center', 'bottom_right', 'top_left', 'top_center', 'top_right', 'right_top', 'right_center', 'right_bottom', 'left_top', 'left_center', 'left_bottom'];
            if (position === 'bottom_center') return ['bottom_center', 'bottom_left', 'bottom_right', 'top_center', 'top_left', 'top_right', 'right_center', 'right_top', 'right_bottom', 'left_center', 'left_top', 'left_bottom'];
            if (position === 'bottom_right') return ['bottom_right', 'bottom_center', 'bottom_left', 'top_right', 'top_center', 'top_left', 'right_top', 'right_center', 'right_bottom', 'left_top', 'left_center', 'left_bottom'];
            if (position === 'top_left') return ['top_left', 'top_center', 'top_right', 'bottom_left', 'bottom_center', 'bottom_right', 'right_top', 'right_center', 'right_bottom', 'left_top', 'left_center', 'left_bottom'];
            if (position === 'top_center') return ['top_center', 'top_left', 'top_right', 'bottom_center', 'bottom_left', 'bottom_right', 'right_center', 'right_top', 'right_bottom', 'left_center', 'left_top', 'left_bottom'];
            if (position === 'top_right') return ['top_right', 'top_center', 'top_left', 'bottom_right', 'bottom_center', 'bottom_left', 'right_top', 'right_center', 'right_bottom', 'left_top', 'left_center', 'left_bottom'];
            if (position === 'right_top') return ['right_top', 'right_center', 'right_bottom', 'left_top', 'left_center', 'left_bottom', 'bottom_right', 'bottom_left', 'bottom_center', 'top_right', 'top_left', 'top_center'];
            if (position === 'right_center') return ['right_center', 'right_top', 'right_bottom', 'left_center', 'left_top', 'left_bottom', 'bottom_center', 'bottom_right', 'bottom_left', 'top_center', 'top_right', 'top_left'];
            if (position === 'right_bottom') return ['right_bottom', 'right_top', 'right_center', 'left_bottom', 'left_top', 'left_center', 'bottom_right', 'bottom_left', 'bottom_center', 'top_right', 'top_left', 'top_center'];
            if (position === 'left_top') return ['left_top', 'left_center', 'left_bottom', 'right_top', 'right_center', 'right_bottom', 'bottom_left', 'bottom_right', 'bottom_center', 'top_left', 'top_right', 'top_center'];
            if (position === 'left_center') return ['left_center', 'left_top', 'left_bottom', 'right_center', 'right_top', 'right_bottom', 'bottom_center', 'bottom_left', 'bottom_right', 'top_center', 'top_left', 'top_right'];
            if (position === 'left_bottom') return ['left_bottom', 'left_top', 'left_center', 'right_bottom', 'right_top', 'right_center', 'bottom_left', 'bottom_right', 'bottom_center', 'top_left', 'top_right', 'top_center'];

            return [position];
        };

        panel.applyPopoverPlacement = function (position) {
            var content = this.getPopoverContentElement();
            var item = this.getPopoverItemElement();
            if (!content || !item) {
                return false;
            }

            var margin = this.config.popoverMargin;
            var itemWidth = parseInt(item.offsetWidth || 0, 10);
            var itemHeight = parseInt(item.offsetHeight || 0, 10);
            var contentWidth = parseInt(content.offsetWidth || 0, 10);
            var contentHeight = parseInt(content.offsetHeight || 0, 10);

            var left = 0;
            var top = 0;

            if (position === 'bottom_left') {
                left = 0;
                top = itemHeight + margin;
            } else if (position === 'bottom_center') {
                left = (itemWidth - contentWidth) / 2;
                top = itemHeight + margin;
            } else if (position === 'bottom_right') {
                left = itemWidth - contentWidth;
                top = itemHeight + margin;
            } else if (position === 'top_left') {
                left = 0;
                top = -contentHeight - margin;
            } else if (position === 'top_center') {
                left = (itemWidth - contentWidth) / 2;
                top = -contentHeight - margin;
            } else if (position === 'top_right') {
                left = itemWidth - contentWidth;
                top = -contentHeight - margin;
            } else if (position === 'left_top') {
                left = -contentWidth - margin;
                top = 0;
            } else if (position === 'left_center') {
                left = -contentWidth - margin;
                top = (itemHeight - contentHeight) / 2;
            } else if (position === 'left_bottom') {
                left = -contentWidth - margin;
                top = itemHeight - contentHeight;
            } else if (position === 'right_top') {
                left = itemWidth + margin;
                top = 0;
            } else if (position === 'right_center') {
                left = itemWidth + margin;
                top = (itemHeight - contentHeight) / 2;
            } else if (position === 'right_bottom') {
                left = itemWidth + margin;
                top = itemHeight - contentHeight;
            }

            content.style.left = Math.round(left) + 'px';
            content.style.top = Math.round(top) + 'px';
            content.style.right = 'auto';

            var rect = content.getBoundingClientRect();
            return rect.top >= 8
                && rect.left >= 8
                && rect.right <= (window.innerWidth - 8)
                && rect.bottom <= (window.innerHeight - 8);
        };

        panel.clampPopoverToViewport = function () {
            var content = this.getPopoverContentElement();
            if (!content) {
                return;
            }

            var rect = content.getBoundingClientRect();
            var deltaX = 0;
            var deltaY = 0;

            if (rect.left < 8) {
                deltaX = 8 - rect.left;
            } else if (rect.right > (window.innerWidth - 8)) {
                deltaX = (window.innerWidth - 8) - rect.right;
            }

            if (rect.top < 8) {
                deltaY = 8 - rect.top;
            } else if (rect.bottom > (window.innerHeight - 8)) {
                deltaY = (window.innerHeight - 8) - rect.bottom;
            }

            if (deltaX === 0 && deltaY === 0) {
                return;
            }

            var left = parseFloat(content.style.left || '0') + deltaX;
            var top = parseFloat(content.style.top || '0') + deltaY;
            content.style.left = Math.round(left) + 'px';
            content.style.top = Math.round(top) + 'px';
        };

        panel.positionPopoverContent = function () {
            var content = this.getPopoverContentElement();
            if (!content) {
                return;
            }

            content.style.width = 'max-content';
            var candidates = this.getPopoverPositionCandidates(this.config.popoverPosition);
            var placed = false;

            for (var i = 0; i < candidates.length; i++) {
                if (this.applyPopoverPlacement(candidates[i])) {
                    placed = true;
                    break;
                }
            }

            if (!placed) {
                this.applyPopoverPlacement(this.config.popoverPosition);
                this.clampPopoverToViewport();
            }
        };

        panel.bindPopoverReposition = function () {
            if (this._popoverRepositionHandler) {
                return;
            }

            var panelRef = this;
            this._popoverRepositionHandler = function () {
                if (!panelRef.isOpen) {
                    return;
                }

                if (!panelRef.syncPopoverAnchor()) {
                    panelRef.closePopover();
                    return;
                }

                panelRef.positionPopoverContent();
            };

            window.addEventListener('resize', this._popoverRepositionHandler);
            window.addEventListener('scroll', this._popoverRepositionHandler, true);
        };

        panel.unbindPopoverReposition = function () {
            if (!this._popoverRepositionHandler) {
                return;
            }

            window.removeEventListener('resize', this._popoverRepositionHandler);
            window.removeEventListener('scroll', this._popoverRepositionHandler, true);
            this._popoverRepositionHandler = null;
        };

        panel.showPopover = function () {
            if (this.config.renderMode !== 'popover' || !this.htmlElement) {
                return;
            }

            var content = this.getPopoverContentElement();
            if (!content) {
                return;
            }

            if (!this.syncPopoverAnchor()) {
                return;
            }

            content.classList.remove('hidden');
            this.positionPopoverContent();
            this.isOpen = true;
            this.bindPopoverReposition();
        };

        panel.closePopover = function () {
            if (this.config.renderMode !== 'popover' || !this.htmlElement) {
                return;
            }

            var content = this.getPopoverContentElement();
            if (!content) {
                return;
            }

            content.classList.add('hidden');
            this.isOpen = false;
            this.unbindPopoverReposition();
        };

        panel.togglePopover = function () {
            this.isOpen ? this.closePopover() : this.showPopover();
        };

        panel.bindDialogEvents = function () {
            if (this.config.renderMode !== 'dialog' || !this.htmlElement) {
                return;
            }

            var panelRef = this;
            var titleElement = panelRef.htmlElement.querySelector('.panel_sheet_title');
            var closeButton = panelRef.htmlElement.querySelector('.panel_sheet_close_button');

            if (closeButton && !panelRef._dialogCloseButtonHandlerBound) {
                closeButton.addEventListener('click', function () {
                    panelRef.close();
                });
                panelRef._dialogCloseButtonHandlerBound = true;
            }

            if (titleElement && !panelRef._dialogDragHandlersBound) {
                var getClientY = function (event) {
                    if (event.touches && event.touches.length) {
                        return event.touches[0].clientY;
                    }
                    if (event.changedTouches && event.changedTouches.length) {
                        return event.changedTouches[0].clientY;
                    }
                    return event.clientY;
                };

                var startDrag = function (event) {
                    if (!panelRef.isMobileSheetActive()) {
                        return;
                    }

                    var target = event.target;
                    if (target && typeof target.closest === 'function' && target.closest('.panel_sheet_ignore_drag')) {
                        return;
                    }

                    panelRef.sheetState.isDragging = true;
                    panelRef.sheetState.startY = getClientY(event);
                    panelRef.sheetState.startHeight = parseInt(panelRef.helperElement ? panelRef.helperElement.offsetHeight : 0, 10);
                    panelRef.sheetState.lastHeight = panelRef.sheetState.startHeight;
                    titleElement.classList.add('dragging');
                };

                var moveDrag = function (event) {
                    if (!panelRef.sheetState.isDragging) {
                        return;
                    }

                    if (event.cancelable) {
                        event.preventDefault();
                    }

                    var currentY = getClientY(event);
                    var deltaY = currentY - panelRef.sheetState.startY;
                    var nextHeight = panelRef.sheetState.startHeight - deltaY;
                    panelRef.setSheetHeight(nextHeight, false);
                };

                var stopDrag = function (event) {
                    if (!panelRef.sheetState.isDragging) {
                        return;
                    }

                    panelRef.sheetState.isDragging = false;
                    titleElement.classList.remove('dragging');

                    var snapHeights = panelRef.getSheetSnapHeights();
                    var minHeight = snapHeights[0];
                    var lastHeight = parseInt(panelRef.sheetState.lastHeight || minHeight, 10);
                    var endY = getClientY(event);
                    var movedDown = (endY - panelRef.sheetState.startY) > 0;

                    if (panelRef.config.closeOnSwipeDown && movedDown && lastHeight < (minHeight - 60)) {
                        panelRef.close();
                        return;
                    }

                    var nearestIndex = 0;
                    var nearestDistance = Number.MAX_SAFE_INTEGER;
                    snapHeights.forEach(function (height, index) {
                        var distance = Math.abs(height - lastHeight);
                        if (distance < nearestDistance) {
                            nearestDistance = distance;
                            nearestIndex = index;
                        }
                    });

                    panelRef.sheetState.currentSnapIndex = nearestIndex;
                    panelRef.setSheetHeight(snapHeights[nearestIndex], true);
                };

                titleElement.addEventListener('touchstart', startDrag, { passive: true });
                titleElement.addEventListener('mousedown', startDrag);
                document.addEventListener('touchmove', moveDrag, { passive: false });
                document.addEventListener('mousemove', moveDrag);
                document.addEventListener('touchend', stopDrag);
                document.addEventListener('mouseup', stopDrag);

                panelRef._dialogDragHandlersBound = true;
            }

            if (!panelRef._dialogClickHandler) {
                panelRef._dialogClickHandler = function (event) {
                    var trigger = event.target.closest('[data-panel-id]');
                    if (trigger && trigger.getAttribute('data-panel-id') === panelRef.id && panelRef.config.toggleOnItemClick) {
                        panelRef.toggle();
                        return;
                    }

                    if (!panelRef.config.closeOnBackdrop || !panelRef.isOpen) {
                        return;
                    }

                    if (event.target === panelRef.htmlElement) {
                        panelRef.close();
                    }
                };

                document.addEventListener('click', panelRef._dialogClickHandler);
            }

            if (!panelRef._sheetResizeHandler) {
                panelRef._sheetResizeHandler = function () {
                    panelRef.syncSheetMode();
                };
                window.addEventListener('resize', panelRef._sheetResizeHandler);
            }
        };

        panel.bindPopoverEvents = function () {
            if (this.config.renderMode !== 'popover' || !this.htmlElement) {
                return;
            }

            var panelRef = this;
            var item = panelRef.getPopoverItemElement();
            var anchor = panelRef.getPopoverAnchorElement();

            if (panelRef.config.toggleOnItemClick) {
                if (panelRef.config.anchorElementId && anchor && !panelRef._popoverAnchorClickHandler) {
                    panelRef._popoverAnchorClickHandler = function (event) {
                        event.preventDefault();
                        panelRef.togglePopover();
                    };
                    anchor.addEventListener('click', panelRef._popoverAnchorClickHandler);
                } else if (!panelRef.config.anchorElementId && item && !panelRef._popoverAnchorClickHandler) {
                    panelRef._popoverAnchorClickHandler = function () {
                        panelRef.togglePopover();
                    };
                    item.addEventListener('click', panelRef._popoverAnchorClickHandler);
                }
            }

            if (!panelRef._popoverOutsideClickHandler) {
                panelRef._popoverOutsideClickHandler = function (event) {
                    if (!panelRef.config.closeOnOutsideClick || !panelRef.isOpen) {
                        return;
                    }

                    var isInsidePanel = panelRef.htmlElement.contains(event.target);
                    var anchorElement = panelRef.getPopoverAnchorElement();
                    var isInsideAnchor = anchorElement ? anchorElement.contains(event.target) : false;

                    if (!isInsidePanel && !isInsideAnchor) {
                        panelRef.closePopover();
                    }
                };
                document.addEventListener('click', panelRef._popoverOutsideClickHandler);
            }
        };

        panel.init = function () {
            if (this.config.renderMode === 'dialog') {
                this.bindDialogEvents();
                if (this.config.autoShow) {
                    this.show();
                }
                return;
            }

            this.bindPopoverEvents();
            if (this.config.autoShow) {
                this.showPopover();
            }
        };

        window[panel.id] = panel;
        return panel;
    };

    window.tbFrameworkPanelBootstrap = function (options) {
        if (!options) {
            return;
        }

        if (typeof window.tbFrameworkCreatePanelComponent !== 'function' || typeof window.addComponent !== 'function') {
            window.tbFrameworkPanelQueue.push(options);
            return;
        }

        var panel = window.tbFrameworkCreatePanelComponent(options);
        if (panel) {
            window.addComponent(panel);
        }
    };

    if (window.tbFrameworkPanelQueue.length) {
        var queued = window.tbFrameworkPanelQueue.splice(0, window.tbFrameworkPanelQueue.length);
        queued.forEach(function (panelOptions) {
            window.tbFrameworkPanelBootstrap(panelOptions);
        });
    }
})();
