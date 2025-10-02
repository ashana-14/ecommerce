<?php

namespace App\Helpers;

class StripeCheckoutSuccess
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = StripeClient::getClient();
    }

    /**
     * Undocumented function
     *
     * @param [type] $session_id
     * @return void
     */
    public function updateCheckoutOrder($session_id)
    {
        return true;
    }
}
