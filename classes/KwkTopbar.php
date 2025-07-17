<?php
class KwkTopbar extends ObjectModel
{
    public $id_kwk_topbar;
    public $content;
    public $date_start;
    public $date_end;
    public $background_color;
    public $text_color;
    public $active;
    public $link;
    public $target_blank;

    public static $definition = [
        'table' => 'kwk_topbar',
        'primary' => 'id_kwk_topbar',
        'fields' => [
            'content' => ['type' => self::TYPE_STRING, 'required' => true],
            'date_start' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_end' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'background_color' => ['type' => self::TYPE_STRING, 'validate' => 'isColor', 'size' => 7],
            'text_color' => ['type' => self::TYPE_STRING, 'validate' => 'isColor', 'size' => 7],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'link' => ['type' => self::TYPE_STRING, 'validate' => 'isUrl', 'size' => 255],
            'target_blank' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
        ],
    ];

    public static function getActiveTopbars()
    {
        $now = date('Y-m-d H:i:s');
        return Db::getInstance()->executeS(
            'SELECT * FROM ' . _DB_PREFIX_ . 'kwk_topbar 
            WHERE active = 1 
            AND (date_start IS NULL OR date_start <= \'' . pSQL($now) . '\')
            AND (date_end IS NULL OR date_end >= \'' . pSQL($now) . '\')'
        );
    }
}