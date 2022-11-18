<?php
/**
 * This script takes an argument in command line in JSON for creating a INI file with user input data.
 *
 * Need to provide a JSON string in argument, JSON structure example:
 * {
 *     "file":"protected/conf/database.ini",
 *     "input":{
 *         "host":{
 *             "question":"MySQL hostname:"
 *         },
 *         "port":{
 *             "question":"MySQL port (default is 3306):",
 *             "default":"3306"
 *         },
 *         "dbname":{
 *             "question":"Database name:"
 *         },
 *         "username":{
 *             "question":"Database username:"
 *         },
 *         "password":{
 *             "question":"Database password:"
 *         },
 *         "charset":{
 *             "question":"Charset (default is utf8mb4):",
 *             "default":"utf8mb4"
 *         }
 *     }
 * }
 *
 * The JSON need to be escaped, usage example:
 * php ini.php '{\"file\":\"protected\/conf\/database.ini\",\"input\":{\"host\":{\"question\":\"MySQL hostname:\"},\"port\":{\"question\":\"MySQL port (default is 3306):\",\"default\":\"3306\"},\"dbname\":{\"question\":\"Database name:\"},\"username\":{\"question\":\"Database username:\"},\"password\":{\"question\":\"Database password:\"},\"charset\":{\"question\":\"Charset (default is utf8mb4):\",\"default\":\"utf8mb4\"}}}'
 */

// Check if argument exists
$arg = $argv[1] ?? null;
if (is_null($arg)) {
	echo 'Missing command line argument' . PHP_EOL;
	exit(1);
}

$ini = json_decode($arg, true);

// Check if file parameter is defined
$file = $ini['file'] ?? null;
if (is_null($file)) {
	echo 'INI file parameter not defined' . PHP_EOL;
	exit(1);
}

// Do nothing if the INI file already exists
if (file_exists($file)) {
	echo 'INI file already exists' . PHP_EOL;
	exit;
}

// Check if input parameter is defined
$input = $ini['input'] ?? [];
if (empty($input)) {
	echo 'INI file input parameter not defined' . PHP_EOL;
	exit(1);
}

echo "\n\033[1;37m\033[44m                                         \033[0m\n";
echo "\033[1;37m\033[44m    Welcome to the INI file generator    \033[0m\n";
echo "\033[1;37m\033[44m                                         \033[0m\n\n";
echo "This command will guide you through creating your $file file.\n\n";

// Read user inputs
$result = '';
foreach ($input as $name => $data) {
	$question = $data['question'] ?? $name;
	$default = $data['default'] ?? '';
	$value = trim(readline("$question [$default]: "));
	$result .= "$name = " . (empty($value) ? $default : $value) . PHP_EOL;
}

// Create file
if (!file_put_contents($file, $result, FILE_APPEND | LOCK_EX)) {
	echo 'Write file error!';
	exit(2);
}
echo $file . ' created successfully!' . PHP_EOL;