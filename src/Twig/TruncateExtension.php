<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class TruncateExtension.
 */
class TruncateExtension extends AbstractExtension
{
    /**
     * @return array|\Twig_Filter
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('truncate', array($this, 'truncateFilter')),
        );
    }

    /**
     * @param string $text
     * @param int    $limit
     * @param bool   $respectWordBoundary
     * @param string $append
     *
     * @return string
     */
    public function truncateFilter(string $text, int $limit, bool $respectWordBoundary = false, string $append = '...'): string
    {
        if (strlen($text) <= $limit) {
            return $text;
        }
        if ($respectWordBoundary) {
            $text = $this->truncateWordWise($text, $limit, $append);
        }

        if (!$respectWordBoundary) {
            $text = $this->truncateCharWise($text, $limit, $append);
        }

        return trim($text);
    }

    /**
     * @param string $text
     * @param int    $limit
     * @param string $append
     *
     * @return string
     */
    private function truncateCharWise(string $text, int $limit, string $append)
    {
        return substr($text, 0, $limit).$append;
    }

    /**
     * White spaces will not affect the resulting char count.
     *
     * @param string $text
     * @param int    $limit
     * @param string $append
     *
     * @return string
     */
    private function truncateWordWise(string $text, int $limit, string $append)
    {
        $result = '';
        $currentLength = 0;
        $words = explode(' ', $text);
        /** @var string $word */
        foreach ($words as $word) {
            if ($currentLength + strlen($word) > $limit) {
                return $result.$append;
            }
            $currentLength += strlen($word);
            $result .= ' '.$word;
        }

        return $result;
    }
}
