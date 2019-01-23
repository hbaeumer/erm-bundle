<?php declare(strict_types=1);

/**
 * This file is part of hbaeumer/erm-bundle
 *
 * Copyright (c) Heiner Baeumer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */


namespace Hbaeumer\ErmBundle\Grapher;

class Encoder
{
    public function encode(string $text): string
    {
        $compressed = gzdeflate($text, 9);
        return $this->encode64($compressed);
    }

    private function encode64(string $compressed): string
    {
        $str = "";
        $len = strlen($compressed);
        for ($i = 0; $i < $len; $i += 3) {
            if ($i + 2 === $len) {
                $str .= $this->append3bytes(ord(substr($compressed, $i, 1)), ord(substr($compressed, $i + 1, 1)), 0);
            } elseif ($i + 1 === $len) {
                $str .= $this->append3bytes(ord(substr($compressed, $i, 1)), 0, 0);
            } else {
                $str .= $this->append3bytes(
                    ord(substr($compressed, $i, 1)),
                    ord(substr($compressed, $i + 1, 1)),
                    ord(substr($compressed, $i + 2, 1))
                );
            }
        }
        return $str;
    }

    private function append3bytes(int $byte1, int $byte2, int $byte3): string
    {
        $cbyte1 = $byte1 >> 2;
        $cbyte2 = (($byte1 & 0x3) << 4) | ($byte2 >> 4);
        $cbyte3 = (($byte2 & 0xF) << 2) | ($byte3 >> 6);
        $cbyte4 = $byte3 & 0x3F;
        $string = "";
        $string .= $this->encode6bit($cbyte1 & 0x3F);
        $string .= $this->encode6bit($cbyte2 & 0x3F);
        $string .= $this->encode6bit($cbyte3 & 0x3F);
        $string .= $this->encode6bit($cbyte4 & 0x3F);
        return $string;
    }

    private function encode6bit(int $bit): string
    {
        if ($bit < 10) {
            return chr(48 + $bit);
        }
        $bit -= 10;
        if ($bit < 26) {
            return chr(65 + $bit);
        }
        $bit -= 26;
        if ($bit < 26) {
            return chr(97 + $bit);
        }
        $bit -= 26;
        if ($bit === 0) {
            return '-';
        }
        if ($bit === 1) {
            return '_';
        }
        return '?';
    }
}
