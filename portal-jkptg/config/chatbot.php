<?php

return [
    'driver' => env('CHATBOT_DRIVER', 'canned'),

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-6'),
        'max_tokens' => 1024,
        'timeout' => 30,
        'usd_per_1k_input' => 0.003,
        'usd_per_1k_output' => 0.015,
        'usd_to_rm' => 4.7,
    ],

    'rate_limit' => [
        'per_ip_per_hour' => 10,
    ],

    'sanitizer' => [
        'max_length' => 2000,
        'banned_tokens' => [
            'system:', 'assistant:', '<|im_start|>', '<|im_end|>',
            '<|endoftext|>', '<|fim_prefix|>',
        ],
    ],

    'system_prompt' => [
        'ms' => 'Anda ialah pembantu maya rasmi Jabatan Ketua Pengarah Tanah dan Galian Persekutuan (JKPTG). Jawab dalam Bahasa Melayu rasmi. Tumpukan kepada perkhidmatan JKPTG: pengambilan tanah, pendaftaran hakmilik, pajakan negeri, royalti galian, ePerolehan tender. Jangan reka maklumat. Jika tidak pasti, cadangkan hubungi cawangan JKPTG terdekat. Sertakan rujukan akta jika berkaitan (Akta 486, KTN, dsb).',
        'en' => 'You are the official virtual assistant of the Federal Department of Director General of Lands and Mines (JKPTG). Reply in formal English. Focus on JKPTG services: land acquisition, title registration, state lease, mineral royalty, ePerolehan tenders. Do not fabricate. If unsure, suggest contacting the nearest JKPTG branch. Cite acts when relevant (Act 486, NLC, etc).',
    ],

    'history_window' => 6,
];
