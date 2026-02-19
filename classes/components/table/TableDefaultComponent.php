<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class TableDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'table';
    protected const NAME = 'table_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        // Rewrite the tbody depending on specific keys and display values

        $columns = [];
        $new_data = [];

        foreach ($data['thead'] as $original_key => $column) {
            $key = empty($column['key']) ? $original_key : $column['key'];
            $display = empty($column['display']) ? '' : $column['display'];
            $align = empty($column['align']) ? 'left' : $column['align'];

            $columns[] = [
                'key' => $key,
                'display' => $display,
                'align' => $align,
            ];

            if (isset($column['align']) && $column['align']==='right') {
                $data['thead'][$original_key]['title'] = '<div style="text-align: right;">'.$column['title'].'</div>';
            }
            else if (isset($column['align']) && $column['align']==='center') {
                $data['thead'][$original_key]['title'] = '<div style="text-align: center;">'.$column['title'].'</div>';
            }
            else {
                $data['thead'][$original_key]['title'] = '<div style="text-align: left;">'.$column['title'].'</div>';
            }
        }

        foreach ($data['tbody'] as $key_row => $row) {
            foreach ($columns as $column) {
                $key = $column['key'];
                $display = $column['display'];

                if ($display==='date') {
                    $value = Tools::displayDate($row[$key]);
                }
                else if ($display==='price') {
                    $value = Tools::displayPrice($row[$key]);
                }
                else {
                    $value = $row[$key];
                }

                if ($column['align']==='right') {
                    $value = '<div style="text-align: right;">'.$value.'</div>';
                }
                else if ($column['align']==='center') {
                    $value = '<div style="text-align: center;">'.$value.'</div>';
                }
                else {
                    $value = '<div style="text-align: left;">'.$value.'</div>';
                }

                $new_data[$key_row][$key] = $value;
            }
        }

        $data['tbody'] = $new_data;
    }

    public function getDemoData(): array {
        return [
            'thead' => [
                ['key' => '', 'title' => 'Name', 'display' => ''],
                ['key' => '', 'title' => 'Surname', 'display' => ''],
                ['key' => '', 'title' => 'Street', 'display' => ''],
                ['key' => '', 'title' => 'Date', 'display' => 'date'],
                ['key' => '', 'title' => 'Price', 'display' => 'price', 'align' => 'right'],
            ],
            'tbody' => [
                ['Dan', 'Quinn', 'Down Hill 12', 'Dallas', '4.8'],
                ['Kevin', 'Stefanski', '', 'Cleveland', '5.0'],
                ['Sam Francisco', 'Rodriguez Perez', '', 'Cleveland', '5.0'],
            ],
        ];
    }
}



