<div>
    <?php
        $prev = $paginator->getPrevious();
        if($prev !== false) {
        ?>
    <a href="<?= $paginator->getSlug().'1'.$paginator->getQueryParams() ?>">
        First
    </a>
        <?php
        }
        if($prev !== false) {
    ?>
    <a href="<?= $paginator->getSlug().$prev.$paginator->getQueryParams() ?>">
        Previous
    </a>
    <?php }
    foreach($paginator->getRange() as $num) {
        if($num == $paginator->currentPage) {
    ?>
        <a href="<?= $paginator->getSlug().$num.$paginator->getQueryParams() ?>"><?= $num ?></a>
        <?php } else { ?>
        <a href="<?= $paginator->getSlug().$num.$paginator->getQueryParams() ?>"><?= $num ?></a>
    <?php
        }
    }
        $next = $paginator->getNext();
        if($next !== false) {
    ?>
    <a href="<?= $paginator->getSlug().$paginator->getNext().$paginator->getQueryParams() ?>">
        Next
    </a>
    <?php } if($paginator->getTotalPages() > 1 && $next !== false) { ?>
    <a href="<?= $paginator->getSlug().$paginator->getTotalPages().$paginator->getQueryParams() ?>">
        Previous
    </a>
    <?php } ?>
</div>