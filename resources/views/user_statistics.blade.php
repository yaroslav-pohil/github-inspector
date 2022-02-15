<html>
<head>
    <title>Code review statistics</title>
    <link rel="alternate icon" class="js-site-favicon" type="image/png" href="https://github.githubassets.com/favicons/favicon.png">
</head>

<body>
<h1>PRs statistics</h1>

<ul>
    @foreach($users as $user)
        <li>
            {{ $user }}:
            <ul>
                <li>
                    Your PRs that are waiting for code review - {{ count($openedPrs[$user] ?? []) }}
                    @foreach($openedPrs[$user] ?? [] as $link)
                        <br>- <a target="_blank" href="{{ $link }}">{{ $link }}</a>
                    @endforeach
                </li>
                <li>
                    PRs waiting for your code review - {{ count($codeReviewDebts[$user] ?? []) }}:
                    @foreach($codeReviewDebts[$user] ?? [] as $link)
                        <br>- <a target="_blank" href="{{ $link }}">{{ $link }}</a>
                    @endforeach
                </li>
            </ul>

        </li>
    @endforeach
</ul>
</body>

</html>


