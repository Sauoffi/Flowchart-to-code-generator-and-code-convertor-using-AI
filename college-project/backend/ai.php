<?php

function ImageContext($img_url) {
    $OPENAI_KEY = "sk-proj-9g9CrBC0UyfyGt95DkQ7BLmFKEqZARZ2UdN7dWEqZeKC-tfP3JT-TCNlKDBwqKTsKjt4qiGDFQT3BlbkFJAbXYmLZEwas8mY3f6F_X0HxINuyCuMCFR1q6w0546YWAlX1jc6um1GhQJrdO0H7RBoaUG_HSgA-a2I7RXPm6UXF_Ar0WR5K7F0wQhpJdsDvJd2fMntUgA"; // Replace with your actual OpenAI API key

    $url = "https://api.openai.com/v1/chat/completions";

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $OPENAI_KEY"
    ];

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            [
                "role" => "user",
                "content" => [
                    ["type" => "text", "text" => "What’s in this image? (If Image is blurred or unreadable please return IMAGE_UNPROCESSABLE)"],
                    ["type" => "image_url", "image_url" => ["url" => $img_url]]
                ]
            ]
        ],
        "max_tokens" => 500
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'];
    } else {
        return "FAILURE";
    }
}

function GenCode($ctx,$prmpt) {
    $OPENAI_KEY = "sk-proj-9g9CrBC0UyfyGt95DkQ7BLmFKEqZARZ2UdN7dWEqZeKC-tfP3JT-TCNlKDBwqKTsKjt4qiGDFQT3BlbkFJAbXYmLZEwas8mY3f6F_X0HxINuyCuMCFR1q6w0546YWAlX1jc6um1GhQJrdO0H7RBoaUG_HSgA-a2I7RXPm6UXF_Ar0WR5K7F0wQhpJdsDvJd2fMntUgA"; // Replace with your actual OpenAI API key

    $url = "https://api.openai.com/v1/chat/completions";

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $OPENAI_KEY"
    ];

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            [
                "role" => "system",
                "content" => [
                    ["type" => "text", "text" => "You are A python code assistant. you only reply in Python Code. User will provide Context and Guidence prompt to generate code. ( if you are unable to understand the code then Responsd FAILED)(Don't respond in MDX Just return the code no need mdx format)"]
                ]
                ],
            [
                "role" => "user",
                "content" => [
                    ["type" => "text", "text" => "Context: $ctx \n\nGuidence Prompt: $prmpt"]
                ]
            ]
        ],
        "max_tokens" => 800
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'];
    } else {
        return "FAILURE";
    }
}

$action = $_GET['action'];
if ($action == "ImageContext") {
    $img_url = $_GET['imgurl'];
    $result = ImageContext($img_url);
    echo $result;
} else if ($action == "GenCode") {
    $ctx = $_GET['ctx'];
    $prmpt = $_GET['prompt'];
    $result = GenCode($ctx,$prmpt);
    echo $result;
}

