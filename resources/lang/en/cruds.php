<?php

return [
    'building'       => [
        'title'          => 'Здания',
        'title_singular' => 'Здание',
        'fields'         => [
            'id'                => 'ID',
            'address'           => 'Адрес',
            'name'              => 'Название',
            'region_id'         => 'Район',
            'metro_id'  => 'Метро',
            'type'              => 'Тип дома',
            'type_options' => [
                'brick' => 'Кирпичный',
                'monolith' => 'Монолитный',
                'panel' => 'Панельный',
                'block' => 'Блочный',
                'wood' => 'Деревянный',
                'monolithBrick' => 'Монолитно-кирпичный',
                'stalin' => 'Сталинский'
            ],
            'class'             => 'Класс',
            'lon'               => 'Долгота',
            'lat'               => 'Широта',
            'region'            => 'Район',
            'metro_distance'    => 'Расстояние от метро (м.)',
            'metro_time'        => 'Время до метро (мин.)',
            'metro_time_type'   => 'Способ передвижения',
            'metro_time_type_options' => [
                'walk' => 'Пешком',
                'transport' => 'На транспорте',
            ],
            'mkad_distance'     => 'Расстояние от МКАД',
            'floors'            => 'Этажность',
            'description'       => 'Описание местроположения',
            'is_parking'        => 'Парковка',
            'parking_type'      => 'Парковка',
            'parking_type_options' => [
                'ground' => 'Наземная',
                'multilevel' => 'Многоуровневая',
                'underground' => 'Подземная',
                'roof' => 'На крыше',
            ],
            'year_construction' => 'Год постройки',
            'apartments_for_rent' => 'Квартир в аренду',
        ],
    ],
    'block'   => [
        'title'          => 'Объекты',
        'title_singular' => 'Объект',
        'fields'         => [
            'id'                     => 'ID',

            'building_id' => 'Здание',
            'contact' => 'Собственник',

            'floor'                  => 'Этаж',
            'flat_number'                  => 'Номер квартиры',
            'ceiling'                => 'Высота потолков',
            'area' => 'Общая площадь',
            'living_area' => 'Жилая площадь',
            'kitchen_area' => 'Площадь кухни',
            'metro' => 'Метро',
            'metro_time' => 'Время до метро(мин)',

            'type' => 'Тип жилья',
            'type_options' => [
                'flat' => 'Квартира',
                'apartment' => 'Апартаменты',
            ],

            'rooms' => 'Количество комнат',
            'rooms_options' => [
                'room_1' => '1 комната',
                'room_2' => '2 комнаты',
                'room_3' => '3 комнаты',
                'room_4' => '4 комнаты',
                'room_5' => '5 комнат',
                'room_6_plus' => '6+ комнат',
                'studio' => 'Студия',
                'free_planning' => 'Свободная планировка',
            ],

            'rooms_type' => 'Тип комнат',
            'rooms_type_options' => [
                'separate' => 'Раздельные',
                'combined' => 'Смежные',
                'both' => 'Смежно-раздельные',
            ],

            'balcony' => 'Балкон/лоджия',
            'balcony_options' => [
                'balcony_0' => 'Нет',
                'balcony_1' => '1 балкон',
                'balcony_2' => '2 балкона',
                'balcony_3' => '3 балкона',
                'balcony_4' => '4 балкона',
                'balcony_5' => '5 балконов',
            ],

            'windowsInOut' => 'Окна выходят',
            'windowsInOut_options' => [
                'yard' => 'Во двор',
                'street' => 'На улицу',
                'yardAndStreet' => 'Во двор и на улицу',
            ],

            'separate_wc_count' => 'Раздельные сан. узлы',
            'wc_count_options' => [
                'wc_count_0' => 'Нет',
                'wc_count_1' => '1 сан. узел',
                'wc_count_2' => '2 сан. узла',
                'wc_count_3' => '3 сан. узла',
                'wc_count_4' => '4 сан. узла',
                'wc_count_5' => '5 сан. узлов',
            ],

            'combined_wc_count' => 'Совмещенные сан. узлы',

            'renovation' => 'Ремонт',
            'renovation_options' => [
                'cosmetic' => 'Косметический ремонт',
                'euro' => 'Евро ремонт',
                'design' => 'Дизайнерский ремонт',
                'no' => 'Без ремонта',
            ],

            'filling' => 'В квартире',
            'filling_options' => [
                'room_furniture' => 'Мебель в комнатах',
                'kitchen_furniture' => 'Мебель на кухне',
                'refrigerator' => 'Холодильник',
                'dishwasher' => 'Посудомоечная машина',
                'washing_machine' => 'Стиральная машина',
                'internet' => 'Интернет',
                'phone' => 'Телефон',
                'tv' => 'Телевизор',
                'air_conditioning' => 'Кондиционер',
            ],

            'shower_bath' => 'В сан. узлах',
            'shower_bath_options' => [
                'bathroom' => 'Ванная',
                'shower_cabin' => 'Душевая кабина',
            ],

            'living_conds' => 'Условия проживания',
            'living_conds_options' => [
                'only_one' => 'Только для одного',
                'only_family' => 'Только для семей',
                'only_slavs' => 'Только для славян',
                'no_animals' => 'Без животных',
                'no_children' => 'Без детей',
            ],

            'tenant_count_limit' => 'Макс. кол-во проживающих',
            'cadastral_number' => 'Кадастровый номер',
            'video_url' => 'Ссылка на видео',
            'comment' => 'Комментрий',

            'cost'                   => 'Стоимость',
            'deposit' => 'Залог',

            'commission_type' => 'Комиссия',
            'commission_type_options' => [
                'client' => 'С клиента',
                'owner' => 'С собственника',
            ],

            'user'                   => 'Брокер',
            'planned_contact'        => 'Запланированная дата следующего контакта',
            'contract_status'        => 'Договор',
            'commission'             => 'Комиссия %',
            'description'            => 'Описание',

            'cost_m'                 => 'Стоимость кв.м',
            'photos'                 => 'Фото',
            'docs'                   => 'Документы',
            'created_at'             => 'Создано',
            'updated_at'             => 'Обновлено',
            'deleted_at'             => 'Удалено',

        ],
        'listing' => 'Листинг',
    ],
    'buildingFilter' => [
        'fields' => [
            'q' => 'Поиск ...',
        ],
    ],
    'region' => [
        'fields' => [
            'name' => 'Район',
        ],
    ],
    'metro' => [
        'fields' => [
            'name' => 'Метро',
        ],
    ],
    'contact' => [
        'fields' => [
            'name' => 'Собственник',
        ],
    ],
    'notifications' => [
        'title' => 'Уведомления',
        'fields' => [
            'text' => 'Сообщение',
            'notification_date' => 'Дата уведомления',
        ],
    ],
];
