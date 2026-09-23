<?php
$file = 'C:/Users/Tavilo Breno/.gemini/antigravity-ide/brain/607b0219-8302-45f2-b548-34328664a05c/.system_generated/logs/transcript_full.jsonl';
$handle = fopen($file, 'r');
if (!$handle) {
    die("Cannot open file");
}
while (($line = fgets($handle)) !== false) {
    $data = json_decode($line, true);
    if (isset($data['type']) && $data['type'] === 'USER_INPUT' && isset($data['content']) && strpos($data['content'], 'Módulo: Áreas Comuns, Reservas e Cobranças') !== false) {
        file_put_contents('writable/extracted_prompt.txt', $data['content']);
        echo "Found and written to writable/extracted_prompt.txt\n";
        break;
    }
}
fclose($handle);
