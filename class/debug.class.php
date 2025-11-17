<?php
class CDebugLog
{
    static public function Write( $stringdata )
    {
        $hourMin = date('H');
        $logDir = __DIR__ . '/../logs/';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);

        $logFile = $logDir . "debug_" . $hourMin . ".log";
        $pFile = @fopen($logFile, "at");
        if (!$pFile) return;

        fprintf($pFile, "[" . date("Y-m-d H:i:s") . "]" . $stringdata . "\n");
        fclose($pFile);
    }
}
?>
