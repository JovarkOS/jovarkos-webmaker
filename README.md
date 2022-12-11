
# <center>You've seen JovarkOS,</center>

But what about the process of making it? Take
*open source* to a new level and customize your
own ISO built on Arch just like JovarkOS is!

DEV NOTES:

<https://docs.github.com/en/actions/managing-workflow-runs/manually-running-a-workflow>

```sh
curl \
  -X POST \
  -H "Accept: application/vnd.github+json" \
  -H "Authorization: Bearer <YOUR-TOKEN>"\
  -H "X-GitHub-Api-Version: 2022-11-28" \
  https://api.github.com/repos/OWNER/REPO/actions/workflows/build_system.yml/dispatches \
  -d '{"ref":"topic-branch","inputs":{"sessionID":" ","zip_archlive":"San Francisco, CA"}}'
```
