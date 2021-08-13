<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('hash', [$this, 'hash']),
        ];
    }

    public function hash($string)
    {
        return hash('sha1', $string);
    }
}