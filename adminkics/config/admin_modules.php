<?php

return [
    'groups' => [
        'title' => 'Groups',
        'table' => 'kic_group',
        'primary_key' => 'group_id',
        'display' => ['group_id', 'group_name', 'code', 'group_seqno', 'is_center'],
        'label' => 'group_name',
    ],

    'projects' => [
        'title' => 'Projects',
        'table' => 'kic_group_projectlist',
        'primary_key' => 'projectlist_id',
        'display' => ['projectlist_id', 'projectlist_Name', 'group_id', 'project_category', 'is_completed', 'inactive'],
        'label' => 'projectlist_Name',
        'relations' => [
            'group_id' => ['table' => 'kic_group', 'key' => 'group_id', 'label' => 'group_name'],
        ],
    ],

    'staff' => [
        'title' => 'Staff',
        'table' => 'people',
        'primary_key' => 'people_id',
        'display' => ['people_id', 'fname', 'lname', 'email', 'designation_id', 'group_id', 'post_id', 'status'],
        'label' => ['fname', 'lname'],
        'relations' => [
            'designation_id' => ['table' => 'designation', 'key' => 'designation_id', 'label' => 'designation_name'],
            'group_id' => ['table' => 'kic_group', 'key' => 'group_id', 'label' => 'group_name'],
            'post_id' => ['table' => 'kic_post', 'key' => 'post_id', 'label' => 'post_name'],
        ],
    ],

    'staff_labs' => [
        'title' => 'Staff Lab Assignments',
        'table' => 'staff_lab',
        'primary_key' => 'id',
        'display' => ['id', 'staff_id', 'group_id', 'role'],
        'relations' => [
            'staff_id' => ['table' => 'people', 'key' => 'people_id', 'label' => ['fname', 'lname']],
            'group_id' => ['table' => 'kic_group', 'key' => 'group_id', 'label' => 'group_name'],
        ],
    ],

    'designations' => [
        'title' => 'Designations',
        'table' => 'designation',
        'primary_key' => 'designation_id',
        'display' => ['designation_id', 'designation_name', 'designation_seqno', 'designation_image'],
        'label' => 'designation_name',
    ],

    'posts' => [
        'title' => 'Staff Posts',
        'table' => 'kic_post',
        'primary_key' => 'post_id',
        'display' => ['post_id', 'post_name', 'des_id', 'post_seqno'],
        'label' => 'post_name',
        'relations' => [
            'des_id' => ['table' => 'designation', 'key' => 'designation_id', 'label' => 'designation_name'],
        ],
    ],

    'publications' => [
        'title' => 'Publications',
        'table' => 'kic_publications',
        'primary_key' => 'publication_id',
        'display' => ['publication_id', 'publication_title', 'publication_year', 'group_id', 'people_id', 'category'],
        'label' => 'publication_title',
        'relations' => [
            'group_id' => ['table' => 'kic_group', 'key' => 'group_id', 'label' => 'group_name'],
            'people_id' => ['table' => 'people', 'key' => 'people_id', 'label' => ['fname', 'lname']],
        ],
    ],

    'careers' => [
        'title' => 'Careers',
        'table' => 'careers',
        'primary_key' => 'id',
        'display' => ['id', 'job_title', 'group_id', 'location', 'job_close_date'],
        'label' => 'job_title',
        'relations' => [
            'group_id' => ['table' => 'kic_group', 'key' => 'group_id', 'label' => 'group_name'],
        ],
    ],

    'news' => [
        'title' => 'News',
        'table' => 'news',
        'primary_key' => 'id',
        'display' => ['id', 'title', 'category_id', 'created_at'],
        'label' => 'title',
        'relations' => [
            'category_id' => ['table' => 'news_categories', 'key' => 'id', 'label' => 'name'],
        ],
    ],

    'news_categories' => [
        'title' => 'News Categories',
        'table' => 'news_categories',
        'primary_key' => 'id',
        'display' => ['id', 'name'],
        'label' => 'name',
    ],

    'old_news' => [
        'title' => 'Old Star News',
        'table' => 'star_news',
        'primary_key' => 'news_id',
        'display' => ['news_id', 'news_des', 'news_stamp', 'group_id', 'active'],
        'label' => 'news_des',
        'relations' => [
            'group_id' => ['table' => 'kic_group', 'key' => 'group_id', 'label' => 'group_name'],
        ],
    ],

    'menu_sections' => [
        'title' => 'Menu Sections',
        'table' => 'menu_sections',
        'primary_key' => 'id',
        'display' => ['id', 'title', 'order_index'],
        'label' => 'title',
    ],

    'menu_links' => [
        'title' => 'Menu Links',
        'table' => 'menu_links',
        'primary_key' => 'id',
        'display' => ['id', 'label', 'menu_section_id', 'url', 'order_index'],
        'label' => 'label',
        'relations' => [
            'menu_section_id' => ['table' => 'menu_sections', 'key' => 'id', 'label' => 'title'],
        ],
    ],

    'partners' => [
        'title' => 'Partners',
        'table' => 'kics_partners',
        'primary_key' => 'id',
        'display' => ['id', 'title', 'link', 'logo'],
        'label' => 'title',
    ],

    'about' => [
        'title' => 'About',
        'table' => 'about',
        'primary_key' => 'id',
        'display' => ['id', 'section_1', 'updated_at'],
    ],

    'vision' => [
        'title' => 'Vision',
        'table' => 'vision',
        'primary_key' => 'id',
        'display' => ['id', 'section_vision', 'updated_at'],
    ],

    'director_message' => [
        'title' => 'Director Message',
        'table' => 'director_message',
        'primary_key' => 'id',
        'display' => ['id', 'section_message', 'updated_at'],
    ],

    'admin_users' => [
        'title' => 'Admin Users',
        'table' => 'users',
        'primary_key' => 'id',
        'display' => ['id', 'name', 'email', 'role'],
        'label' => 'name',
    ],

    'old_users' => [
        'title' => 'Old Frontend Users',
        'table' => 'user',
        'primary_key' => 'id',
        'display' => ['id', 'username', 'status', 'superuser', 'lastvisit'],
        'label' => 'username',
    ],

    'old_admins' => [
        'title' => 'Old Admins',
        'table' => 'admin',
        'primary_key' => 'admin_id',
        'display' => ['admin_id', 'admin_fname', 'admin_lname', 'admin_email', 'people_id', 'master_admin'],
        'relations' => [
            'people_id' => ['table' => 'people', 'key' => 'people_id', 'label' => ['fname', 'lname']],
        ],
    ],
];
