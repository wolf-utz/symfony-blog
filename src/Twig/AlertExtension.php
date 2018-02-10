<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AlertExtension.
 */
class AlertExtension extends AbstractExtension
{
    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('alert', array($this, 'alertFunction')),
        );
    }

    /**
     * @param string $message
     * @param string $level
     *
     * @return string
     */
    public function alertFunction(string $message, string $level = 'success'): string
    {
        return "<div class=\"alert alert-$level\" role=\"alert\">$message</div>";
    }
}
