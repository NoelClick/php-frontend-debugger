<?php

namespace NoelClick\PhpFrontendDebugger; 

use NoelClick\PhpFrontendDebugger\FrontendDebugger;
use \Exception;

/**
 * @author @NoelClick
 * @copyright 2022 by Noel Kayabasli
 */
class ExceptionHandler
{

    /**
     * @var ?ExceptionHandler Stores the singleton object.
     */
    private static ?ExceptionHandler $eh = null;

    /**
     * @var bool Will be false if any of the specified conditions are false (you can specify a condition using the setCondition() method).
     */
    private bool $condition = true;

    private function __construct() {}
	
    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone() {}

    /**
     * prevent from being unserialized (which would create a second instance of it)
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance(): ExceptionHandler 
    {
        if (self::$eh === null) {
            self::$eh = new self();
        }
        return self::$eh;
    }

    public function handle(): void
    {
        set_exception_handler(function (\Throwable $args): void
        {
            $i = 0;
            if ($args->getFile() != null && $args->getLine() != null) {
                $i++;
                $exceptionString = '--- ' . $i . ' ---<br><b><u>'. htmlspecialchars($args->getFile()) . '</u></b>' . '<br>';
                for ($k = -5; $k < 5; $k++) {
                    $exceptionString .= $this->printLineNumber($args->getLine(), $k) . ($k == -1 ? $this->getFormattedLine(htmlspecialchars(file($args->getFile())[$args->getLine() + $k] ?? "") ?? "", null) : htmlspecialchars(file($args->getFile())[$args->getLine()+$k] ?? "") ?? "");
                }
                $exceptionString .= "<br>";
                FrontendDebugger::getInstance()->addToException($exceptionString);
            }
            foreach ($args->getTrace() as $val) {
                $i++;
                $exceptionString = "";
                if ($val["file"] != null && $val["line"] != null) {
                    $exceptionString .= '--- ' . $i . ' ---<br><b><u>'. htmlspecialchars($val["file"]) . '</u></b>' . '<br>';
                    for ($k = -5; $k < 5; $k++) {
                        $exceptionString .= $this->printLineNumber($val["line"], $k) . ($k == -1 ? $this->getFormattedLine(htmlspecialchars(file($val["file"])[$val["line"]+$k] ?? "") ?? "", htmlspecialchars($val["function"])) : htmlspecialchars(file($val["file"])[$val["line"]+$k] ?? "") ?? "");
                    }
                }
                $exceptionString .= "<br><br>Function: " . htmlspecialchars($val["function"]) ?? "<br>";
                FrontendDebugger::getInstance()->addToException($exceptionString);
            }

            FrontendDebugger::getInstance()
                ->showByDefault()
                ->insertException($args->getMessage())
                ->showIf($this->condition)
                ->printButton()
                ->printHtml();
        }
        );
    }

    /**
     * @param $condition bool If false, the frontend box is not printed.
     */
    public function setCondition(bool $condition): ExceptionHandler
    {
    	$this->condition = !$condition ? $condition : $this->condition;
	return $this;
    }

    private function getFormattedLine(string $line, ?string $functionName): string
    {
        if (isset($functionName)) {
            $line = str_replace($functionName, '<span style="color: #f00;">' . $functionName . '</span>', $line);
        }
        return "<b>" . $line . "</b>";
    }

    private function printLineNumber(int $line, int $k): string
    {
        return $line+1+$k . " ";
    }
}
