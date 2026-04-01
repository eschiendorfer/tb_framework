<?php

require_once(dirname(__FILE__) . '/enums/ComponentChannel.php');

final class EntityDataLoader
{
    public const ENTITY_TYPE_PRODUCT = 'product';
    public const ENTITY_TYPE_CATEGORY = 'category';
    public const ENTITY_TYPE_MANUFACTURER = 'manufacturer';
    public const ENTITY_TYPE_BLOG = 'blog';
    public const SOURCE_TYPE_CATEGORY = 'category';
    public const SOURCE_TYPE_MANUFACTURER = 'manufacturer';
    public const SOURCE_TYPE_PRODUCTS = 'products';
    public const SOURCE_TYPE_CUSTOMER_PRODUCT_RECOMMENDATION = 'customerproductrecommendation';
    public const SOURCE_TYPE_NEW_PRODUCT_RECOMMENDATION = 'newproductrecommendation';
    public const SOURCE_TYPE_SALE_PRODUCT_RECOMMENDATION = 'saleproductrecommendation';

    public static function resolveComponentChannel(string $runtimeChannel): ComponentChannel
    {
        $runtimeChannel = trim(strtolower($runtimeChannel));

        if ($runtimeChannel === ComponentChannel::EMAIL->value) {
            return ComponentChannel::EMAIL;
        }

        return ComponentChannel::WEB;
    }

    public static function getProductSourceTypeOptions(): array
    {
        return [
            ['label' => 'Kategorie', 'value' => self::SOURCE_TYPE_CATEGORY],
            ['label' => 'Hersteller', 'value' => self::SOURCE_TYPE_MANUFACTURER],
            ['label' => 'Produkte (CSV IDs)', 'value' => self::SOURCE_TYPE_PRODUCTS],
            ['label' => 'Kundenempfehlung (Best Fit)', 'value' => self::SOURCE_TYPE_CUSTOMER_PRODUCT_RECOMMENDATION],
            ['label' => 'Neuheiten-Empfehlung', 'value' => self::SOURCE_TYPE_NEW_PRODUCT_RECOMMENDATION],
            ['label' => 'Sale-Empfehlung', 'value' => self::SOURCE_TYPE_SALE_PRODUCT_RECOMMENDATION],
        ];
    }

    public static function getTitleForProduct(array $product, bool $showPrice = false): string
    {
        $title = trim((string)($product['name'] ?? ''));

        if (!$showPrice) {
            return $title;
        }

        if (!array_key_exists('price', $product)) {
            return $title;
        }

        return $title . ' (' . \Tools::displayPrice((float)$product['price']) . ')';
    }

    public static function getSubtitleForProduct(array $product): string
    {
        $featuresHtml = trim((string)($product['features_html'] ?? ''));
        if ($featuresHtml !== '') {
            return $featuresHtml;
        }

        $manufacturerName = trim((string)($product['manufacturer_name'] ?? ''));
        $mainCategoryName = trim((string)($product['main_category_name'] ?? ''));

        if ($mainCategoryName === '') {
            $idCategoryDefault = (int)($product['id_category_default'] ?? 0);
            if ($idCategoryDefault > 0) {
                $mainCategoryName = self::getCategoryName($idCategoryDefault);
            }
        }

        if ($mainCategoryName !== '' && $manufacturerName !== '') {
            return $mainCategoryName . ' von ' . $manufacturerName;
        }

        if ($mainCategoryName !== '') {
            return $mainCategoryName;
        }

        return $manufacturerName;
    }

    public static function getLinkData(array $params): array
    {
        $context = \Context::getContext();
        $idLang = (int)($context->language->id ?? 0);
        $idShop = (int)($context->shop->id ?? 0);

        $linkInformation = [
            'href' => '',
            'title' => '',
            'active' => true,
            'target' => '',
        ];

        if (isset($params['external'])) {
            $linkInformation['href'] = (string)$params['external'];
            $linkInformation['title'] = (string)($params['title'] ?? $params['external']);
            $linkInformation['target'] = '_blank';
        } elseif (isset($params['category'])) {
            $idCategory = (int)$params['category'];
            $category = new \Category($idCategory, $idLang > 0 ? $idLang : null, $idShop > 0 ? $idShop : null);
            $linkInformation['href'] = (string)$context->link->getCategoryLink($idCategory);
            $linkInformation['title'] = (string)($category->name ?? '');
            $linkInformation['active'] = (bool)($category->active ?? false);
        } elseif (isset($params['product'])) {
            $idProduct = (int)$params['product'];
            $product = new \Product($idProduct, false, $idLang > 0 ? $idLang : null, $idShop > 0 ? $idShop : null);
            $linkInformation['title'] = (string)($product->name ?? '');
            $linkInformation['href'] = (string)$context->link->getProductLink($idProduct);
            $linkInformation['active'] = (bool)($product->active ?? false);
        } elseif (isset($params['cms'])) {
            $idCms = (int)$params['cms'];
            $cms = new \CMS($idCms, $idLang > 0 ? $idLang : null, $idShop > 0 ? $idShop : null);
            $linkInformation['href'] = (string)$context->link->getCMSLink($idCms);
            $linkInformation['active'] = (bool)($cms->active ?? false);
            $linkInformation['title'] = (string)($cms->meta_title ?? '');
        } elseif (isset($params['blog']) && class_exists('\SimpleBlogPost')) {
            $idSimpleBlogPost = (int)$params['blog'];
            $blogpost = new \SimpleBlogPost($idSimpleBlogPost, $idLang > 0 ? $idLang : null, $idShop > 0 ? $idShop : null);
            $linkInformation['href'] = (string)($blogpost->url ?? '');
            $linkInformation['active'] = (bool)($blogpost->active ?? false);
            $linkInformation['title'] = (string)($blogpost->title ?? '');
        } elseif (isset($params['manufacturer'])) {
            $idManufacturer = (int)$params['manufacturer'];
            $manufacturer = new \Manufacturer($idManufacturer, $idLang > 0 ? $idLang : null);
            $linkInformation['href'] = (string)$context->link->getManufacturerLink($manufacturer);
            $linkInformation['active'] = (bool)($manufacturer->active ?? false);
            $linkInformation['title'] = (string)($manufacturer->name ?? '');
        } elseif (!empty($params['customproductlist'])) {
            $idCustomProductList = (int)$params['customproductlist'];
            if (class_exists('\FilterCustomProductList')) {
                $customProductList = new \FilterCustomProductList($idCustomProductList, $idLang);
                $linkInformation['href'] = (string)($customProductList->url ?? '');
                $linkInformation['active'] = true;
                $linkInformation['title'] = (string)($customProductList->name ?? '');
            }
        }

        if (!empty($params['title'])) {
            $linkInformation['title'] = (string)$params['title'];
        }

        if (!empty($params['href'])) {
            $linkInformation['href'] = (string)$params['href'];
        }

        return $linkInformation;
    }

    public static function getProductDataByParams(
        array $params,
        array $runtimeContext = [],
        ?ComponentChannel $channel = null
    ): array {
        $sourceType = self::resolveProductSourceType($params);
        $limit = (int)($params['limit'] ?? 5);

        if ($sourceType === self::SOURCE_TYPE_CATEGORY || $sourceType === self::SOURCE_TYPE_MANUFACTURER) {
            $idEntity = (int)($params['id_entity'] ?? 0);
            if ($idEntity <= 0) {
                $idEntity = (int)($params[$sourceType] ?? 0);
            }
            if ($idEntity <= 0) {
                return [];
            }

            return self::loadProductsFromFilter([], $limit, $sourceType, $idEntity, $channel);
        }

        if ($sourceType === self::SOURCE_TYPE_PRODUCTS) {
            $idsProduct = explode(',', (string)($params['products'] ?? ''));
            $idsProduct = self::normalizeIds($idsProduct);
            if (empty($idsProduct)) {
                return [];
            }
            $forcedLimit = isset($params['limit']) ? (int)$params['limit'] : 0;
            $loadLimit = $forcedLimit > 0 ? $forcedLimit : count($idsProduct);

            return self::loadProductsFromFilter($idsProduct, $loadLimit, '', 0, $channel);
        }

        if (self::isRecommendationSourceType($sourceType)) {
            if ($limit <= 0) {
                return [];
            }

            $idsProduct = self::getRecommendedProductIds($sourceType, $limit, $runtimeContext);
            if (empty($idsProduct)) {
                return [];
            }

            $forcedLimit = isset($params['limit']) ? (int)$params['limit'] : 0;
            $loadLimit = $forcedLimit > 0 ? $forcedLimit : count($idsProduct);

            return self::loadProductsFromFilter($idsProduct, $loadLimit, '', 0, $channel);
        }

        // Legacy fallback for existing shortcodes without source_type.
        return self::loadProductsByLegacyParams($params, $runtimeContext, $channel, $limit);
    }

    public static function loadByEntityType(
        string $entityType,
        int $idEntity,
        $contextOrChannel = [],
        ?ComponentChannel $channel = null
    ): array {
        if ($contextOrChannel instanceof ComponentChannel) {
            $channel = $contextOrChannel;
        }

        $entityType = trim(strtolower($entityType));
        if ($idEntity <= 0) {
            return [];
        }

        if ($entityType === self::ENTITY_TYPE_PRODUCT) {
            return self::loadProductById($idEntity, $channel);
        }

        if ($entityType === self::ENTITY_TYPE_CATEGORY) {
            return self::loadCategoryById($idEntity);
        }

        if ($entityType === self::ENTITY_TYPE_MANUFACTURER) {
            return self::loadManufacturerById($idEntity);
        }

        if ($entityType === self::ENTITY_TYPE_BLOG) {
            return self::loadBlogById($idEntity);
        }

        return [];
    }

    public static function loadProductsByIds(array $idsProduct, ?ComponentChannel $channel = null): array
    {
        $idsProduct = self::normalizeIds($idsProduct);
        if (empty($idsProduct)) {
            return [];
        }

        $context = \Context::getContext();
        $idLang = (int)($context->language->id ?? 0);
        $idShop = (int)($context->shop->id ?? 0);
        if ($idLang <= 0 || $idShop <= 0) {
            return [];
        }

        $idsProductSql = implode(',', $idsProduct);
        $nbDaysNewProduct = (int)\Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $dateNewRelevant = pSQL(date('Y-m-d', strtotime("-{$nbDaysNewProduct} days")) . ' 00:00:00');

        $sql = '
            SELECT
                p.id_product,
                p.id_category_default,
                ps.price,
                ps.show_price,
                p.ean13,
                p.reference,
                lsa.out_of_stock,
                p.customizable,
                p.is_virtual,
                pl.link_rewrite,
                pl.name,
                pl.description_short,
                pl.available_now,
                pl.available_later,
                m.name AS manufacturer_name,
                i.id_image,
                IF(ps.`date_add` > "' . $dateNewRelevant . '", 1, 0) AS new
            FROM `' . _DB_PREFIX_ . 'product` p
            INNER JOIN `' . _DB_PREFIX_ . 'product_shop` ps
                ON p.id_product = ps.id_product AND ps.id_shop = ' . $idShop . '
            INNER JOIN `' . _DB_PREFIX_ . 'stock_available` lsa
                ON lsa.id_product = p.id_product AND lsa.id_product_attribute = 0
            INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                ON p.id_product = pl.id_product AND pl.id_shop = ' . $idShop . ' AND pl.id_lang = ' . $idLang . '
            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
                ON p.id_manufacturer = m.id_manufacturer
            LEFT JOIN `' . _DB_PREFIX_ . 'image` i
                ON p.id_product = i.id_product AND i.cover = 1
            WHERE p.id_product IN (' . $idsProductSql . ')
              AND ps.active = 1
            GROUP BY p.id_product
        ';

        $rows = (array)\Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (empty($rows)) {
            return [];
        }

        $products = \Product::getProductsProperties($idLang, $rows);
        if (empty($products) || !is_array($products)) {
            return [];
        }

        $products = self::reorderProductsByIds($idsProduct, $products);
        $products = self::prepareProductsForChannel($products, $channel);

        return $products;
    }

    private static function loadProductsFromFilter(
        array $idsProduct,
        int $limit = 0,
        string $entityType = '',
        int $idEntity = 0,
        ?ComponentChannel $channel = null
    ): array {
        $idsProductNormalized = self::normalizeIds($idsProduct);

        if ($limit > 0) {
            $effectiveLimit = $limit;
        } elseif (!empty($idsProductNormalized)) {
            $effectiveLimit = count($idsProductNormalized);
        } else {
            $effectiveLimit = 5;
        }

        $genzoFilter = \Module::getInstanceByName('genzo_filter');
        if (!is_object($genzoFilter) || !method_exists($genzoFilter, 'getProducts')) {
            return [];
        }

        $genzoFilter->orderBy = '';
        $genzoFilter->orderWay = '';
        $genzoFilter->orderSql = '';
        $genzoFilter->limit = $effectiveLimit;
        $genzoFilter->entity_type = trim((string)$entityType);
        $genzoFilter->id_entity = (int)$idEntity;
        $genzoFilter->ids_product = $idsProductNormalized;
        $genzoFilter->offset = 0;
        $genzoFilter->filters = [];

        $products = (array)$genzoFilter->getProducts('products');
        if (empty($products)) {
            return [];
        }

        $products = self::reorderProductsByIds($idsProductNormalized, $products);

        if ($effectiveLimit > 0 && count($products) > $effectiveLimit) {
            $products = array_slice($products, 0, $effectiveLimit);
        }

        return self::prepareProductsForChannel($products, $channel);
    }

    private static function getRecommendedProductIds(
        string $recommendationType,
        int $limit,
        array $runtimeContext = []
    ): array {
        if ($limit <= 0 || !class_exists('\CrmModule\CustomerProductRecommendationCrm')) {
            return [];
        }

        $idCustomer = self::resolveCustomerId($runtimeContext);
        if ($idCustomer <= 0) {
            return [];
        }

        return \CrmModule\CustomerProductRecommendationCrm::getRecommendedProductIds(
            $idCustomer,
            $recommendationType,
            $limit
        );
    }

    private static function resolveProductSourceType(array $params): string
    {
        $sourceType = trim(strtolower((string)($params['source_type'] ?? '')));
        if (in_array($sourceType, self::getSupportedProductSourceTypes(), true)) {
            return $sourceType;
        }

        return '';
    }

    /**
     * @return string[]
     */
    private static function getSupportedProductSourceTypes(): array
    {
        return [
            self::SOURCE_TYPE_CATEGORY,
            self::SOURCE_TYPE_MANUFACTURER,
            self::SOURCE_TYPE_PRODUCTS,
            self::SOURCE_TYPE_CUSTOMER_PRODUCT_RECOMMENDATION,
            self::SOURCE_TYPE_NEW_PRODUCT_RECOMMENDATION,
            self::SOURCE_TYPE_SALE_PRODUCT_RECOMMENDATION,
        ];
    }

    private static function isRecommendationSourceType(string $sourceType): bool
    {
        return in_array($sourceType, [
            self::SOURCE_TYPE_CUSTOMER_PRODUCT_RECOMMENDATION,
            self::SOURCE_TYPE_NEW_PRODUCT_RECOMMENDATION,
            self::SOURCE_TYPE_SALE_PRODUCT_RECOMMENDATION,
        ], true);
    }

    private static function loadProductsByLegacyParams(
        array $params,
        array $runtimeContext,
        ?ComponentChannel $channel,
        int $limit
    ): array {
        $entityType = '';
        $idEntity = 0;
        $idsProduct = [];
        $shouldSortByInputOrder = false;

        if (!empty($params[self::ENTITY_TYPE_CATEGORY])) {
            $entityType = self::ENTITY_TYPE_CATEGORY;
            $idEntity = (int)$params[self::ENTITY_TYPE_CATEGORY];
        } elseif (!empty($params[self::ENTITY_TYPE_MANUFACTURER])) {
            $entityType = self::ENTITY_TYPE_MANUFACTURER;
            $idEntity = (int)$params[self::ENTITY_TYPE_MANUFACTURER];
        }

        if (!empty($params['products'])) {
            $idsProduct = explode(',', (string)$params['products']);
            $limit = count($idsProduct);
            $shouldSortByInputOrder = true;
        } elseif (!empty($params['entity_type'])) {
            $entityTypeRecommendation = trim(strtolower((string)$params['entity_type']));
            if ($limit > 0 && self::isSupportedRecommendationType($entityTypeRecommendation)) {
                $idsProduct = self::getRecommendedProductIds($entityTypeRecommendation, $limit, $runtimeContext);
                if (empty($idsProduct)) {
                    return [];
                }
                $limit = count($idsProduct);
                $shouldSortByInputOrder = true;
            }
        }

        if ($shouldSortByInputOrder) {
            $forcedLimit = isset($params['limit']) ? (int)$params['limit'] : 0;
            $loadLimit = $forcedLimit > 0 ? $forcedLimit : count($idsProduct);

            return self::loadProductsFromFilter($idsProduct, $loadLimit, $entityType, $idEntity, $channel);
        }

        return self::loadProductsFromFilter([], $limit, $entityType, $idEntity, $channel);
    }

    private static function resolveCustomerId(array $runtimeContext): int
    {
        $idCustomer = (int)($runtimeContext['id_customer'] ?? 0);
        if ($idCustomer > 0) {
            return $idCustomer;
        }

        return (int)(\Context::getContext()->customer->id ?? 0);
    }

    private static function isSupportedRecommendationType(string $recommendationType): bool
    {
        if (!class_exists('\CrmModule\CustomerProductRecommendationCrm')) {
            return false;
        }

        return \CrmModule\CustomerProductRecommendationCrm::isSupportedRecommendationType($recommendationType);
    }

    private static function loadProductById(int $idProduct, ?ComponentChannel $channel = null): array
    {
        $products = self::loadProductsByIds([$idProduct], $channel);

        if (empty($products[0]) || !is_array($products[0])) {
            return [];
        }

        return $products[0];
    }

    private static function loadCategoryById(int $idCategory): array
    {
        $context = \Context::getContext();
        $idLang = (int)($context->language->id ?? 0);
        $idShop = (int)($context->shop->id ?? 0);

        $category = new \Category($idCategory, $idLang > 0 ? $idLang : null, $idShop > 0 ? $idShop : null);
        if (!\Validate::isLoadedObject($category)) {
            return [];
        }

        $title = trim((string)($category->name ?? ''));
        if ($title === '') {
            return [];
        }

        return [
            'id_category' => (int)$category->id,
            'name' => $title,
            'description' => trim((string)($category->description ?? '')),
            'link' => (string)$context->link->getCategoryLink((int)$category->id),
            'image_src' => self::resolveCategoryImageSrc($category),
        ];
    }

    private static function loadManufacturerById(int $idManufacturer): array
    {
        $context = \Context::getContext();
        $idLang = (int)($context->language->id ?? 0);
        $manufacturer = new \Manufacturer($idManufacturer, $idLang > 0 ? $idLang : null);

        if (!\Validate::isLoadedObject($manufacturer)) {
            return [];
        }

        $title = trim((string)($manufacturer->name ?? ''));
        if ($title === '') {
            return [];
        }

        $description = trim((string)($manufacturer->description ?? ''));
        if ($description === '') {
            $description = trim((string)($manufacturer->short_description ?? ''));
        }

        return [
            'id_manufacturer' => (int)$manufacturer->id,
            'name' => $title,
            'description' => $description,
            'link' => (string)$context->link->getManufacturerLink($manufacturer),
            'image_src' => self::resolveManufacturerImageSrc((int)$manufacturer->id),
        ];
    }

    private static function loadBlogById(int $idSimpleBlogPost): array
    {
        if (!class_exists('\SimpleBlogPost')) {
            return [];
        }

        $context = \Context::getContext();
        $idLang = (int)($context->language->id ?? 0);
        $idShop = (int)($context->shop->id ?? 0);

        $blogpost = new \SimpleBlogPost($idSimpleBlogPost, $idLang > 0 ? $idLang : null, $idShop > 0 ? $idShop : null);
        if (!\Validate::isLoadedObject($blogpost) || !(bool)($blogpost->active ?? false)) {
            return [];
        }

        $title = trim((string)($blogpost->title ?? ''));
        $url = trim((string)($blogpost->url ?? ''));
        if ($title === '' || $url === '') {
            return [];
        }

        $imageSrc = '';
        $bannerThumb = trim((string)($blogpost->banner_thumb ?? ''));
        if ($bannerThumb !== '') {
            $imageSrc = (string)$context->link->getBaseLink() . $bannerThumb;
            $imageSrc = str_replace('//modules', '/modules', $imageSrc);
        }

        return [
            'id_simpleblog_post' => (int)$blogpost->id,
            'title' => $title,
            'url' => $url,
            'short_description' => trim((string)($blogpost->short_content ?? '')),
            'image_src' => $imageSrc,
        ];
    }

    private static function getCategoryName(int $idCategory, ?int $idLang = null): string
    {
        $cache = self::getCategoryNameMap($idLang);
        return trim((string)($cache[$idCategory] ?? ''));
    }

    private static function getCategoryNameMap(?int $idLang = null): array
    {
        static $cacheByLang = [];

        if (!$idLang) {
            $idLang = (int)\Configuration::get('PS_LANG_DEFAULT');
        }

        if (isset($cacheByLang[$idLang])) {
            return $cacheByLang[$idLang];
        }

        $rows = (array)\Category::getAllCategoriesName(
            null,
            $idLang,
            false,
            null,
            false
        );

        $cache = [];
        foreach ($rows as $row) {
            $idCategory = (int)($row['id_category'] ?? 0);
            if ($idCategory <= 0) {
                continue;
            }
            $cache[$idCategory] = trim((string)($row['name'] ?? ''));
        }

        $cacheByLang[$idLang] = $cache;
        return $cacheByLang[$idLang];
    }

    private static function resolveCategoryImageSrc(\Category $category): string
    {
        try {
            $link = \Context::getContext()->link;
            $linkRewrite = trim((string)($category->link_rewrite ?? ''));
            $image = (string)$link->getCatImageLink($linkRewrite, (int)$category->id, 'category_default');
            if ($image !== '') {
                return $image;
            }
        } catch (\Throwable) {
        }

        return self::resolveImageSrcFromDisk('c', (int)$category->id, [
            (string)$category->id . '-category_default.jpg',
            (string)$category->id . '.jpg',
        ]);
    }

    private static function resolveManufacturerImageSrc(int $idManufacturer): string
    {
        return self::resolveImageSrcFromDisk('m', $idManufacturer, [
            (string)$idManufacturer . '.jpg',
            (string)$idManufacturer . '.jpeg',
            (string)$idManufacturer . '.png',
            (string)$idManufacturer . '.webp',
        ]);
    }

    private static function resolveImageSrcFromDisk(string $directory, int $idEntity, array $candidateFiles): string
    {
        if ($idEntity <= 0) {
            return '';
        }

        $absoluteDirectory = _PS_ROOT_DIR_ . '/img/' . trim($directory, '/') . '/';
        foreach ($candidateFiles as $candidateFile) {
            $candidateFile = trim((string)$candidateFile);
            if ($candidateFile === '') {
                continue;
            }

            if (!file_exists($absoluteDirectory . $candidateFile)) {
                continue;
            }

            $baseUri = defined('__PS_BASE_URI__') ? rtrim((string)__PS_BASE_URI__, '/') : '';
            return $baseUri . '/img/' . trim($directory, '/') . '/' . $candidateFile;
        }

        return '';
    }

    private static function normalizeIds(array $ids): array
    {
        $normalized = [];

        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id > 0) {
                $normalized[] = $id;
            }
        }

        return array_values(array_unique($normalized));
    }

    private static function reorderProductsByIds(array $idsProductOrder, array $products): array
    {
        $positionById = [];
        foreach ($idsProductOrder as $position => $idProduct) {
            $positionById[(int)$idProduct] = (int)$position;
        }

        usort($products, static function (array $left, array $right) use ($positionById): int {
            $leftId = (int)($left['id_product'] ?? 0);
            $rightId = (int)($right['id_product'] ?? 0);

            $leftPosition = $positionById[$leftId] ?? PHP_INT_MAX;
            $rightPosition = $positionById[$rightId] ?? PHP_INT_MAX;

            if ($leftPosition === $rightPosition) {
                return 0;
            }

            return $leftPosition <=> $rightPosition;
        });

        return $products;
    }

    private static function prepareProductsForChannel(array $products, ?ComponentChannel $channel): array
    {
        if ($channel !== ComponentChannel::EMAIL) {
            return $products;
        }

        foreach ($products as &$product) {
            $productUrl = self::resolveProductUrl($product);
            $imageUrl = self::resolveProductImageUrl($product);

            $product['product_url'] = \ImageHelper::convertToAbsoluteUrl($productUrl);
            $product['image_url'] = \ImageHelper::convertToAbsoluteUrl($imageUrl);
            $product['link'] = $product['product_url'];
            $product['img'] = $product['image_url'];
        }
        unset($product);

        return $products;
    }

    private static function resolveProductUrl(array $product): string
    {
        $link = trim((string)($product['link'] ?? ''));
        if ($link !== '') {
            return $link;
        }

        $idProduct = (int)($product['id_product'] ?? 0);
        if ($idProduct <= 0) {
            return '';
        }

        $linkRewrite = (string)($product['link_rewrite'] ?? '');
        return (string)\Context::getContext()->link->getProductLink($idProduct, $linkRewrite);
    }

    private static function resolveProductImageUrl(array $product): string
    {
        $image = trim((string)($product['img'] ?? ''));
        if ($image !== '') {
            return $image;
        }

        $idProduct = (int)($product['id_product'] ?? 0);
        $idImage = (int)($product['id_image'] ?? 0);

        if ($idImage <= 0 && $idProduct > 0) {
            $cover = \Product::getCover($idProduct);
            $idImage = (int)($cover['id_image'] ?? 0);
        }

        if ($idImage <= 0) {
            return '';
        }

        $linkRewrite = (string)($product['link_rewrite'] ?? '');
        return (string)\Context::getContext()->link->getImageLink($linkRewrite, $idImage, 'home_default');
    }
}
