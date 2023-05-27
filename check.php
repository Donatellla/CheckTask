<?php

$inputParams = parseInputParams($argv);
if(array_key_exists('user',$inputParams)){
    $userName = getCurrentUser($inputParams['user']);
} else{
    $userName = getCurrentUser();
}

$inputParamsTask = array_key_exists('task', $inputParams) ? $inputParams['task'] : 0;

global $resultFilePath;
$resultFilePath = '';

switch($inputParamsTask){
    case 1:{
        task1($userName);
        break;
    }

    case 2:{
        task2();
        break;
    }

    case 3:{
        task3();
        break;
    }

     case 4:{
        task4();
        break;
    }

    case 5:{
        task5();
        break;
    }

    case 6:{
        task6();
        break;
    }

    case 7:{
        task7($userName);
        break;
    }

    case 8:{
        task8($userName);
        break;
    }

    case 9:{
        task9($userName);
        break;
    }

    case 10:{
        task10();
        break;
    }

    case 11:{
        task11();
        break;
    }

    case 12:{
        task12();
        break;
    }

    case 13:{
        task13();
        break;
    }

    case 14:{
        task14();
        break;
    }

    case 15:{
        task15($userName);
        break;
    }

    default:{

        $resultFilePath = "/home/{$userName}/Desktop/result.txt";
        if(file_exists($resultFilePath)) {
            unlink($resultFilePath);
        }

        $numberAttempt = 1;

        if(file_exists("/home/{$userName}/Документы/Info.json")){
            $officialInfoString = file_get_contents("/home/{$userName}/Документы/Info.json");
        } else {
            $officialInfoString = '';
        }

        if($officialInfoString){
            $officialInfo = json_decode($officialInfoString, true);
            if($officialInfo['NUMBER_ATTEMPT']){
                $numberAttempt = $officialInfo['NUMBER_ATTEMPT'];
                ++$numberAttempt;
            }
        }

        printText('Номер попытки: ' . $numberAttempt);

        task1($userName);
        task2();
        task3();
        task4();
        task5();
        task6();
        task7($userName);
        task8($userName);
        task9($userName);
        task10();
        task11();
        task12();
        task13();
        task14();
        task15($userName);

        file_put_contents("/home/{$userName}/Документы/Info.json", json_encode(['NUMBER_ATTEMPT' => $numberAttempt]));

        break;
    }
}

function getContents($path){
    $configFileContent = file_get_contents($path);
    $configLines = explode("\n", $configFileContent);

    $params = [];
    foreach($configLines as $configLine) {
        if($configLine && strpos($configLine, '=') !== false) {
            $positionFirstEqual = strpos($configLine, '=');

            $paramKey = substr($configLine, 0, $positionFirstEqual);
            $paramValue = substr($configLine, $positionFirstEqual + 1);

            $paramValue = trim($paramValue, " \n\r\t\v\x00\"'");
            if($paramKey && $paramValue) {
                $params[$paramKey] = $paramValue;
            }
        }
    }

    return $params;
}

function task1 ($user){
    $grubConfig = getContents('/etc/default/grub');

    if(!array_key_exists('GRUB_CMDLINE_LINUX_DEFAULT', $grubConfig) || is_null($grubConfig['GRUB_CMDLINE_LINUX_DEFAULT'])){
        printText('Параметр GRUB_CMDLINE_LINUX_DEFAULT не найден в исходном файле');
        return;
    }

    if(!file_exists("/home/{$user}/Desktop/exercise1.txt")){
        printText('Файл exercise1.txt не найден');
        return;
    }

    $exercise1Config = getContents("/home/{$user}/Desktop/exercise1.txt");

    if(!array_key_exists('GRUB_CMDLINE_LINUX_DEFAULT', $exercise1Config) || is_null($exercise1Config['GRUB_CMDLINE_LINUX_DEFAULT'])){
        printText('Параметр GRUB_CMDLINE_LINUX_DEFAULT не найден в exercise1.txt');
        return;
    }

    $arSourceParams = explode(' ', $grubConfig['GRUB_CMDLINE_LINUX_DEFAULT']);
    $arResultParams = explode(' ', $exercise1Config['GRUB_CMDLINE_LINUX_DEFAULT']);

    $intersect = array_intersect($arSourceParams, $arResultParams);
    $array = ["parsec.max_ilev=63", "quiet", "net.ifnames=0"];

    if(count(array_intersect($intersect, $array)) != count($array)){
        printText('Задание №1 не выполнено!');
        return;
    }
    printText('Задание №1 выполнено!');
}

function task2 (){
    $configGrub = getContents('/etc/default/grub');

    if(!array_key_exists('GRUB_BACKGROUND', $configGrub) || is_null($configGrub['GRUB_BACKGROUND'])){
        printText('Параметр не найден в исходном файле');
        return;
    }

    if($configGrub['GRUB_BACKGROUND'] != '/usr/share/images/desktop-base/kamchatka1.jpg'){
        printText('Задание №2 не выполнено!');
        return;
    }

    printText('Задание №2 выполнено!');
}

function task3 (){
     $configFilePasswdContent = file_get_contents('/etc/passwd');
     preg_match('#testgrub:x:(\d{4}):(\d{4}):([a-zA-Z,]+):\/home\/testgrub:\/bin\/bash#', $configFilePasswdContent, $matchPasswd);

     if(!$matchPasswd){
        printText('Пользователь не создан');
        return;
    }

     $configFileGroupContent = file_get_contents('/etc/group');
     preg_match('#astra-admin:x:\d{4}:(.*)#', $configFileGroupContent, $matchGroup);
     $arSourceParams = explode(',', $matchGroup[1]);
     if(!in_array('testgrub', $arSourceParams)){
         printText('Пользователь не добавлен в группу astra-admin');
         return;
     }

     $configFileGrubContent = file_get_contents('/etc/grub.d/40_custom');
     preg_match('#set superusers="testgrub"#', $configFileGrubContent, $matchUser);

     if(!$matchUser){
         printText('Пользователю не задан пароль для доступа к меню загрузчика GRUB');
         return;
     }

     $password = 'grub.pbkdf2.sha512.10000.0AA6AB8C6830316D9F8CF3F53144A53EB9F65334894516B47FE234091814562BEECFD33D63FC61CD45FA1953C46676919AD2191A178AED21A34443C119C6FA93.D37F402C8824C34DEDB8C38BBE69508AC4B8A67FEE7AD4E0D6D1D323C2207931314A0C048F1F94B99560F664C57314F20C834B4DD5070E65EA97FD59B31476B7';

     preg_match('#password_pbkdf2 testgrub (.*)#', $configFileGrubContent, $matchPassword);
     if($password != $matchPassword[1]){
         printText('Пользователю задан неверный пароль для доступа к меню загрузчика GRUB');
         return;
     }

     printText('Задание №3 выполнено!');

}

function task4(){
    $configFileShadowContent = file_get_contents('/etc/shadow');
    preg_match('#root:(.*):\d{5}:\d:\d{5}:\d:::#', $configFileShadowContent, $matchRoot);

    $password = '$gost12512hash$5F4Y2cH1ECJiUOlp$HDGYYvtxxPVbpSDfsQD1g34fGCyjkatZnLUUCwBUaCRovm2f1wGLUVPF5H9TzXNvpsu4ubZiVw0TC5xWL1Gaz.';

    if($password == $matchRoot[1]){
         printText('Пароль суперпользователя не изменён!');
         return;
     }

     printText('Задание №4 выполнено!');
}

function task5(){
    $configGrub = getContents('/etc/default/grub');

    if(!array_key_exists('GRUB_TIMEOUT', $configGrub) || is_null($configGrub['GRUB_TIMEOUT'])){
        printText('Параметр не найден в исходном файле');
        return;
    }

    if($configGrub['GRUB_TIMEOUT'] != '4'){
        printText('Время ожидания для выбора опции загрузки в меню GRUB не изменено!');
        return;
    }

    if(!array_key_exists('GRUB_HIDDEN_TIMEOUT_QUIET', $configGrub) || is_null($configGrub['GRUB_HIDDEN_TIMEOUT_QUIET'])){
        printText('Параметр не найден в исходном файле');
        return;
    }

    if($configGrub['GRUB_HIDDEN_TIMEOUT_QUIET'] != 'true'){
        printText('Не скрыта информация о загрузочном меню!');
        return;
    }

    if(!array_key_exists('GRUB_TIMEOUT_STYLE', $configGrub) || is_null($configGrub['GRUB_TIMEOUT_STYLE'])){
        printText('Параметр не найден в исходном файле');
        return;
    }

    if($configGrub['GRUB_TIMEOUT_STYLE'] != 'countdown'){
        printText('Не верный стиль отображения таймера обратного отсчета!');
        return;
    }

    printText('Задание №5 выполнено!');
}

function task6(){
    $fileNames = scandir('/boot');

    $genericExists = false;
    foreach($fileNames as $fileName) {
        $fileNameParts = explode('-', $fileName);
        $countParts = count($fileNameParts);

        if($fileNameParts[$countParts - 1] == 'generic') {
            $genericExists = true;
            break;
        }
    }

    if($genericExists) {
        printText('Задание №6 выполнено!');
    } else {
        printText('Задание №6 не выполнено!');
    }
}

function task7($user){
    $modulesContent = file_get_contents('/proc/modules');
    preg_match('#iwlwifi \d+ \d+ \S* \S* \dx\d* \(\S\)#', $modulesContent, $module);

    if(!$module){
        printText('Модуль ядра iwlwifi не загружен!');
        return;
    }

     if(!file_exists("/home/{$user}/Desktop/exercise7.txt")){
        printText('Файл exercise7.txt не найден');
        return;
    }

    $exercise7Config = file_get_contents("/home/{$user}/Desktop/exercise7.txt");

    $needValues = ['insmod','rmmod'];
    $configValues = explode(',', $exercise7Config);
    foreach($configValues as $key => $value){
        $configValues[$key] = trim($value);
    }

    if(count(array_diff($configValues, $needValues)) != 0) {
        printText('Файл exercise7.txt заполнен некорректно!');
        return;
    }

    printText('Задание №7 выполнено!');

}

function task8($user){
    if(!file_exists("/usr/local/bin/startup.sh")){
        printText('Bash-скрипт startup.sh не найден');
        return;
    }

    if(!file_exists("/etc/systemd/system/startup.service")){
        printText('startup.service не найден');
        return;
    }

    $statusEnabled = exec('systemctl is-enabled startup.service');

    if ($statusEnabled != 'enabled') {
        printText('Cлужба не настроена для автоматического запуска');
        return;
    }

    if(!file_exists("/home/{$user}/Desktop/exercise8.txt")){
        printText('Файл exercise8.txt не найден');
        return;
    }

    if(count(file("/home/{$user}/Desktop/exercise8.txt", FILE_SKIP_EMPTY_LINES)) > 10){
        printText('Bash-скрипт startup.sh работает некорректно, в файле exercise8.txt больше 10 строк!');
        return;
    }

    $exercise8Content = file_get_contents("/home/{$user}/Desktop/exercise8.txt");
    preg_match('#Добро пожаловать в AstraLinuxSE: .* \d{2}:\d{2}:\d{2} \d{2} .* \d{4}#', $exercise8Content, $exercise8);

    if(!$exercise8){
         printText('Файл exercise8.txt пустой или заполняется некорректно!');
         return;
     }

     printText('Задание №8 выполнено!');
}

function task9($user){
    if(!file_exists("/usr/local/bin/check.sh")){
        printText('Bash-скрипт check.sh не найден');
        return;
    }

    if(!file_exists("/etc/systemd/system/check.service")){
        printText('check.service не найден');
        return;
    }

    if(!file_exists("/etc/systemd/system/check.timer")){
        printText('check.timer не найден');
        return;
    }

    $statusEnabled = exec('systemctl is-enabled check.timer');

    if ($statusEnabled != 'enabled') {
        printText('Cлужба не настроена для автоматического запуска');
        return;
    }

    if(!file_exists("/home/{$user}/Desktop/exercise9.txt")){
        printText('Файл exercise9.txt не найден');
        return;
    }

     if(count(file("/home/{$user}/Desktop/exercise9.txt", FILE_SKIP_EMPTY_LINES)) > 10){
        printText('Bash-скрипт check.sh работает некорректно, в файле exercise9.txt больше 10 строк!');
        return;
    }

    $exercise9Content = file_get_contents("/home/{$user}/Desktop/exercise9.txt");
    preg_match_all('#Проверка: \d{2}:(.*)#', $exercise9Content, $exercise9);

    if(!$exercise9){
         printText('Файл exercise9.txt пустой или заполняется некорректно!');
         return;
     }

    if (count($exercise9[1]) >= 3) {
        $diff1 = abs($exercise9[1][1] - $exercise9[1][0]);
        $diff2 = abs($exercise9[1][2] - $exercise9[1][1]);
        if ($diff1 != 2 && $diff2 != 2) {
            printText('Файл exercise9.txt заполняется некорректно, скрипт выполняется не каждые 2 минуты!');
            return;
        }

    } else{
        printText('Файл exercise9.txt недостаточно заполнен, для проверки должно быть минимум 3 записи!');
        return;
    }

    printText('Задание №9 выполнено!');

}

function task10(){
     if(!file_exists("/etc/systemd/system/routing.service")){
        printText('routing.service не найден');
        return;
    }

    $statusEnabled = exec('systemctl is-enabled routing.service');

    if ($statusEnabled != 'enabled') {
        printText('Cлужба не настроена для автоматического запуска');
        return;
    }

    $ip_forward = file_get_contents("/proc/sys/net/ipv4/ip_forward");
    preg_match('#\d{1}#', $ip_forward, $ipforwardValue);

    if($ipforwardValue[0] != '1'){
         printText('Задание №10 не выполнено!');
         return;
     }

     printText('Задание №10 выполнено!');

}

function task11(){
    if(!file_exists("/usr/local/bin/exercise11.sh")){
        printText('Bash-скрипт exercise11.sh не найден');
        return;
    }

    if(!file_exists("/etc/systemd/system/exercise11.service")){
        printText('exercise11.service не найден');
        return;
    }

    $serviceContent = file_get_contents("/etc/systemd/system/exercise11.service");
    preg_match('#PDPLable=(.*):(.*):(.*)#', $serviceContent, $paramValue);

    if(!$paramValue){
        printText('Параметр PDPLable не найден в exercise11.service!');
        return;
    }

    $value1 = trim($paramValue[1], " \n\r\t\v\x00\"'");
    $value2 = trim($paramValue[2], " \n\r\t\v\x00\"'");
    $value3 = trim($paramValue[3], " \n\r\t\v\x00\"'");

    if($value1 != 1 || $value2 != 0 || $value3 != 0){
        printText('Значение PDPLable задано неверно!');
        return;
    }

    printText('Задание №11 выполнено!');
}

function task12(){
    if(!file_exists("/usr/local/bin/exercise12.sh")){
        printText('Bash-скрипт exercise12.sh не найден');
        return;
    }

    if(!file_exists("/etc/systemd/system/exercise12.service")){
        printText('exercise12.service не найден');
        return;
    }

    $serviceContent = file_get_contents("/etc/systemd/system/exercise12.service");
    preg_match('#CapabilitiesParsec=(.*)#', $serviceContent, $paramValue);

    if(!$paramValue){
        printText('Параметр CapabilitiesParsec не найден в exercise12.service!');
        return;
    }

    $value = trim($paramValue[1], " \n\r\t\v\x00\"'");

    if($value != 'PARSEC_CAP_PRIV_SOCK' ){
        printText('Значение CapabilitiesParsec задано неверно!');
        return;
    }

    printText('Задание №12 выполнено!');
}

function task13(){
    $fstabContent = file_get_contents("/etc/fstab");
    preg_match('#UUID.*\/media\/usb.*auto.*defaults,nofail.*0.*0#', $fstabContent, $fstabValue);

    if(!$fstabValue){
        printText('Запись в файле /etc/fstab не найдена');
        return;
    }

    printText('Задание №13 выполнено!');
}

function task14(){
    $grubConfig = getContents('/etc/default/grub');

    if(!array_key_exists('GRUB_CMDLINE_LINUX_DEFAULT', $grubConfig) || is_null($grubConfig['GRUB_CMDLINE_LINUX_DEFAULT'])){
        printText('Параметр GRUB_CMDLINE_LINUX_DEFAULT не найден в исходном файле');
        return;
    }

    $arSourceParams = explode(' ', $grubConfig['GRUB_CMDLINE_LINUX_DEFAULT']);

    $find = false;
    foreach($arSourceParams as $param){
        if($param == 'fsck.mode=force'){
            printText('Задание №14 выполнено!');
            $find = true;
            break;
        }
    }

    if($find == false){
        printText('Задание №14 не выполнено!');
        return;
    }
}

function task15($user){
    if(!file_exists("/usr/local/bin/exercise15.sh")){
        printText('Bash-скрипт exercise15.sh не найден');
        return;
    }

    $cronContent = exec("crontab -l -u {$user}");
    preg_match('#0 0 \* \* \* \/usr\/local\/bin\/exercise15.sh#', $cronContent, $cron);

    if(!$cron){
        printText('Задание в cron не найдено или записано некорректно!');
        return;
    }

    $dateNow = date('d.m');

    if(!file_exists("/home/{$user}/Desktop/weather_{$dateNow}.png")){
        printText('Файл с прогнозом погоды не найден! Запустите скрипт exercise15.sh и повторите проверку!');
        return;
    }

    printText('Задание №15 выполнено!');
}

function getCurrentUser($userName = ''){
    if($userName == ''){
        return posix_getlogin();
    } else {
        return $userName;
    }
}

function parseInputParams($argv): array {
    $copyArgv = $argv;
    unset($copyArgv[0]);

    $parsedParams = [];
    foreach($copyArgv as $arg){
        $arg = trim($arg, " \n\r\t\v\x00-");
        $explodedParam = explode('=', $arg);
        $parsedParams[$explodedParam[0]] = $explodedParam[1];
    }

    return $parsedParams;
}

function printText($data){
    global $resultFilePath;

    if($resultFilePath) {
        file_put_contents($resultFilePath, $data . PHP_EOL, FILE_APPEND);
    }

    echo $data . PHP_EOL;
    flush();
}
