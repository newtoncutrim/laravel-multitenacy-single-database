@props(['title' => null, 'wide' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, Helvetica, sans-serif;
            --page: #f5f7f8;
            --surface: #ffffff;
            --text: #1f2933;
            --muted: #667085;
            --border: #d7dee4;
            --primary: #0f8b8d;
            --primary-dark: #0b6567;
            --danger: #b42318;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--page);
            color: var(--text);
        }

        a {
            color: var(--primary-dark);
            font-weight: 700;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .auth-box {
            width: min(100%, 440px);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 28px;
            box-shadow: 0 18px 45px rgba(31, 41, 51, 0.08);
        }

        .auth-box-wide {
            width: min(100%, 760px);
        }

        .header {
            margin-bottom: 24px;
        }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary-dark);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
            line-height: 1.2;
        }

        .description {
            margin: 10px 0 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 0.95rem;
            font-weight: 700;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            min-height: 44px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            color: var(--text);
            background: #ffffff;
            font: inherit;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input:focus {
            border-color: var(--primary);
            outline: 3px solid rgba(15, 139, 141, 0.18);
        }

        textarea:focus {
            border-color: var(--primary);
            outline: 3px solid rgba(15, 139, 141, 0.18);
        }

        .error {
            margin: 7px 0 0;
            color: var(--danger);
            font-size: 0.9rem;
        }

        .remember {
            display: flex;
            gap: 8px;
            align-items: center;
            margin: 4px 0 18px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .remember input {
            width: 16px;
            height: 16px;
        }

        .button {
            width: 100%;
            min-height: 44px;
            border: 0;
            border-radius: 8px;
            background: var(--primary);
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
        }

        .button:hover {
            background: var(--primary-dark);
        }

        .switch {
            margin: 20px 0 0;
            color: var(--muted);
            text-align: center;
        }

        .top-link {
            display: inline-block;
            margin-bottom: 18px;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .actions .top-link {
            margin-bottom: 0;
        }

        .posts {
            display: grid;
            gap: 14px;
            margin-top: 24px;
        }

        .post {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            background: #ffffff;
        }

        .post h2 {
            margin: 0 0 8px;
            font-size: 1.1rem;
        }

        .post p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .post-meta {
            margin-top: 12px;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .button-secondary {
            width: auto;
            min-height: 36px;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #ffffff;
            color: var(--danger);
            cursor: pointer;
            font: inherit;
            font-weight: 700;
        }

        .button-secondary:hover {
            border-color: var(--danger);
        }

        .status {
            margin: 0 0 16px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #e7f6f2;
            color: #155e4b;
        }

        .empty {
            margin: 20px 0 0;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 480px) {
            .auth-box {
                padding: 22px;
            }

            h1 {
                font-size: 1.55rem;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="auth-box @if ($wide) auth-box-wide @endif">
            {{ $slot }}
        </section>
    </main>
</body>
</html>
