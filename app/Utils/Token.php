<?php

namespace App\Utils;

class Token
{
    protected $guard;
    protected $payload;

    /**
     * Creates a new instance of the class.
     *
     * @param string $guard The guard name.
     */
    public function __construct(string $guard)
    {
        $this->guard = $guard;
        $this->payload = auth()->guard($guard)->payload();
    }

    /**
     * Retrieves the expiration time for the authentication token.
     *
     * @return int The expiration time in seconds.
     */
    public function getExpiration()
    {
        $exp = auth()->factory()->getTTL() * 60;

        return $exp;
    }

    /**
     * Retrieves the expiration date and time of the object.
     *
     * @return string The expiration date and time in the format Y-m-d H:i:s.
     */
    public function getExpirationAt()
    {
        $expat = \Carbon\Carbon::createFromTimestamp($this->getExpiration())->toDateTimeString();

        return $expat;
    }
}
