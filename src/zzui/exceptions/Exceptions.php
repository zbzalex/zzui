<?php

namespace zzui\exceptions;

class Exceptions
{
    public static function formatExceptionTrace(\Exception $e)
    {
        $lines = [];
        $lines[] = "Exception: " . get_class($e) . ' — ' . $e->getMessage();
        $lines[] = "in " . $e->getFile() . ":" . $e->getLine();
        $lines[] = str_repeat("=", 80);

        foreach ($e->getTrace() as $index => $frame) {
            $file = isset($frame['file']) ? $frame['file'] : '[internal function]';
            $line = isset($frame['line']) ? $frame['line'] : '';
            $class = isset($frame['class']) ? $frame['class'] : '';
            $type = isset($frame['type']) ? $frame['type'] : '';
            $function = $frame['function'];
            $args = isset($frame['args']) ? array_map('gettype', $frame['args']) : [];

            $lines[] = sprintf(
                "#%02d %s(%s): %s%s%s(%s)",
                $index,
                $file,
                $line,
                $class,
                $type,
                $function,
                implode(', ', $args)
            );
        }

        return implode("<br />", $lines);
    }
}
