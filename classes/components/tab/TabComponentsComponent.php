<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class TabComponentsComponent extends ComponentDefinition {
    protected const TYPE = 'tab';
    protected const NAME = 'tab_components';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (isset($data['displayProductTab']) && $data['displayProductTab']) {
            $renderStateSnapshot = FrameworkRenderState::snapshotCallTracking();

            FrameworkRenderState::resetCallTracking();

            $displayProductTabs = Hook::exec('displayProductTab', [], null, true);
            $displayProductsTabContents = Hook::exec('displayProductTabContent', ['id_product' => $data['id_product']], null, true);

            foreach ($displayProductTabs as $module => $displayProductTab) {
                $data['tabs'][] = [
                    'title' => $displayProductTab,
                    'content' => $displayProductsTabContents[$module],
                ];
            }

            FrameworkRenderState::restoreCallTracking($renderStateSnapshot);
        }

        $display_set = false;

        foreach ($data['tabs'] as $key => &$tab) {
            if (empty($tab['title']) || empty(trim($tab['title'])) || empty($tab['content']) || empty(trim($tab['content']))) {
                unset($data['tabs'][$key]);
                continue;
            }

            if (isset($tab['display']) && $tab['display']) {
                !$display_set ? $display_set = true : $tab['display'] = false;
            }
        }

        if (!$display_set) {
            $firstKey = array_key_first($data['tabs']);
            $data['tabs'][$firstKey]['display'] = true;
        }
    }

    public function getDemoData(): array {
        $carousel = CarouselComponentsComponent::fetchDemo();
        $header = HeaderDefaultComponent::fetchDemo();
        $imagecloud = ImagecloudDefaultComponent::fetchDemo();

        return [
            'tabs' => [
                ['title' => 'Carousel', 'content' => $carousel, 'display' => true],
                ['title' => 'Header', 'content' => $header],
                ['title' => 'Imagecloud', 'content' => $imagecloud, 'boxed' => false],
            ]
        ];
    }
}



