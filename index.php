<?php

include 'array.php';

//Задача 1 "Разъединение и объединение"

function getPartsFromFullname($string) {
    $nameParts = explode(' ', $string);
    return ['surname' => $nameParts[0], 'name' => $nameParts[1],'patronomyc' => $nameParts[2]];

}

getPartsFromFullname($example_persons_array[0]['fullname']);


function getFullnameFromParts ($surname, $name, $patronomyc) {
    return $surname.' '.$name.' '.$patronomyc;

}

//TEST
$person = getPartsFromFullname ($example_persons_array[0]['fullname']);

echo getFullnameFromParts ($person['surname'], $person['name'], $person['patronomyc']);

//Задача 2 "Сокращение ФИО"


function getShortName ($string) {
    $arr = getPartsFromFullname($string);
    return $arr['surname'] .' '.mb_substr($arr['name'], 0, 1,  'UTF-8').'.';
}

//TEST
echo getShortName($example_persons_array[1]['fullname']);


//Задача 3 "Функция определения пола по ФИО"

function getGenderFromName ($string) {
    $arr = getPartsFromFullname($string);
    $sum =0;


    if (mb_substr($arr['patronomyc'], -3, 3, 'UTF-8') == 'вна') {
        $sum--;
    } elseif (mb_substr($arr['patronomyc'], -2, 2, 'UTF-8') == 'ич') {
        $sum++;
    }

    if (mb_substr($arr['name'], -1, 1, 'UTF-8') == 'а') {
        $sum--;
    } elseif (mb_substr($arr['name'], -1, 1, 'UTF-8') == 'й' || 'н') {
        $sum++;
    }

    if (mb_substr($arr['surname'], -2, 2, 'UTF-8') == 'ва') {
        $sum--;
    } elseif (mb_substr($arr['surname'], -1, 1, 'UTF-8') == 'в') {
        $sum++;
    }

    return $sum<=>0;
}

//TEST

getGenderFromName($example_persons_array[1]['fullname']);



//Задача 4 "Определение возрастно-полового состава"

function getGenderDescription ($arr) {
    $menArr = array_filter($arr, function ($value) {
        return getGenderFromName($value['fullname']) > 0;
    });

    $womenArr = array_filter($arr, function ($value) {
        return getGenderFromName($value['fullname']) < 0;
    });


    $men= (count($menArr)*100)/count($arr);
    $women= (count($womenArr)*100)/count($arr);
    $uknwn= 100 - ($men + $women);

    //Округляю перед выводом чтобы сохранить точность.

    $men = round($men, 2);
    $women= round($women, 2);
    $uknwn= round($uknwn, 2);

    echo <<<HEREDOCLETTER
Гендерный состав аудитории:
---------------------------
Мужчины - $men%
Женщины - $women%
Не удалось определить - $uknwn%
HEREDOCLETTER;
}

//TEST

getGenderDescription ($example_persons_array);


//Задача 5 "Идеальный подбор пары"

function getPerfectPartner ($surname, $name, $patronomyc, $arr){

    $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);

    $fullName = getFullnameFromParts ($surname, $name, $patronomyc);

    $gender = getGenderFromName ($fullName);



    while (true) {
        $i= rand (0, count($arr)-1);
        $partnerName = $arr[$i]['fullname'];
        $partnerGender = getGenderFromName ($partnerName);
        if ($gender != $partnerGender and $partnerGender != 0) {
            $percent = round(rand(50, 100), 2);
            $shortName = getShortName($fullName);
            $shortPartner = getShortName($partnerName);
            echo <<<HEREDOCLETTER
$shortName + $shortPartner = 
♡ Идеально на $percent% ♡
HEREDOCLETTER;
            break;
        } else {
            continue;
        }
    }

}


//создаем массив данных, которые будем передавать в качестве аргументов в функцию
//для этого воспользуемся функцией из Задачи1:

$alone = getPartsFromFullname($example_persons_array[1]['fullname']);

getPerfectPartner($alone['surname'], $alone['name'], $alone['patronomyc'], $example_persons_array);
