<?php

namespace App\Services\Llm;

class LlmException extends \RuntimeException {}
class RateLimitError extends LlmException {}
class TimeoutError extends LlmException {}
class InvalidResponseError extends LlmException {}
class CostCapExceededError extends LlmException {}
