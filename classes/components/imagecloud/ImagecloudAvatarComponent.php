<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudAvatarComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_avatar';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $images = [];

        foreach (self::getKronaAvatarProfiles(3) as $profile) {
            $images[] = [
                'image' => [
                    'imageEntity' => '',
                    'idEntity' => 0,
                    'src' => $profile['avatar'],
                    'width' => 0,
                    'height' => 0,
                    'alt' => $profile['name'],
                ],
                'id' => '',
                'link' => [
                    'href' => $profile['url'],
                    'title' => $profile['name'],
                ],
            ];
        }

        return [
            'images' => $images,
        ];
    }

    public static function getKronaAvatarProfiles(int $limit = 3): array
    {
        $profiles = [];

        foreach (self::getKronaAvatarRows($limit) as $row) {
            $avatar = trim((string)($row['avatar'] ?? ''));
            $avatarUrl = self::buildKronaAvatarUrl($avatar, (string)($row['date_upd'] ?? ''));
            if ($avatarUrl === '') {
                continue;
            }

            $name = trim((string)($row['pseudonym'] ?? ''));
            if ($name === '') {
                $name = trim((string)(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '')));
            }

            $profiles[] = [
                'id_customer' => (int)($row['id_customer'] ?? 0),
                'name' => $name,
                'avatar' => $avatarUrl,
                'url' => self::buildKronaCustomerUrl((int)($row['id_customer'] ?? 0), (string)($row['referral_code'] ?? '')),
            ];
        }

        return $profiles;
    }

    private static function getKronaAvatarRows(int $limit): array
    {
        if ($limit <= 0 || !class_exists('Db') || !class_exists('DbQuery')) {
            return [];
        }

        $tableName = _DB_PREFIX_ . 'genzo_krona_player';
        try {
            $tableExists = (bool)\Db::getInstance()->getValue("
                SELECT 1
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = '" . pSQL($tableName) . "'
            ");
        } catch (\Throwable) {
            return [];
        }

        if (!$tableExists) {
            return [];
        }

        $query = new \DbQuery();
        $query->select('p.id_customer, p.pseudonym, p.avatar, p.referral_code, p.date_upd, c.firstname, c.lastname');
        $query->from('genzo_krona_player', 'p');
        $query->innerJoin('customer', 'c', 'c.id_customer = p.id_customer');
        $query->where('p.active = 1');
        $query->where('p.banned = 0');
        $query->where("p.avatar IS NOT NULL AND p.avatar != '' AND p.avatar != 'no-avatar.jpg'");
        $query->orderBy('RAND()');
        $query->limit(max(1, $limit * 4));

        try {
            return (array)\Db::getInstance()->executeS($query);
        } catch (\Throwable) {
            return [];
        }
    }

    private static function buildKronaAvatarUrl(string $avatar, string $dateUpd): string
    {
        $avatar = basename($avatar);
        if ($avatar === '' || $avatar === 'no-avatar.jpg') {
            return '';
        }

        $filePath = _PS_UPLOAD_DIR_ . 'genzo_krona/img/avatar/' . $avatar;
        if (!file_exists($filePath)) {
            return '';
        }

        $timestamp = $dateUpd !== '' ? strtotime($dateUpd) : false;
        $version = $timestamp !== false ? '?=' . $timestamp : '';

        return '/upload/genzo_krona/img/avatar/' . $avatar . $version;
    }

    private static function buildKronaCustomerUrl(int $idCustomer, string $referralCode): string
    {
        if ($idCustomer <= 0) {
            return '';
        }

        $link = \Context::getContext()->link;
        if (!$link instanceof \Link) {
            return '';
        }

        $params = [];
        if (trim($referralCode) !== '') {
            $params['referral_code'] = trim($referralCode);
        }

        return (string)$link->getModuleLink('genzo_krona', 'overview', $params);
    }
}



