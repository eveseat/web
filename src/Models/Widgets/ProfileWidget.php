<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Models\Widgets;


class ProfileWidget implements IWidget
{

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $footer;

    /**
     * @var string
     */
    private $body_template;

    /**
     * @var null
     */
    private $dataset;

    /**
     * ProfileWidget constructor.
     * @param string $title
     * @param string $body_template
     * @param null $dataset
     * @param string|null $footer
     */
    public function __construct(string $title, string $body_template, $dataset = null, string $footer = null)
    {
        $this->title = $title;
        $this->body_template = $body_template;
        $this->dataset = $dataset;
        $this->footer = $footer;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getFooter(): ?string
    {
        return $this->footer;
    }

    /**
     * @return string
     */
    public function getBodyTemplate(): string
    {
        return $this->body_template;
    }

    /**
     * @return null
     */
    public function getDataset()
    {
        return $this->dataset;
    }
}
