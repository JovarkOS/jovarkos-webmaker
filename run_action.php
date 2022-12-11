<?php

session_start();

// var_export($_SESSION);

$id = $_SESSION['id'];
$session_dir = __DIR__ . $id;


// Create post request to github actions workflow_dispatch API endpoint using shell_exec()

$command = <<<TEXT
curl \
  -X POST \
  -H "Accept: application/vnd.github+json" \
  -H "Authorization: Bearer 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"\
  -H "X-GitHub-Api-Version: 2022-11-28" \
  https://api.github.com/repos/jovarkos/jovarkos-webmaker/actions/workflows/build_system.yml/dispatches \
  -d '{"ref":"main","inputs":{"sessionID":"$id","zip_location":"http://155.138.220.59/jovarkos-webmaker/$session_dir/archlive.zip"}}';
TEXT;


shell_exec($command);