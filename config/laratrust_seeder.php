<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => false,

    'roles_structure' => [
        'admin' =>[
            'name' => 'general_affairs',
            'permission' => [
                'header' => [
                    'vehicle', 'degi_tacho', 'etc_device', 'master_management', 'user_management'
                ],
                'vehicle' => ['list', 'download_list', 'new_registration', 'registration_image_upload',
                              'new_registration_device_master_cooperation', 'new_registration_operation_status_automatic_reflection', 'detailed_display', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display',
                              'detailed_display_image_download_all', 'detailed_display_image_download_other_than_contract', 'detailed_view_link', 'download', 'edit', 'edit_history_delete', 'edit_registration_image_upload', 'edit_vehicle_device_master_cooperation',
                                'edit_operation_status_automatic_reflection', 'edit_display_update_schedule_information_display', 'edit_display_history_display', 'edit_vehicle_analysis'],
                'degi_tacho' => ['list', 'registration', 'registration_vehicle_master_cooperation', 'detail_view', 'detailed_display_update_schedule_information_display',
                    'detailed_display_history_display', 'detailed_display_link', 'edit', 'edit_delete_history', 'edit_master_cooperation', 'edit_display_update_schedule_information_display',
                    'edit_display_history_display'],
                'etc_device' => [
                                    'device_list', 'registration', 'registration_vehicle_master_cooperation','detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_view_link', 'edit','edit_delete_history', 'edit_registration_etc_master_cooperation', 'edit_display_update_schedule_information_display', 'edit_display_history_display'
                                ],
                'master_manager' => [
                                        'list', 'detailed_display', 'add', 'delete', 'edit'
                                    ],
                'user' => [
                                'list', 'new_registration', 'display', 'delete', 'edit'
                          ]
            ]
        ],
        'company_manager' =>[
            'name' => 'vehicle_analysis',
            'permission' => [
                'header' => [
                    'vehicle', 'degi_tacho', 'etc_device'
                ],
                'vehicle' => ['list', 'detailed_display', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_image_download_other_than_contract',
                                'detailed_view_link', 'download', 'edit_display_update_schedule_information_display', 'edit_display_history_display', 'edit_vehicle_analysis'],
                'degi_tacho' => [
                                    'list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_link'
                                ],
                'etc_device' => [
                                    'device_list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_view_link',
                                ],
                'master_manager' => [

                ],
                'user' => [

                          ]
            ]
        ],
        'hr_manager' =>[
            'name' => 'information_systems',
            'permission' => [
                'header' => [
                    'vehicle', 'degi_tacho', 'etc_device'
                ],
                'vehicle' => ['list', 'detailed_display', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_image_download_other_than_contract',
                    'detailed_view_link', 'download'],
                'degi_tacho' => ['list', 'registration', 'registration_vehicle_master_cooperation', 'detail_view', 'detailed_display_update_schedule_information_display',
                    'detailed_display_history_display', 'detailed_display_link', 'edit', 'edit_delete_history', 'edit_master_cooperation', 'edit_display_update_schedule_information_display',
                    'edit_display_history_display'],
                'etc_device' => [
                                    'device_list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_view_link',
                                ],
                'master_manager' => [

                ],
                'user' => [

                          ]
            ]
        ],
        'company' =>[
            'name' => 'center_manager',
            'permission' => [
                'header' => [
                    'vehicle', 'degi_tacho', 'etc_device'
                ],
                'vehicle' => ['list', 'detailed_display', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_image_download_all',
                    'detailed_display_image_download_other_than_contract', 'detailed_view_link', 'download'],
                'degi_tacho' => [
                                    'list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_link'
                                ],
                'etc_device' => [
                                    'device_list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_view_link',
                                ],
                'master_manager' => [

                ],
                'user' => [

                          ]
            ]
        ],
        'hr' =>[
            'name' => 'viewer',
            'permission' => [
                'header' => [
                    'vehicle', 'degi_tacho', 'etc_device'
                ],
                'vehicle' => ['list', 'detailed_display', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_image_download_other_than_contract',
                    'detailed_view_link', 'download'],
                'degi_tacho' => [
                                    'list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_display_link'
                                ],
                'etc_device' => [
                                    'device_list', 'detail_view', 'detailed_display_update_schedule_information_display', 'detailed_display_history_display', 'detailed_view_link',
                                ],
                'master_manager' => [

                ],
                'user' => [

                          ]
            ]
        ]
    ],
    'permissions_map' => [
        'header' => [
            'name' =>  'header',
            'description' => 'is permission: Vehicle, Degi-tacho, ETC device, Master management, User management',
            'items' => [
                'vehicle' => 'asset page Vehicle',
                'degi_tacho' => 'asset page Degi-tacho',
                'etc_device' => 'asset page ETC device',
                'master_management' => 'asset page master_management',
                'user_management' => 'asset page User management',
            ]
        ],
        'vehicle' => [
            'name' => 'vehicle',
            'description' => 'is permission: Vehicle_List, download, registration, registration_Image_upload, registration_Device master cooperation, new registration_operation status automatic reflection, Detailed display, Detailed display_Update schedule information display...',
            'items' => [
                // Vehicle_List
                'registration_image_upload' => 'New registration_Image upload',
                'list' => 'Show list vehicle',
                'download_list' => 'Vehicle_Batch download',
                // Vehicle_New registration
                "new_registration"=> 'New registration',
                'new_registration_device_master_cooperation' => 'New registration_Device master cooperation',
                'new_registration_operation_status_automatic_reflection'=>'new registration_operation status automatic reflection',
                // Detailed display
                'detailed_display' => 'Detailed display',
                'detailed_display_update_schedule_information_display' => 'Detailed display_Update schedule information display',
                'detailed_display_history_display' => 'Detailed display_History display',
                'detailed_display_image_download_all' => 'Detailed display_Image download_All',
                'detailed_display_image_download_other_than_contract' => 'Detailed display_Image download_Other than contract',
                'detailed_view_link' => 'Detailed view_Link',
                'download' => 'Download',
                // Edit
                'edit' => 'Edit',
                'edit_history_delete' => 'Edit_History Delete',
                'edit_registration_image_upload' => 'edit_vehicle_Image upload',
                'edit_vehicle_device_master_cooperation' => 'edit_Vehicle device master cooperation',
                'edit_operation_status_automatic_reflection' => 'edit_operation status automatic reflection',
                'edit_display_update_schedule_information_display' => 'edit display_Update schedule information display',
                'edit_display_history_display' => 'edit display_History display',
                'edit_vehicle_analysis' => 'Edit_Vehicle analysis',
            ]
        ],
        'degi_tacho' => [
            'name' => 'Degi-tacho',
            'description' => 'is permission: list, edit, create, delete',
            'items' => [
                //List
                'list' => 'Degi-tacho_List',
                //Registration
                'registration' => 'Registration',
                'registration_vehicle_master_cooperation' => 'Registration_Vehicle master cooperation',
                //detail
                'detail_view' => 'Detail View',
                'detailed_display_update_schedule_information_display' => 'Detailed display_Update schedule information display',
                'detailed_display_history_display' => 'Detailed display_History display',
                'detailed_display_link' => 'Detailed display_Link',
                //edit
                'edit' => 'Edit',
                'edit_delete_history' => 'Edit_Delete history',
                'edit_master_cooperation' => 'Edit degi_tacho master cooperation',
                'edit_display_update_schedule_information_display' => 'Edit display_Update schedule information display',
                'edit_display_history_display' => 'Edit display_History display',
            ]
        ],
        'etc_device' => [
            'name' => 'etc_device',
            'description' => 'is permission: list, edit, create, delete',
            'items' => [
                //list
                'device_list' => 'device_List',
                //Registration
                'registration' => 'Registration',
                'registration_vehicle_master_cooperation' => 'Registration_vehicle master cooperation',
                //view
                'detail_view' => 'Detail View',
                'detailed_display_update_schedule_information_display' => 'Detailed display_Update schedule information display',
                'detailed_display_history_display' => 'Detailed display_History display',
                'detailed_view_link' => 'Detailed view_Link',
                //edit
                'edit' => 'Edit',
                'edit_delete_history' => 'Edit_Delete history',
                'edit_registration_etc_master_cooperation' => 'Edit registration_etc master cooperation',
                'edit_display_update_schedule_information_display' => 'Edit display_Update schedule information display',
                'edit_display_history_display' => 'Edit display_History display',
            ]
        ],
        'master_manager' => [
            'name' => 'master_manager',
            'description' => 'is permission: list, edit, create, delete',
            'items' => [
                //list
                'list' => 'Master management_List',
                //detail
                'detailed_display' => 'Master management_Detailed display',
                //add
                'add' => 'Master management_Add',
                //delete
                'delete' => 'Master management_Delete',
                //edit
                'edit' => 'Master management_Edit',
            ]
        ],
        'user' => [
            'name' => 'user_mamager',
            'description' => 'is permission: list, edit, create, delete',
            'items' => [
                //list
                'list' => 'User Management List',
                'selective_delete' => 'User Management Selective Delete',
                //add
                'new_registration' => 'User Management New Registration',
                //detail
                'display' => 'User Management Display',
                'delete' => 'User Management Delete',
                //edit
                'edit' => 'User Management Edit',
            ]
        ],
    ]
];
