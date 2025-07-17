<?php
class AdminKwkTopbarController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'kwk_topbar';
        $this->identifier = 'id_kwk_topbar';
        $this->className = 'KwkTopbar';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = ['delete' => ['text' => 'Delete selected', 'confirm' => 'Confirm deletion?']];

        parent::__construct();

        $this->fields_list = [
            'id_kwk_topbar' => ['title' => 'ID', 'align' => 'center', 'class' => 'fixed-width-xs'],
            'content' => ['title' => 'Content', 'maxlength' => 100], // Limite l'affichage
            'date_start' => ['title' => 'Start Date', 'type' => 'datetime'],
            'date_end' => ['title' => 'End Date', 'type' => 'datetime'],
            'background_color' => ['title' => 'Background Color', 'class' => 'fixed-width-sm'],
            'text_color' => ['title' => 'Text Color', 'class' => 'fixed-width-sm'],
            'active' => ['title' => 'Active', 'active' => 'status', 'type' => 'bool', 'align' => 'center'],
            'link' => ['title' => 'Link', 'maxlength' => 50],
            'target_blank' => ['title' => 'Target Blank', 'type' => 'bool', 'align' => 'center'],
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => ['title' => 'Top Bar', 'icon' => 'icon-cogs'],
            'input' => [
                [
                    'type' => 'textarea',
                    'label' => 'Content',
                    'name' => 'content',
                    'required' => true,
                    'desc' => 'Enter the content of the top bar (plain text).',
                ],
                [
                    'type' => 'datetime',
                    'label' => 'Start Date',
                    'name' => 'date_start',
                    'desc' => 'Date when the top bar starts displaying (optional).',
                ],
                [
                    'type' => 'datetime',
                    'label' => 'End Date',
                    'name' => 'date_end',
                    'desc' => 'Date when the top bar stops displaying (optional).',
                ],
                [
                    'type' => 'color',
                    'label' => 'Background Color',
                    'name' => 'background_color',
                    'required' => true,
                ],
                [
                    'type' => 'color',
                    'label' => 'Text Color',
                    'name' => 'text_color',
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => 'Active',
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => [
                        ['id' => 'active_on', 'value' => 1, 'label' => 'Enabled'],
                        ['id' => 'active_off', 'value' => 0, 'label' => 'Disabled'],
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => 'Link',
                    'name' => 'link',
                    'desc' => 'Optional URL for the top bar (leave empty for no link).',
                ],
                [
                    'type' => 'switch',
                    'label' => 'Open Link in New Tab',
                    'name' => 'target_blank',
                    'is_bool' => true,
                    'values' => [
                        ['id' => 'target_blank_on', 'value' => 1, 'label' => 'Yes'],
                        ['id' => 'target_blank_off', 'value' => 0, 'label' => 'No'],
                    ],
                ],
            ],
            'submit' => ['title' => 'Save'],
        ];

        return parent::renderForm();
    }
}