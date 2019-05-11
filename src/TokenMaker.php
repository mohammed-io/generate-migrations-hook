<?php

namespace MohammedIO;


class TokenMaker
{
    public function arrayToArrayToken(array $array, $depth = 0)
    {
        $output = '';

        $space = '  ';
        $indentation = str_repeat($space, $depth);

        foreach ($array as $key => $value) {
            $keyExpression = $this->generateKeyExpression($key);

            if (is_array($value)) {
                $valueToken = $this->arrayToArrayToken($value, $depth + 1);
            } else {
                $valueToken = $value === null ? "null" : "\"$value\"";
            }

            $output .= "$indentation$keyExpression $valueToken,\n";
        }

        return "[\n" . $output . "$indentation]";
    }

    public function generateKeyExpression($key)
    {
        return "\"$key\" =>";
    }
}