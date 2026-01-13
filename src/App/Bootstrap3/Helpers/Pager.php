<?php

namespace App\Bootstrap3\Helpers;

use App\Strings;
use App\Views\BaseView;
use App\Views\ViewInterface;

class Pager extends BaseView implements ViewInterface
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $per_page;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $pages_count;

    /**
     * @var int
     */
    protected $current_page;

    /**
     * @var bool
     */
    protected $first_page;

    /**
     * @var bool
     */
    protected $last_page;

    /**
     * @var string
     */
    protected $fist_page_url;

    /**
     * @var string
     */
    protected $prev_page_url;

    /**
     * @var string
     */
    protected $next_page_url;

    /**
     * @var string
     */
    protected $last_page_url;

    /**
     * @var string|null
     */
    protected $link_class;

    /**
     * @var string
     */
    private $prefix;

    /**
     * Pager constructor.
     * @param $offset int Смещение относительно количества данных
     * @param $per_page int Количество элементов на одной странице
     * @param $count int Общее количество элементов
     * @param $prefix string Префикс URL для страницы
     * @param $link_class string Класс CSS добавляемый к элементам
     */
    public function __construct($offset, $per_page, $count, $prefix = null, $link_class = null)
    {
        $this->offset = $offset;
        $this->per_page = $per_page;
        $this->count = $count;
        $this->link_class = $link_class;
        $this->prefix = $prefix;

        $this->pages_count = ceil($count / $per_page);
        $this->current_page = ceil($offset / $per_page) + 1;
        $this->first_page = $offset == 0;
        $this->last_page = ($offset + $per_page) >= $count;

        $this->fist_page_url = Strings::http_build_query_replace("offset", 0);
        $this->prev_page_url = Strings::http_build_query_replace("offset", (!$this->first_page ? ($this->current_page - 2) : 0) * $this->per_page);
        $this->next_page_url = Strings::http_build_query_replace("offset", ($this->current_page + (!$this->last_page ? 0 : -1)) * $this->per_page);
        $this->last_page_url = Strings::http_build_query_replace("offset", ($this->pages_count-1) * $this->per_page);
    }

    /**
     * @return int
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset(int $offset): Pager
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage(): ?int
    {
        return $this->per_page;
    }

    /**
     * @param int $per_page
     * @return $this
     */
    public function setPerPage(int $per_page): Pager
    {
        $this->per_page = $per_page;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count): Pager
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getPagesCount(): ?int
    {
        return $this->pages_count;
    }

    /**
     * @param int $pages_count
     * @return $this
     */
    public function setPagesCount(int $pages_count): Pager
    {
        $this->pages_count = $pages_count;
        return $this;
    }

    /**
     * @return void
     */
    public function out()
    {
        if ($this->current_page < (7 / 2)) {

            $increment = [1, min(6, $this->pages_count)];

        } elseif ($this->current_page > ($this->pages_count - (7 / 2))) {

            $increment = [max(1,$this->pages_count - 5), $this->pages_count];

        } else {

            $increment = [max(1, $this->current_page - 2), min($this->current_page + 3, $this->pages_count)];
        }
        ?>

        <ul class="pager">
            <li class="previous<?php print ($this->first_page ? " disabled" : null);?>">
                <a class="<?php print $this->link_class ?: null;?>" href="<?php print $this->prefix;?>?<?php print $this->fist_page_url;?>">← В начало</a>
            </li>

            <li class="<?php print ($this->first_page ? " disabled" : null);?>">
                <a class="<?php print $this->link_class ?: null;?>" href="<?php print $this->prefix;?>?<?php print $this->prev_page_url;?>">Предыдущая</a>
            </li>

            <?php if ($increment[0] > 1):?>
                <li class="disabled">...</li>
            <?php endif;?>

            <?php for ($i = $increment[0]; $i <= $increment[1]; $i++):?>
                <li class="<?php print ($this->current_page == $i ? " active" : null);?>">
                    <a class="<?php print $this->link_class ?: null;?>" href="<?php print $this->prefix;?>?<?php print Strings::http_build_query_replace("offset", ($i - 1) * $this->per_page);?>"><?php print $i;?></a>
                </li>
            <?php endfor;?>

            <?php if ($increment[1] < $this->pages_count):?>
                <li class="disabled"><?php if ($increment[0] > 1):?>...<?php endif;?></li>
            <?php endif;?>

            <li class="<?php print ($this->last_page ? " disabled" : null);?>">
                <a class="<?php print $this->link_class ?: null;?>" href="<?php print $this->prefix;?>?<?php print $this->next_page_url;?>">Следующая</a>
            </li>

            <li class="next<?php print ($this->last_page ? " disabled" : null);?>">
                <a class="<?php print $this->link_class ?: null;?>" href="<?php print $this->prefix;?>?<?php print $this->last_page_url;?>">В конец →</a>
            </li>
        </ul>
        <?php
    }
}



