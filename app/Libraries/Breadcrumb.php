<?php

namespace App\Libraries;

class Breadcrumb {

    protected $breadcrumbs = [];

    public function add(string $title, string $url = null) {
        $this->breadcrumbs[] = [
            'title' => $title,
            'url' => $url
        ];
        return $this;
    }

    public function render(): string {
        $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        $lastIndex = count($this->breadcrumbs) - 1;

        foreach ($this->breadcrumbs as $index => $crumb) {
            if ($index === $lastIndex || empty($crumb['url'])) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">' . esc($crumb['title']) . '</li>';
            } else {
                $html .= '<li class="breadcrumb-item"><a href="' . esc($crumb['url']) . '">' . esc($crumb['title']) . '</a></li>';
            }
        }

        $html .= '</ol></nav>';
        return $html;
    }

    public function get(): array {
        return $this->breadcrumbs;
    }
}
