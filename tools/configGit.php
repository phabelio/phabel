<?php

$user = "41898282+github-actions[bot]@users.noreply.github.com";
\passthru("git config --local user.email ".\escapeshellarg($user));
\passthru("git config --local user.name ".\escapeshellarg("Github Actions"));
