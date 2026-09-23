<?php
$file = 'C:/Users/Tavilo Breno/.gemini/antigravity-ide/brain/607b0219-8302-45f2-b548-34328664a05c/.system_generated/logs/transcript_full.jsonl';
$handle = fopen($file, 'r');
if (!$handle) {
    die("Cannot open file");
}
while (($line = fgets($handle)) !== false) {
    $data = json_decode($line, true);
    if (isset($data['content']) && strpos($data['content'], 'Módulo: Áreas Comuns, Reservas e Cobranças') !== false) {
        echo "FOUND PROMPT:\n";
        echo $data['content'] . "\n";
    }
}
fclose($handle);
