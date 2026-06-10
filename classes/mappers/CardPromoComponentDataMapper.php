<?php

final class CardPromoComponentDataMapper
{
    private const ENTITY_TYPE_TEASER_BANNER = 'teaser_banner';

    public static function resolveCardPromo(
        string $entityType,
        int $idEntity,
        \CoreExtension\OutputChannelEnum $channel
    ): array {
        if (self::normalizeEntityType($entityType) !== self::ENTITY_TYPE_TEASER_BANNER || $idEntity <= 0) {
            return [];
        }

        return self::resolveTeaserBanner($idEntity, $channel);
    }

    private static function resolveTeaserBanner(int $idTeaserBanner, \CoreExtension\OutputChannelEnum $channel): array
    {
        if (!self::ensureTeaserBannerClassAvailable()) {
            return [];
        }

        $teaserBanner = new \TeaserBanner($idTeaserBanner);
        if (!\Validate::isLoadedObject($teaserBanner) || !(bool)$teaserBanner->active) {
            return [];
        }

        $context = \Context::getContext();
        $idLang = (int)($context->language->id ?? 0);
        $idLangDefault = (int)\Configuration::get('PS_LANG_DEFAULT');

        $url = (string)$teaserBanner->url;
        $imageName = (string)$teaserBanner->image;
        $imageSrc = $imageName !== ''
            ? _MODULE_DIR_ . 'genzo_theme_configurator/views/img/promo/' . $imageName
            : '';

        if ($channel === \CoreExtension\OutputChannelEnum::EMAIL) {
            $imageSrc = self::toAbsoluteUrl($imageSrc);
            $url = self::toAbsoluteUrl($url);
        }

        $data = [
            'title' => self::getLangValue($teaserBanner->title, $idLang, $idLangDefault),
            'subtitle' => self::getLangValue($teaserBanner->subtitle, $idLang, $idLangDefault),
            'description' => self::getLangValue($teaserBanner->description, $idLang, $idLangDefault),
            'image' => [
                'src' => $imageSrc,
                'link' => $url,
            ],
        ];

        $cta = self::getLangValue($teaserBanner->cta, $idLang, $idLangDefault);
        if ($cta !== '') {
            $data['button'] = [
                'title' => $cta,
                'link' => $url,
            ];
        }

        if (self::ensureConversionTypeEnumAvailable()) {
            \ComponentDefinition::addTrackingToData(
                $data,
                [
                    \CrmModule\ConversionTypeEnum::VIEW->value,
                    \CrmModule\ConversionTypeEnum::CLICK->value,
                ],
                \CoreExtension\EntityTypeEnum::TEASER_BANNER->value,
                $idTeaserBanner
            );
        }

        return $data;
    }

    private static function ensureTeaserBannerClassAvailable(): bool
    {
        if (class_exists('\TeaserBanner')) {
            return true;
        }

        if (!\Module::isEnabled('genzo_theme_configurator')) {
            return false;
        }

        $file = _PS_MODULE_DIR_ . 'genzo_theme_configurator/classes/TeaserBanner.php';
        if (!file_exists($file)) {
            return false;
        }

        require_once $file;

        return class_exists('\TeaserBanner');
    }

    private static function ensureConversionTypeEnumAvailable(): bool
    {
        if (class_exists('\CrmModule\ConversionTypeEnum')) {
            return true;
        }

        $file = _PS_MODULE_DIR_ . 'genzo_crm/autoload.php';
        if (!file_exists($file)) {
            return false;
        }

        require_once $file;

        return class_exists('\CrmModule\ConversionTypeEnum');
    }

    private static function getLangValue($values, int $idLang, int $idLangDefault): string
    {
        if (!is_array($values)) {
            return (string)$values;
        }

        if (isset($values[$idLang]) && $values[$idLang] !== '') {
            return (string)$values[$idLang];
        }

        if (isset($values[$idLangDefault]) && $values[$idLangDefault] !== '') {
            return (string)$values[$idLangDefault];
        }

        return (string)reset($values);
    }

    private static function normalizeEntityType(string $entityType): string
    {
        return strtolower(trim($entityType));
    }

    private static function toAbsoluteUrl(string $url): string
    {
        return class_exists('\ImageHelper') ? \ImageHelper::convertToAbsoluteUrl($url) : $url;
    }
}
