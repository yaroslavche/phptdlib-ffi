<?php

namespace TdLib;

use Closure;
use FFI;
use TdLib\Exception\TdLibExceptionInterface;
use TdLib\Exception\UnsupportedOS;
use const PHP_OS_FAMILY;

class TdLib
{
    private readonly FFI $tdlib;

    /** @throws TdLibExceptionInterface */
    public function __construct(?string $library = null)
    {
        $this->tdlib = FFI::cdef(
            '
            typedef void(*td_log_message_callback_ptr) (int verbosity_level, const char *message);
            int td_create_client_id();
            void td_send(int client_id, const char *request);
            const char * td_receive(double timeout);
            const char * td_execute(const char *request);
            void td_set_log_message_callback(int max_verbosity_level, td_log_message_callback_ptr callback);
            ',
            $library ?? match (PHP_OS_FAMILY) {
            'Linux' => 'libtdjson.so',
            default => throw new UnsupportedOS(),
        }
        );
    }

    public function td_create_client_id(): int
    {
        return $this->tdlib->td_create_client_id();
    }

    public function td_send(int $clientId, string $request): void
    {
        $this->tdlib->td_send($clientId, $request);
    }

    public function td_receive(float $timeout): ?string
    {
        return $this->tdlib->td_receive($timeout);
    }

    public function td_execute(string $request): string
    {
        return $this->tdlib->td_execute($request);
    }

    public function td_set_log_message_callback(int $maxVerbosityLevel, Closure $callback): void
    {
        $this->tdlib->td_set_log_message_callback($maxVerbosityLevel, $callback);
    }
}
