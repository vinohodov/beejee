<?php

namespace lib;

class Link
{
    /**
     * order
     * sorting
     * page
     */
    private $page;
    private $path;
    private $order;
    private $sorting;
    private $link = [];

    public function __construct(Request $request) {
        $this->page = $request->page ? $request->page : null;
        $this->path = $request->path ? $request->path : null;
        $this->order = $request->order ? $request->order : null;
        $this->sorting = $request->sorting ? $request->sorting : null;
    }

    public function pagination()
    {
        return $this->clear()->order()->sorting()->page(true)->getLink();
    }

    public function columnOrder($column)
    {
        $this->clear();
        if ($column == $this->order) {
            $this->page()->order()->sorting($this->toggleSorting());
        } else {
            $this->page()->order($column)->sorting();
        }
        return $this->getLink();
    }

    public function common() {
        return $this->clear()->order()->sorting()->page()->getLink();
    }

    private function getLink()
    {
        return '/?' . implode('&', $this->link);
    }

    private function order($column = null)
    {
        if ($column) $this->link[] = 'order=' . $column;
        elseif ($this->order) $this->link[] = 'order=' . $this->order;
        return $this;
    }

    private function sorting($sorting = null)
    {
        if ($sorting) $this->link[] = 'sorting=' . $sorting;
        elseif ($this->sorting) $this->link[] = 'sorting=' . $this->sorting;
        return $this;
    }

    private function page($empty = false)
    {
        if ($empty) $this->link[] = 'page=';
        elseif ($this->page) $this->link[] = 'page=' . $this->page;
        return $this;
    }

    private function path() {
        if ($this->path) $this->link[] = 'path=' . $this->path;
        return $this;
    }

    private function toggleSorting() {
        return $this->sorting == 'asc' ? 'desc' : 'asc';
    }

    private function clear() {
        $this->link = [];
        $this->path();
        return $this;
    }
}