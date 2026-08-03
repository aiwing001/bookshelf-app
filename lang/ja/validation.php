<?php

return [
    'required' => ':attributeを入力してください。',
    'string' => ':attributeは文字列で入力してください。',
    'email' => ':attributeは正しいメールアドレス形式で入力してください。',
    'confirmed' => ':attributeが確認用の入力と一致しません。',
    'unique' => 'この:attributeはすでに使用されています。',

    'min' => [
        'string' => ':attributeは:min文字以上で入力してください。',
    ],

    'max' => [
        'string' => ':attributeは:max文字以内で入力してください。',
    ],

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => '確認用パスワード',
    ],
];