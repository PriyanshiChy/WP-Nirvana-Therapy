<?php
function template(string $format, mixed ...$partialArgs): callable
{
  return function (mixed ...$moreArgs) use ($format, $partialArgs): string {
    $allArgs = array_merge([$format], $partialArgs, $moreArgs);
    return sprintf(...$allArgs);
  };
}
?>