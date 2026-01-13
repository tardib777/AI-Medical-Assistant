<?php

namespace App\Services;

class TextCleanService
{
    public function clean(string $rawText): string
    {
        $text = $rawText;

        // 1️⃣ Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // 2️⃣ Remove markdown bullets (* )
        $text = preg_replace('/^\s*\*\s+/mu', '', $text);

        // 3️⃣ If a sentence ends with "." and followed by new lines → merge
        // "." + any spaces + \n+ + spaces → ". "
        $text = preg_replace('/\.\s*\n+\s*/u', '. ', $text);

        // 4️⃣ Replace any remaining double newlines with single newline
        $text = preg_replace("/\n{2,}/u", "\n", $text);

        // 5️⃣ Replace any remaining single newline with TWO spaces
        $text = preg_replace("/\n/u", '  ', $text);

        // 6️⃣ Normalize spaces
        $text = preg_replace('/\s{3,}/u', '  ', $text);

        // 7️⃣ Fix spacing after punctuation
        $text = preg_replace('/([،,.!?])([^\s])/u', '$1 $2', $text);

        return trim($text);
    }
}
