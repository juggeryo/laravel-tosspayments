<?php

namespace Getsolaris\LaravelTossPayments\Attributes;

use Getsolaris\LaravelTossPayments\Contracts\AttributeInterface;
use Getsolaris\LaravelTossPayments\TossPayments;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

class Payment extends TossPayments implements AttributeInterface
{
    /**
     * @var string $uri
     */
    protected string $uri;

    /**
     * @var string $paymentKey
     */
    protected string $paymentKey;

    /**
     * @var string $orderId
     */
    protected string $orderId;

    /**
     * @var int $amount
     */
    protected int $amount;

    public function __construct()
    {
        parent::__construct($this);
        $this->initializeUri();
    }

    /**
     * @return $this
     */
    public function initializeUri(): static
    {
        $this->uri = '/payments';

        return $this;
    }

    /**
     * @param  string|null  $endpoint
     * @return string
     */
    public function createEndpoint(?string $endpoint): string
    {
        return $this->url . $this->uri . $this->start($endpoint);
    }

    /**
     * @param  string  $paymentKey
     * @return $this
     */
    public function paymentKey(string $paymentKey): static
    {
        $this->paymentKey = $paymentKey;

        return $this;
    }

    /**
     * @param  string  $orderId
     * @return $this
     */
    public function orderId(string $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @param  int  $amount
     * @return $this
     */
    public function amount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return PromiseInterface|Response
     */
    public function confirm(): PromiseInterface|Response
    {
        return $this->client->post($this->createEndpoint('/confirm'), [
            'paymentKey' => $this->paymentKey,
            'orderId' => $this->orderId,
            'amount' => $this->amount,
        ]);
    }

    /**
     * @return PromiseInterface|Response
     */
    public function get(): PromiseInterface|Response
    {
        return $this->client->get($this->createEndpoint('/' . $this->paymentKey));
    }

    /**
     * @return PromiseInterface|Response
     */
    public function order(): PromiseInterface|Response
    {
        return $this->client->get($this->createEndpoint('/orders/' . $this->orderId));
    }
}