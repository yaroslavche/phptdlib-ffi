```php
use TdLib\TdLib;

$tdlib = new TdLib();
$tdlib->td_execute(json_encode(['@type' => 'setLogVerbosityLevel', 'new_verbosity_level' => '0']));
$tdlib->td_set_log_message_callback(1, fn(int $level, string $message) => var_dump(sprintf('%s%s', $message, PHP_EOL)));

$clientId = $tdlib->td_create_client_id();
$parameters = [
    'use_test_dc' => true,
    'database_directory' => '/var/tmp/tdlib',
    'files_directory' => '/var/tmp/tdlib',
    'use_file_database' => false,
    'use_chat_info_database' => false,
    'use_message_database' => false,
    'use_secret_chats' => false,
    'api_id' => 0,
    'api_hash' => '',
    'system_language_code' => 'en',
    'device_model' => 'Linux',
    'system_version' => 'Tumbleweed',
    'application_version' => '0.0.1',
    'enable_storage_optimizer' => true,
    'ignore_file_names' => false,
];
$tdlib->td_send($clientId, json_encode(['@type' => 'setTdlibParameters', ...$parameters]));
$tdlib->td_send($clientId, json_encode(['@type' => 'setAuthenticationPhoneNumber', 'number' => '+380631234567']));
$tdlib->td_send($clientId, json_encode(['@type' => 'setDatabaseEncryptionKey', 'key' => '']));
$tdlib->td_send($clientId, json_encode(['@type' => 'getAuthorizationState']));
while ($r = $tdlib->td_receive(0.1)) {
    var_export($r);
}
```