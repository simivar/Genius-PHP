<?php
declare(strict_types=1);

namespace Genius\Factory;

use Genius\HttpClient\ClientConfiguration;
use Genius\HttpClient\RequesterInterface;
use Http\Message\Authentication;

trait RequesterFactoryTrait
{
    protected ?Authentication $authentication = null;
    private RequesterInterface $requester;
    private RequesterFactory $requesterFactory;

    public function setRequesterFactory(RequesterFactory $requesterFactory): void
    {
        $this->requesterFactory = $requesterFactory;
    }

    public function getRequesterFactory(): RequesterFactory
    {
        if (!isset($this->requesterFactory)) {
            RequesterFactory::setClientConfiguration(new ClientConfiguration($this->authentication));
            $this->requesterFactory = new RequesterFactory();
        }

        return $this->requesterFactory;
    }

    public function getRequester(): RequesterInterface
    {
        if (!isset($this->requester)) {
            $this->requester = $this->getRequesterFactory()::create();
        }

        return $this->requester;
    }
}
