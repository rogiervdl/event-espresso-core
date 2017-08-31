<?php
defined('EVENT_ESPRESSO_VERSION') || exit('No direct access allowed.');

/**
 * Messages_Template_List_Table
 * extends EE_Admin_List_Table class
 * @package         Event Espresso
 * @subpackage      /includes/core/admin/messages
 * @author          Darren Ethier
 * ------------------------------------------------------------------------
 */
class Messages_Template_List_Table extends EE_Admin_List_Table
{


    /**
     * @return Messages_Admin_Page
     */
    public function get_admin_page()
    {
        return $this->_admin_page;
    }


    /**
     * Setup data object
     */
    protected function _setup_data()
    {
        $this->_data           = $this->get_admin_page()->get_message_templates(
            $this->_per_page,
            $this->_view,
            false
        );
        $this->_all_data_count = $this->get_admin_page()->get_message_templates(
            $this->_per_page,
            $this->_view,
            true,
            true
        );
    }


    /**
     * Set internal properties
     */
    protected function _set_properties()
    {
        $this->_wp_list_args = array(
            'singular' => esc_html__('Message Template Group', 'event_espresso'),
            'plural'   => esc_html__('Message Template', 'event_espresso'),
            'ajax'     => true, //for now,
            'screen'   => $this->get_admin_page()->get_current_screen()->id,
        );
        $this->_columns      = array(
            //'cb' => '<input type="checkbox" />', //no deleting default (global) templates!
            'message_type' => esc_html__('Message Type', 'event_espresso'),
            'messenger'    => esc_html__('Messenger', 'event_espresso'),
            'description'  => esc_html__('Description', 'event_espresso'),
        );

        $this->_sortable_columns = array(
            'messenger' => array('MTP_messenger' => true),
        );

        $this->_hidden_columns = array();
    }


    /**
     * Overriding the single_row method from parent to verify whether the $item has an accessible
     * message_type or messenger object before generating the row.
     *
     * @param EE_Message_Template_Group $item
     * @return string
     */
    public function single_row($item)
    {
        $message_type = $item->message_type_obj();
        $messenger    = $item->messenger_obj();

        if (! $message_type instanceof EE_message_type || ! $messenger instanceof EE_messenger) {
            echo '';
            return;
        }

        parent::single_row($item);
    }


    /**
     * @return array
     */
    protected function _get_table_filters()
    {
        $filters = array();

        //get select inputs
        $select_inputs = array(
            $this->_get_messengers_dropdown_filter(),
            $this->_get_message_types_dropdown_filter(),
        );

        //set filters to select inputs if they aren't empty
        foreach ($select_inputs as $select_input) {
            if ($select_input) {
                $filters[] = $select_input;
            }
        }
        return $filters;
    }

    /**
     * we're just removing the search box for message templates, not needed.
     *
     * @return string (empty);
     */
    function search_box($text, $input_id)
    {
        return '';
    }


    /**
     * Add counts to the _views property
     */
    protected function _add_view_counts()
    {
        foreach ($this->_views as $view => $args) {
            $this->_views[$view]['count'] = $this->get_admin_page()->get_message_templates(
                $this->_per_page,
                $view,
                true,
                true
            );
        }
    }


    /**
     * @param EE_Message_Template_Group $item
     * @return string
     */
    public function column_cb($item)
    {
        return '';
    }


    /**
     * @param EE_Message_Template_Group $item
     * @return string
     */
    function column_description($item)
    {
        return '<p>' . $item->message_type_obj()->description . '</p>';
    }


    /**
     * @param EE_Message_Template_Group $item
     * @return string
     */
    function column_messenger($item)
    {
        //Build row actions
        $actions = array();

        // edit link but only if item isn't trashed.
        if (! $item->get('MTP_deleted')
            && EE_Registry::instance()->CAP->current_user_can(
                'ee_edit_message',
                'espresso_messages_edit_message_template',
                $item->ID()
            )) {
            $edit_lnk_url    = EE_Admin_Page::add_query_args_and_nonce(
                array(
                    'action' => 'edit_message_template',
                    'id'     => $item->GRP_ID(),
                ),
                EE_MSG_ADMIN_URL
            );
            $actions['edit'] = '<a href="' . $edit_lnk_url . '"'
                               . ' class="' . $item->message_type() . '-edit-link"'
                               . ' title="' . esc_attr__('Edit Template Group', 'event_espresso') . '">'
                               . esc_html__('Edit', 'event_espresso')
                               . '</a>';
        }

        $name_link = ! $item->get('MTP_deleted')
                     && EE_Registry::instance()->CAP->current_user_can(
                         'ee_edit_message',
                         'espresso_messages_edit_message_template',
                         $item->ID()
                     )
            ? '<a href="' . $edit_lnk_url . '"'
              . ' title="' . esc_attr__('Edit Template Group', 'event_espresso') . '">'
              . ucwords($item->messenger_obj()->label['singular'])
              . '</a>'
            : ucwords($item->messenger_obj()->label['singular']);

        //we want to display the contexts in here so we need to set them up
        $c_label           = $item->context_label();
        $c_configs         = $item->contexts_config();
        $ctxt              = array();
        $context_templates = $item->context_templates();
        foreach ($context_templates as $context => $template_fields) {
            $mtp_to        = ! empty($context_templates[$context]['to'])
                             && $context_templates[$context]['to'] instanceof EE_Message_Template
                ? $context_templates[$context]['to']->get('MTP_content')
                : null;
            $inactive      = empty($mtp_to)
                             && ! empty($context_templates[$context]['to'])
                ? ' class="mtp-inactive"'
                : '';
            $context_title = ucwords($c_configs[$context]['label']);
            $edit_link     = EE_Admin_Page::add_query_args_and_nonce(array('action'  => 'edit_message_template',
                                                                           'id'      => $item->GRP_ID(),
                                                                           'context' => $context,
            ), EE_MSG_ADMIN_URL);
            $ctxt[]        = EE_Registry::instance()->CAP->current_user_can(
                'ee_edit_message',
                'espresso_messages_edit_message_template',
                $item->ID()
            )
                ? '<a' . $inactive
                  . ' href="' . $edit_link . '"'
                  . ' class="' . $item->message_type() . '-' . $context . '-edit-link"'
                  . ' title="' . esc_attr__('Edit Context', 'event_espresso') . '">'
                  . $context_title
                  . '</a>'
                : $context_title;
        }

        $ctx_content = ! $item->get('MTP_deleted')
                       && EE_Registry::instance()->CAP->current_user_can(
                           'ee_edit_message',
                           'espresso_messages_edit_message_template',
                           $item->ID()
                       )
            ? sprintf(
                '<strong>%s:</strong> ',
                ucwords($c_label['plural'])
            )
              . implode(' | ', $ctxt)
            : '';


        //Return the name contents
        return sprintf(
            '%1$s <span style="color:silver">(id:%2$s)</span><br />%3$s%4$s',
            /* $1%s */
            $name_link,
            /* $2%s */
            $item->GRP_ID(),
            /* %4$s */
            $ctx_content,
            /* $3%s */
            $this->row_actions($actions)
        );
    }

    /**
     * column_message_type
     *
     * @param  EE_Message_Template_Group  $item message info for the row
     * @return string       message_type name
     */
    function column_message_type($item)
    {
        return ucwords($item->message_type_obj()->label['singular']);
    }



    /**
     * Generate dropdown filter select input for messengers
     *
     * @return string
     */
    protected function _get_messengers_dropdown_filter()
    {
        $messenger_options                                   = array();
        $active_message_template_groups_grouped_by_messenger = EEM_Message_Template_Group::instance()->get_all(
            array(
                array(
                    'MTP_is_active' => true,
                    'MTP_is_global' => true,
                ),
                'group_by' => 'MTP_messenger',
            )
        );

        foreach ($active_message_template_groups_grouped_by_messenger as $active_message_template_group) {
            if ($active_message_template_group instanceof EE_Message_Template_Group) {
                $messenger                                                      = $active_message_template_group->messenger_obj();
                $messenger_label                                                = $messenger instanceof EE_messenger
                    ? $messenger->label['singular']
                    : $active_message_template_group->messenger();
                $messenger_options[$active_message_template_group->messenger()] = ucwords($messenger_label);
            }
        }
        return $this->get_admin_page()->get_messengers_select_input($messenger_options);
    }


    /**
     * Generate dropdown filter select input for message types
     *
     * @return string
     */
    protected function _get_message_types_dropdown_filter()
    {
        $message_type_options                                   = array();
        $active_message_template_groups_grouped_by_message_type = EEM_Message_Template_Group::instance()->get_all(
            array(
                array(
                    'MTP_is_active' => true,
                    'MTP_is_global' => true,
                ),
                'group_by' => 'MTP_message_type',
            )
        );

        foreach ($active_message_template_groups_grouped_by_message_type as $active_message_template_group) {
            if ($active_message_template_group instanceof EE_Message_Template_Group) {
                $message_type                                                         = $active_message_template_group->message_type_obj();
                $message_type_label                                                   = $message_type instanceof EE_message_type
                    ? $message_type->label['singular']
                    : $active_message_template_group->message_type();
                $message_type_options[$active_message_template_group->message_type()] = ucwords($message_type_label);
            }
        }
        return $this->get_admin_page()->get_message_types_select_input($message_type_options);
    }

}
