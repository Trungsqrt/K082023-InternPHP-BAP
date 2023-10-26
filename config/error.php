<?php

return [
    'user_does_not_exist' => [
        'code' => 429,
        'message' => 'メールアドレス、 またはパスワードが正しく ありません。',
    ],
    'password_is_incorrect' => [
        'code' => 400,
        'message' => 'メールアドレス、 またはパスワードが正しく ありません。',
    ],
    'seminar_ended' => [
        'code' => 400,
        'message' => '現在セミナーの開催予定はありません。',
    ],
    'bad_request' => [
        'code' => 400,
        'message' => 'bad_request',
    ],
    'unauthenticated' => [
        'code' => 401,
        'message' => 'unauthenticated',
    ],
    'access_denied' => [
        'code' => 403,
        'message' => 'access_denied',
    ],
    'server_error' => [
        'code' => 500,
        'message' => 'システムエラーが発生しました。',
    ],
    'email_format' => [
        'code' => 424,
        'message' => 'メールアドレスの書式が正しくありません。',
    ],
    'authentication' => [
        'code' => 429,
        'message' => 'メールアドレス、またはパスワードが正しくありません。',
    ],
    'seminar_notfound' => [
        'code' => 412,
        'message' => '現在セミナーの開催予定はありません。',
    ],
    'seminar_publication_period' => [
        'code' => 408,
        'message' => 'このセミナーは終了しました。',
    ],
    'invalid_user_agent' => [
        'code' => 400,
        'message' => 'invalid_user_agent',
    ],
    'missing_parameters' => [
        'code' => 400,
        'message' => 'missing_parameters',
    ],

    'field_required' => [
        'password_field_required' => [
            'code' => 403,
            'message' => 'passwordを入力してください。',
        ],
        'lname_field_required' => [
            'code' => 403,
            'message' => 'last_nameを入力してください。',
        ],
        'fname_field_required' => [
            'code' => 403,
            'message' => 'first_nameを入力してください。',
        ],
        'password_field_required' => [
            'code' => 403,
            'message' => 'password_confirmを入力してください。',
        ],
    ],
    'field_restricted' => [
        'password_field_restricted' => [
            'code' => 422,
            'message' => 'パスワードは8文字以上で、半角の英大文字・英小文字・数字・記号それぞれを最低1文字ずつ含めてください。',
        ]
    ],
    'email_exist' => [
        'code' => 425,
        'message' => 'このメールアドレスは既に使用されています。他のメールアドレスをご使用ください。',
    ],
    'password_confirm_invalid' => [
        'code' => 433,
        'message' => 'パスワードが一致しません。',
    ],
    'route_not_found' => [
        'code' => 404,
        'message' => 'ルートが見つかりません。',
    ]



];
