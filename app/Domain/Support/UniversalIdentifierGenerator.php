<?php

namespace App\Domain\Support;

class UniversalIdentifierGenerator
{
    /**
     * Generate UUI V4 with simple PHP.
     *
     * @return string
     * @throws \Exception
     */
    public function generate()
    {
        $randomBytes = random_bytes(16);

        $randomBytes[6] = chr(ord($randomBytes[6]) & 0x0f | 0x40);
        $randomBytes[8] = chr(ord($randomBytes[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomBytes), 4));
    }
}
