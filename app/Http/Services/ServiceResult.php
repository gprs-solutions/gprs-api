<?php

namespace App\Http\Services;

class ServiceResult
{
    /**
     * Wheather the operation was successful or not.
     *
     * @var boolean $success
     */
    public bool $success;

    /**
     * Returned data.
     *
     * @var mixed $data
     */
    public mixed $data;

    /**
     * Message with the result of the operation.
     *
     * @var string $message
     */
    public ?string $message;

    /**
     * Constructor for the ServiceResult class.
     *
     * @param boolean     $success - Whether the operation was successful.
     * @param mixed       $data    - The data returned by the service (if any).
     * @param string|null $message - An optional message, usually for feedback.
     *
     * @return void
     */
    public function __construct(bool $success = true, $data = null, ?string $message = null)
    {
        $this->success = $success;
        $this->data    = $data;
        $this->message = $message;
    }

    /**
     * Factory method to create a successful ServiceResult.
     *
     * @param string|null $message - An optional success message.
     * @param mixed       $data    - The data returned by the service (if any).
     *
     * @return ServiceResult - A new instance of ServiceResult indicating success.
     */
    public static function success(?string $message = null, $data = null): self
    {
        return new self(true, $data, $message);
    }

    /**
     * Factory method to create a failed ServiceResult.
     *
     * @param string|null $message - An optional error message explaining the failure.
     * @param mixed       $data    - Any additional data relevant to the failure (optional).
     *
     * @return ServiceResult - A new instance of ServiceResult indicating failure.
     */
    public static function failure(?string $message = null, $data = null): self
    {
        return new self(false, $data, $message);
    }
}
