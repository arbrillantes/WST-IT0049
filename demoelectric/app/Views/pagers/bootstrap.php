<?php $pager->setSurroundCount(2); ?>
<nav aria-label="Customer account pages">
    <ul class="pagination flex-wrap">
        <?php if ($pager->hasPreviousPage()): ?>
            <li class="page-item"><a class="page-link" href="<?= esc($pager->getFirst()) ?>" aria-label="First page">First</a></li>
            <li class="page-item"><a class="page-link" href="<?= esc($pager->getPreviousPage()) ?>" aria-label="Previous page">Previous</a></li>
        <?php endif; ?>
        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= esc($link['uri']) ?>" <?= $link['active'] ? 'aria-current="page"' : '' ?>><?= esc($link['title']) ?></a>
            </li>
        <?php endforeach; ?>
        <?php if ($pager->hasNextPage()): ?>
            <li class="page-item"><a class="page-link" href="<?= esc($pager->getNextPage()) ?>" aria-label="Next page">Next</a></li>
            <li class="page-item"><a class="page-link" href="<?= esc($pager->getLast()) ?>" aria-label="Last page">Last</a></li>
        <?php endif; ?>
    </ul>
</nav>
