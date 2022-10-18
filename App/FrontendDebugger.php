<?php

namespace NoelClick\PhpFrontendDebugger;

use Exception;
use function Composer\Autoload\includeFile;

/**
 * @author @NoelClick
 * @copyright 2022 by Noel Kayabasli
 */
class FrontendDebugger
{
    /**
     * @var ?FrontendDebugger Stores the singleton object
     */
    private static ?FrontendDebugger $fd = null;

    /**
     * @var string Stores the generated HTML.
     */
    private string $html = "";

    /**
     * @var bool Will be false when any of the specified conditions are false (You can specify a condition using the showIf() method).
     */
    private bool $condition = true;

    /**
     * @param bool $showByDefault Specifies whether the off-canvas should be open by default.
     */
    private bool $showByDefault = false;

    /**
     * @var string
     */
    private string $currentExceptionString = "";

    /**
     * @var array
     */
    private array $exceptionList = [];

    private function __construct()
    {

    }

    public function addToException(string $exceptionString): FrontendDebugger
    {
        $this->currentExceptionString .= $exceptionString . "\n";
        return $this;
    }

    public function insertException(string $title = "Exception"): FrontendDebugger
    {
        $this->exceptionList[] = '<p><strong>' . ((isset($title) && $title != '') ? $title : 'Exception') . '</strong></p><pre>' . var_export($this->currentExceptionString, true) . '</pre>';
        return $this;
    }

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

    public static function getInstance(): FrontendDebugger
    {
        if (self::$fd === null) {
            self::$fd = new self();
        }
        return self::$fd;
    }

    public function showByDefault(): FrontendDebugger
    {
        $this->showByDefault = true;
        return $this;
    }

    /**
     * @param mixed $data
     * @param string $title
     * @return $this
     */
    public function insert(mixed $data, string $title = "Variable"): FrontendDebugger
    {
        if (is_array($data) && $this->isRecursive($data)) { // is a recursive array
            $this->html .= '<p><small>[RECURSIVE]</small> <strong>' . $title . '</strong></p><pre>' . print_r($data, true) . '</pre>';
            $this->insertDivider();
            return $this;
        }
        $this->html .= '<p><strong>' . $title . '</strong></p><pre>' . htmlspecialchars(var_export($data, true)) . '</pre>';
        $this->insertDivider();
        return $this;
    }

    /**
     * @param array $array
     * @return bool
     */
    private function isRecursive(array $array): bool
    {
        return in_array($array, $array, true);
    }

    /**
     * @return void
     */
    private function insertDivider(): void
    {
        $this->html .= '<hr>';
    }

    /**
     * @return $this
     */
    public function printButton(): FrontendDebugger
    {
        if ($this->condition) echo '<button type="button" id="frontendDebuggerButton">Toggle debug info</button>';
        return $this;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function showIf(bool $param): FrontendDebugger
    {
        if (!$param) $this->condition = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function printHtml(): FrontendDebugger
    {
        if ($this->condition) {
            $exceptionString = "";
            foreach ($this->exceptionList as $exception) {
                $exceptionString .= $exception . "<hr>";
            }
            $this->html = '<style>'. file_get_contents(__DIR__."/../Public/Stylesheet/fedebugger.min.css") .'</style>
            <div ' . ($this->showByDefault ? 'class="show"' : '') . ' id="frontendDebuggerOffcanvas">
    <div class="offcanvas-header">
        <h5 id="frontendDebuggerOffcanvasLabel">Frontend Debugger</h5>
        <button type="button" id="frontendDebuggerCloseButton">Close</button>
    </div>
    <div class="offcanvas-body">
' . $exceptionString . $this->html;
            print $this->html . '<p style="padding: 6px; font-size: 14px; text-align: center;">&copy; Copyright 2022 by <a style="color: #fff; text-decoration: underlined;" href="https://github.com/NoelClick/php-frontend-debugger" target="_blank" title="GitHub repository">NoelClick</a></p></div><div class="offcanvas-resizer"></div></div><script>'. file_get_contents(__DIR__."/../Public/JavaScript/fedebugger.js") .'</script>';
        }
        return $this;
    }

}
