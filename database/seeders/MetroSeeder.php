<?php

namespace Database\Seeders;

use App\Models\Metro;
use Illuminate\Database\Seeder;

class MetroSeeder extends Seeder
{
    const KEYS = ['name', 'line', 'region_id', 'cian_id'];

    const VALS = [
        ['Третьяковская', '10', '1', '129'],
        ['Медведково', '8', '2', '69'],
        ['Первомайская', '5', '3', '89'],
        ['Калужская', '8', '4', '42'],
        ['Каховская', '18', '5', '44'],
        ['Бульвар адмирала Ушакова', '14', '6', '23'],
        ['Павелецкая', '4', '1', '85'],
        ['Волжская', '12', '7', '32'],
        ['Шаболовская', '8', '8', '151'],
        ['Плющиха', '2', '9', null],
        ['Тимирязевская', '11', '10', '128'],
        ['ВДНХ', '8', '11', '27'],
        ['Тверская', '4', '12', '124'],
        ['Фонвизинская', '12', '13', '286'],
        ['Воронцовская', '18', '4', '390'],
        ['Дмитровская', '11', '13', '37'],
        ['Новые Черёмушки', '8', '14', '79'],
        ['Улица Милашенкова', '15', '13', '280'],
        ['Пушкинская', '9', '12', '105'],
        ['Маяковская', '4', '12', '68'],
        ['Нижегородская улица', '17', '15', null],
        ['Ботанический сад', '8', '16', '21'],
        ['Нижегородская улица', '18', '15', null],
        ['Белорусская', '4', '12', '15'],
        ['Варшавская', '13', '17', '26'],
        ['Красногвардейская', '4', '18', '52'],
        ['Комсомольская', '3', '19', '50'],
        ['Нагатинская', '11', '20', '73'],
        ['Алтуфьево', '11', '21', '6'],
        ['Новопеределкино', '2', '22', '366'],
        ['Автозаводская', '4', '23', '2'],
        ['Дорогомиловская', '2', '24', null],
        ['Текстильщики', '9', '25', '126'],
        ['Некрасовская', '12', '26', null],
        ['Марьино', '12', '27', '67'],
        ['Павелецкая', '7', '1', '85'],
        ['Преображенская площадь', '3', '28', '100'],
        ['Новослободская', '7', '12', '78'],
        ['Фили', '6', '29', '142'],
        ['Кунцевская', '18', '30', '60'],
        ['Домодедовская', '4', '31', '39'],
        ['Мневники', '18', '32', '396'],
        ['Охотный ряд', '3', '12', '84'],
        ['Новокосино', '2', '33', '243'],
        ['Курская', '7', '34', '61'],
        ['Лухмановская', '17', '35', '372'],
        ['Достоевская', '12', '12', '237'],
        ['Улица Новаторов', '18', '36', '391'],
        ['Международная', '6', '37', '70'],
        ['Борисово', '12', '18', '240'],
        ['Выставочный центр', '15', '11', '277'],
        ['Беговая', '9', '37', '14'],
        ['Терехово', '18', '32', null],
        ['Третьяковская', '2', '1', '129'],
        ['Савёловская', '11', '13', '110'],
        ['Волоколамская', '5', '38', '234'],
        ['Парк Победы', '2', '24', '87'],
        ['Чертановская', '11', '39', '147'],
        ['Парк Победы', '5', '24', '87'],
        ['Филёвский парк', '6', '40', '141'],
        ['Фрунзенская', '3', '41', '143'],
        ['Кузнецкий мост', '9', '42', '58'],
        ['Лубянка', '3', '12', '64'],
        ['Улица Сергея Эйзенштейна', '15', '11', '276'],
        ['Водный стадион', '4', '43', '29'],
        ['Улица 1905 года', '9', '37', '134'],
        ['Ломоносовский проспект', '2', '44', '338'],
        ['Марксистская', '2', '45', '66'],
        ['Боровское шоссе', '2', '22', '365'],
        ['Партизанская', '5', '3', '88'],
        ['Улица академика Янгеля', '11', '46', '135'],
        ['Краснопресненская', '7', '37', '159'],
        ['Авиамоторная', '17', '47', '1'],
        ['Чкаловская', '12', '34', '150'],
        ['Тульская', '11', '23', '131'],
        ['Лермонтовский проспект', '9', '48', '271'],
        ['Спартак', '9', '49', '275'],
        ['Арбатская', '6', '9', '8'],
        ['Аннино', '11', '46', '7'],
        ['Севастопольская', '11', '5', '112'],
        ['Профсоюзная', '8', '50', '104'],
        ['Арбатская', '5', '9', '8'],
        ['Улица Горчакова', '14', '6', '136'],
        ['Мякинино', '5', '51', '233'],
        ['Октябрьская', '8', '52', '80'],
        ['Пионерская', '6', '40', '93'],
        ['Площадь Ильича', '2', '45', '95'],
        ['Рассказовка', '2', '53', '367'],
        ['Нагатинский затон', '18', '54', '388'],
        ['Измайловская', '5', '3', '41'],
        ['Косино', '17', '48', '370'],
        ['Битцевский парк', '14', '55', '274'],
        ['Сокол', '4', '56', '116'],
        ['Ржевская', '18', '42', '384'],
        ['Сокольники', '18', '57', '117'],
        ['Смоленская', '6', '9', '115'],
        ['Каховская', '13', '5', '44'],
        ['Парк культуры', '3', '41', '86'],
        ['Мичуринский проспект', '2', '44', '361'],
        ['Котельники', '9', '48', '282'],
        ['Библиотека имени Ленина', '3', '9', null],
        ['Некрасовка', '17', '58', '373'],
        ['Волгоградский проспект', '9', '26', '31'],
        ['Трубная', '12', '42', '130'],
        ['Динамо', '4', '59', '36'],
        ['Алексеевская', '8', '60', '5'],
        ['Улица академика Королёва', '15', '11', '278'],
        ['Планерная', '9', '61', '94'],
        ['Спортивная', '3', '41', '118'],
        ['Аминьевское шоссе', '18', '30', '392'],
        ['Суворовская', '7', '42', null],
        ['Таганская', '9', '45', '123'],
        ['Киевская', '5', '24', '46'],
        ['Театральная', '4', '12', '125'],
        ['Сокольники', '3', '57', '117'],
        ['Деловой центр', '2', '37', '272'],
        ['Бульвар Дмитрия Донского', '11', '62', '24'],
        ['Шипиловская', '12', '18', '238'],
        ['Белорусская', '7', '12', '15'],
        ['Полянка', '11', '52', '98'],
        ['Крылатское', '5', '63', '57'],
        ['Сходненская', '9', '64', '122'],
        ['Площадь Революции', '5', '12', '96'],
        ['Юго-Западная', '3', '65', '156'],
        ['Шоссе Энтузиастов', '2', '66', '152'],
        ['Китай-город', '8', '12', '47'],
        ['Щукинская', '9', '67', '154'],
        ['Лесопарковая', '14', '62', '273'],
        ['Новогиреево', '2', '68', '76'],
        ['Курская', '5', '34', '61'],
        ['Лефортово', '18', '47', '381'],
        ['Очаково', '2', '69', null],
        ['Авиамоторная', '18', '47', '1'],
        ['Минская', '2', '24', '337'],
        ['Студенческая', '6', '24', '120'],
        ['Пражская', '11', '70', '99'],
        ['Парк культуры', '7', '41', '86'],
        ['Аэропорт', '4', '59', '9'],
        ['Проспект Мира', '7', '42', '103'],
        ['Третьяковская', '8', '1', '129'],
        ['Марьина роща', '12', '71', '236'],
        ['Китай-город', '9', '12', '47'],
        ['Авиамоторная', '2', '47', '1'],
        ['Нахимовский проспект', '11', '5', '75'],
        ['Алма-Атинская', '4', '72', '245'],
        ['Цветной бульвар', '11', '12', '145'],
        ['Окружная', '12', '10', '296'],
        ['Добрынинская', '7', '1', '38'],
        ['Технопарк', '4', '73', '283'],
        ['Серпуховская', '11', '1', '114'],
        ['Каширская', '4', '73', '45'],
        ['Жулебино', '9', '48', '270'],
        ['Селигерская', '12', '74', '354'],
        ['Юго-Восточная', '17', '48', '374'],
        ['Солнцево', '2', '75', '364'],
        ['Пятницкое шоссе', '5', '38', '244'],
        ['Улица Скобелевская', '14', '6', '138'],
        ['Воробьёвы горы', '3', '44', '33'],
        ['Мичуринский проспект', '18', '44', '361'],
        ['Верхние Лихоборы', '12', '74', '353'],
        ['Строгино', '5', '51', '228'],
        ['Отрадное', '11', '76', '83'],
        ['Кузьминки', '9', '7', '59'],
        ['Кунцевская', '6', '30', '60'],
        ['Окская улица', '17', '77', null],
        ['Митино', '5', '38', '235'],
        ['Академическая', '8', '50', '3'],
        ['Пролетарская', '9', '45', '101'],
        ['Нижняя Масловка', '18', '78', null],
        ['Деловой центр', '18', '37', '272'],
        ['Тургеневская', '8', '19', '132'],
        ['Беляево', '8', '79', '16'],
        ['Царицыно', '4', '80', '144'],
        ['Волхонка', '2', '41', null],
        ['Киевская', '6', '24', '46'],
        ['Полежаевская', '9', '81', '97'],
        ['Текстильщики', '18', '25', '126'],
        ['Войковская', '4', '82', '30'],
        ['Саларьево', '3', '75', '285'],
        ['Братиславская', '12', '27', '22'],
        ['Южная', '11', '70', '157'],
        ['Киевская', '7', '24', '46'],
        ['Славянский бульвар', '5', '40', '229'],
        ['Свиблово', '8', '16', '111'],
        ['Университет', '3', '44', '140'],
        ['Улица Старокачаловская', '14', '62', '139'],
        ['Электрозаводская', '5', '83', '155'],
        ['Ясенево', '8', '55', '158'],
        ['Румянцево', '3', '84', '284'],
        ['Тропарёво', '3', '65', '281'],
        ['Таганская', '7', '45', '123'],
        ['Каширская', '13', '17', '45'],
        ['Орехово', '4', '85', '82'],
        ['Перово', '2', '68', '90'],
        ['Телецентр', '15', '11', '279'],
        ['Дубровка', '12', '26', '40'],
        ['Каширская', '18', '17', '45'],
        ['Семёновская', '5', '83', '113'],
        ['Стахановская', '17', '77', '376'],
        ['Кунцевская', '5', '30', '60'],
        ['Петровско-Разумовская', '12', '10', '91'],
        ['Тёплый стан', '8', '55', '127'],
        ['Выхино', '9', '48', '34'],
        ['Терешково', '2', '75', null],
        ['Кантемировская', '4', '80', '43'],
        ['Севастопольский проспект', '18', '5', null],
        ['Молодёжная', '5', '30', '72'],
        ['Владыкино', '11', '76', '28'],
        ['Крестьянская застава', '12', '45', '55'],
        ['Хорошёвская', '18', '81', '351'],
        ['Зябликово', '12', '18', '239'],
        ['Петровско-Разумовская', '11', '10', '91'],
        ['Красносельская', '3', '19', '53'],
        ['Улица Народного ополчения', '18', '32', '397'],
        ['Чистые пруды', '3', '34', '149'],
        ['Дмитровское шоссе', '12', '86', null],
        ['Комсомольская', '7', '19', '50'],
        ['Сретенский бульвар', '12', '19', '119'],
        ['Проспект Вернадского', '18', '44', '102'],
        ['Боровицкая', '11', '9', '20'],
        ['Бибирево', '11', '21', '17'],
        ['Речной вокзал', '4', '87', '106'],
        ['Люблино', '12', '88', '65'],
        ['Бутырская', '12', '13', '287'],
        ['Октябрьское поле', '9', '67', '81'],
        ['Новокузнецкая', '4', '1', '77'],
        ['Кутузовская', '6', '24', '62'],
        ['Челобитьево', '8', '89', null],
        ['Тушинская', '9', '49', '133'],
        ['Коломенская', '4', '73', '49'],
        ['Октябрьская', '7', '52', '80'],
        ['Проспект Вернадского', '3', '36', '102'],
        ['Нагорная', '11', '20', '74'],
        ['Выставочная', '6', '37', '35'],
        ['Бунинская аллея', '14', '6', '25'],
        ['Рижская', '8', '42', '107'],
        ['Ходынское поле', '18', '81', null],
        ['Новоясеневская', '8', '55', '19'],
        ['Бауманская', '5', '34', '13'],
        ['Бульвар Рокоссовского', '3', '90', '137'],
        ['Черкизовская', '3', '28', '146'],
        ['Коньково', '8', '91', '51'],
        ['Менделеевская', '11', '12', '71'],
        ['улица Дмитриевского', '17', '35', '371'],
        ['Электрозаводская', '18', '83', '155'],
        ['Раменки', '2', '44', '339'],
        ['Смоленская', '5', '9', '115'],
        ['Баррикадная', '9', '37', '12'],
        ['Багратионовская', '6', '29', '11'],
        ['Бабушкинская', '8', '92', '10'],
        ['Кропоткинская', '3', '41', '56'],
        ['Проспект Мира', '8', '42', '103'],
        ['Сухаревская', '8', '19', '121'],
        ['Тимирязевская', '15', '13', '128'],
        ['Красные ворота', '3', '19', '54'],
        ['Печатники', '12', '93', '92'],
        ['Шереметьевская', '18', '71', '383'],
        ['Рязанский проспект', '9', '77', '109'],
        ['Римская', '12', '45', '108'],
        ['Александровский сад', '6', '12', '4'],
        ['Ленинский проспект', '8', '8', '63'],
        ['Чеховская', '11', '12', '148'],
        ['Печатники', '18', '93', '92'],
        ['Щёлковская', '5', '94', '153'],
        ['Ховрино', '4', '95', '349'],
        ['Шелепиха', '16', '37', '311'],
        ['Площадь Гагарина', '16', '96', '309'],
        ['Лихоборы', '16', '97', '295'],
        ['Автозаводская', '16', '23', '2'],
        ['Шоссе Энтузиастов', '16', '83', '152'],
        ['Хорошёво', '16', '81', '289'],
        ['Соколиная Гора', '16', '83', '301'],
        ['Локомотив', '16', '28', '299'],
        ['Кутузовская', '16', '24', '62'],
        ['Балтийская', '16', '82', '293'],
        ['Владыкино', '16', '98', '28'],
        ['Угрешская', '16', '93', '305'],
        ['Ботанический сад', '16', '16', '21'],
        ['Андроновка', '16', '66', '302'],
        ['Дубровка', '16', '26', '40'],
        ['Измайлово', '16', '83', '300'],
        ['Лужники', '16', '41', '310'],
        ['Нижегородская', '16', '15', '303'],
        ['Новохохловская', '16', '15', '304'],
        ['ЗИЛ', '16', '23', '306'],
        ['Верхние Котлы', '16', '20', '307'],
        ['Белокаменная', '16', '90', '298'],
        ['Ростокино', '16', '99', '297'],
        ['Деловой центр', '16', '37', '272'],
        ['Бульвар Рокоссовского', '16', '90', '137'],
        ['Стрешнево', '16', '56', '292'],
        ['Крымская', '16', '8', '308'],
        ['Окружная', '16', '10', '296'],
        ['Петровский парк', '18', '59', '350'],
        ['ЦСКА', '18', '81', '352'],
        ['Шелепиха', '18', '37', '311'],
        ['Озерная', '2', '69', '362'],
        ['Говорово', '2', '75', '363'],
        ['Беломорская', '4', '87', '369'],
        ['Зорге', '16', '32', '290'],
        ['Панфиловская', '16', '67', '291'],
        ['Коптево', '16', '97', '294'],
        ['Савеловская', '18', '78', '110'],
        ['Коммунарка', '3', '101', '380'],
        ['Кожуховская', '12', '93', '48'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        array_map(function ($a) {
            Metro::query()->insert(array_combine(self::KEYS, $a));
        }, self::VALS);
    }
}