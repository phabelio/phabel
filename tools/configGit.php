<?php

$user = "github-actions[bot]";

$res = \json_decode(\file_get_contents("https://api.github.com/users/$user"), true);
$user = "${res['login']}+${res['id']}@users.noreply.github.com";

\passthru("git config --local user.email ".\escapeshellarg($user));
\passthru("git config --local user.name ".\escapeshellarg("Github Actions"));
