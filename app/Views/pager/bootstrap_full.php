<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- First Page -->
        <li class="page-item <?= $pager->getCurrentPageNumber() === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="First">
                <span aria-hidden="true">&laquo;&laquo; First</span>
            </a>
        </li>

        <!-- Previous Page -->
        <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getPreviousPage() ?? '#' ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>

        <!-- Numbered Pages -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <?php if ($link['active']) : ?>
                    <span class="page-link"><?= $link['title'] ?></span>
                <?php else : ?>
                    <a class="page-link" href="<?= $link['uri'] ?>">
                        <?= $link['title'] ?>
                    </a>
                <?php endif ?>
            </li>
        <?php endforeach ?>

        <!-- Next Page -->
        <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getNextPage() ?? '#' ?>" aria-label="Next">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>

        <!-- Last Page -->
        <li class="page-item <?= $pager->getCurrentPageNumber() === $pager->getPageCount() ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Last">
                <span aria-hidden="true">Last &raquo;&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
