<?php
    $prev = $paginator->getPrevious();
    $next = $paginator->getNext();
    $slug = e($paginator->getSlug());
    $queryParams = e($paginator->getQueryParams());
?>
<div>
    <?php if($prev !== false) { ?>
    <a href="<?= $slug . '1' . $queryParams ?>">First</a>
    <a href="<?= $slug . $prev . $queryParams ?>">Previous</a>
    <?php } ?>

    <?php foreach($paginator->getRange() as $num) { ?>
    <a href="<?= $slug . $num . $queryParams ?>"<?= $num == $paginator->currentPage ? ' aria-current="page"' : '' ?>><?= $num ?></a>
    <?php } ?>

    <?php if($next !== false) { ?>
    <a href="<?= $slug . $next . $queryParams ?>">Next</a>
    <a href="<?= $slug . $paginator->getLast() . $queryParams ?>">Last</a>
    <?php } ?>
</div>
