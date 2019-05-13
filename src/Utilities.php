<?php

namespace MohammedIO;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Utilities
{
    /**
     * @param string $filename
     * @return string
     */
    public function generateFilename($filename)
    {
        $timestamp = str_replace(':',
            '',
            str_replace(
                ['-', ' '],
                '_',
                Carbon::now()->toDateTimeString()
            )
        );

        return $timestamp."_". Str::snake(Str::lower(Str::snake($filename)));
    }

    /**
     * @param string $className
     * @return string
     */
    public function generateMaybeUniqueClassName($className)
    {
        $day = Carbon::now()->format('md');

        return Str::ucfirst(Str::camel($className)).$day. substr($this->getGuid(), 0, 8);
    }

    /**
     * @param $path
     * @param $content
     * @return void
     */
    public function writeFile($path, $content)
    {
        file_put_contents($path, $content);
    }

    /**
     * @param $path
     * @return false|string
     */
    public function readFile($path)
    {
        return file_get_contents($path);
    }

    /**
     * @author Dave Pearson at [https://www.php.net/manual/en/function.com-create-guid.php#119168]
     *
     * @param bool $trim
     * @return string
     */
    public function getGuid ($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
            substr($charid,  0,  8).$hyphen.
            substr($charid,  8,  4).$hyphen.
            substr($charid, 12,  4).$hyphen.
            substr($charid, 16,  4).$hyphen.
            substr($charid, 20, 12).
            $rbrace;
        return $guidv4;
    }
}