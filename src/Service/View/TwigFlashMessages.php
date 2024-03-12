<?php
// TwigFlashMessages.php

namespace App\Service\View;

use Slim\Flash\Messages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFlashMessages extends AbstractExtension
{

    public function __construct(private Messages $flash)
    {
    }

    // extension name
    public function getName(): string
    {
        return 'slim-twig-flash';
    }

    // twig callback
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getMessages']),
        ];
    }

    // if key get its message, otherwise get all messages
    public function getMessages(?string $key = null): ?array
    {
        return $key ? $this->flash->getMessage($key) : $this->flash->getMessages();
    }
}
