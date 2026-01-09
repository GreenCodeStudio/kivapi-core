<?php

namespace Core\Events;
class EventDispatcher
{
    public static function dispatch(string $eventName, object $eventData): void
    {
        foreach (self::listHandlerClasses($eventName) as $handlerClass) {
            if (class_exists($handlerClass) && method_exists($handlerClass, 'handle')) {
                $handlerClass::handle($eventName, $eventData);
            }
        }
    }

    private static function listHandlerClasses(string $eventName): array
    {
        $ret = [];
        if (is_dir(__DIR__."/../../EventHandlers")) {
            $files = scandir(__DIR__."/../../EventHandlers");
            foreach ($files as $file) {
                if (str_ends_with($file, ".php")) {
                    $className = "Page\\EventHandlers\\".str_replace(".php", "", $file);
                    $ret[] = $className;
                }
            }
        }
        return $ret;
    }
}
