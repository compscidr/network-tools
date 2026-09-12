<?php
/** Full commit hash of the checkout whose .git dir is mounted at $gitDir, or "" if unavailable. */
function gitCommit(string $gitDir = '/git'): string {
  $head = trim((string)@file_get_contents("$gitDir/HEAD"));
  if (!str_starts_with($head, 'ref: ')) {
    return $head;
  }
  $ref = substr($head, 5);
  $loose = trim((string)@file_get_contents("$gitDir/$ref"));
  if ($loose !== '') {
    return $loose;
  }
  foreach (file("$gitDir/packed-refs", FILE_IGNORE_NEW_LINES) ?: [] as $line) {
    if (str_ends_with($line, " $ref")) {
      return explode(' ', $line)[0];
    }
  }
  return '';
}

function showFooter(string $gitDir = '/git') {
  $commit = gitCommit($gitDir);
  ?>
      <footer class="row pt-5 pb-3 text-center small text-muted">
        <p>Open source on <a href="https://github.com/compscidr/network-tools">GitHub</a><?php if ($commit) { ?>
          (<a href="https://github.com/compscidr/network-tools/commit/<?php echo $commit; ?>"><?php echo substr($commit, 0, 7); ?></a>)<?php } ?> &middot;
          <a href="https://www.ping4.network">ping4.network</a> &middot;
          <a href="https://www.ping6.network">ping6.network</a> &middot;
          <a href="https://www.dumpers.xyz">dumpers.xyz</a></p>
      </footer>
<?php
}
