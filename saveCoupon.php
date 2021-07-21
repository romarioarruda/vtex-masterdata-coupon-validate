<?php
require_once "readFile.php";
require_once "fileGenerator.php";
require_once "src/MasterData.php";

$fileName = $argv[1];
$configEnv = parse_ini_file('env.ini');

if(!$fileName) exit('Informe o nome do arquivo.');
if(!$configEnv) exit('Variáveis de ambiente não definidas.');

$readFile = readFilePerLine($fileName);

$appKey = $appKey;
$appToken = $appToken;

foreach($readFile as $key => $line) {
  $lineKey = ($key+1);
  $coupon = trim($line);

  $payload = [
    $configEnv['APP_ACCOUNT'],
    $configEnv['APP_KEY'],
    $configEnv['APP_TOKEN'],
    $configEnv['APP_ENTITY_NAME'],
    ["coupon" => $coupon, "ativo" => true]
  ];

  $response = MasterData::saveDocument($payload);

  if(!empty($response)) {
    echo "cupon $coupon salvo n bd.\n";
  } else {
    echo "cupon $coupon n foi salvo n bd.\n";
    fileGenerator("pendentes_$fileName", $coupon);
  }
}
