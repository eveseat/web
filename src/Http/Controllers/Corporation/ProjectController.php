<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\CorporationProjects\CorporationProject;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Financial\ProjectDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class StructureController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ProjectController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Corporation\Military\StructureDataTable  $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProjects(CorporationInfo $corporation, ProjectDataTable $dataTable)
    {

        return $dataTable->addScope(new CorporationScope('corporation.projects', [$corporation->corporation_id]))
            ->render('web::corporation.projects.list', compact('corporation'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  string  $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProject(CorporationInfo $corporation, string $project_id)
    {
        $project = CorporationProject::with('contributors', 'creator')
            ->where('id', $project_id)
            ->first();
        $config = $this->normalizeProjectConfiguration($project->configuration);

        return view('web::corporation.projects.modals.content', compact('corporation', 'project', 'config'));
    }

    /**
     * Normalize project configuration into an array of items:
     * [ ['key'=>'...', 'label'=>'...', 'value'=>'...', 'description'=>null], ... ]
     */
    protected function normalizeProjectConfiguration($configuration): array
    {
        if (is_string($configuration)) {
            // sometimes stored as JSON string
            $decoded = json_decode($configuration, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $configuration = $decoded;
            }
        }

        // If null or empty
        if (empty($configuration)) {
            return [];
        }

        $items = [];

        // Case: configuration is an array of option objects
        if (is_array($configuration) && array_values($configuration) === $configuration) {
            foreach ($configuration as $i => $opt) {
                if (is_array($opt)) {
                    $items[] = [
                        'key' => $opt['name'] ?? "option_{$i}",
                        'label' => $opt['display_name'] ?? $opt['name'] ?? "Option {$i}",
                        'value' => isset($opt['value']) ? $opt['value'] : (isset($opt['default']) ? $opt['default'] : null),
                        'description' => $opt['description'] ?? null,
                    ];
                } else {
                    // primitive in array
                    $items[] = [
                        'key' => "option_{$i}",
                        'label' => "Option {$i}",
                        'value' => (string) $opt,
                        'description' => null,
                    ];
                }
            }

            return $items;
        }

        // Case: configuration is an associative object/array of named options
        if (is_array($configuration)) {
            foreach ($configuration as $k => $v) {
                if (is_array($v) || is_object($v)) {
                    $vArr = (array) $v;
                    $items[] = [
                        'key' => $k,
                        'label' => $vArr['label'] ?? $vArr['name'] ?? $k,
                        'value' => $vArr['value'] ?? $vArr['default'] ?? json_encode($vArr),
                        'description' => $vArr['description'] ?? null,
                    ];
                } else {
                    $items[] = [
                        'key' => $k,
                        'label' => $k,
                        'value' => $v,
                        'description' => null,
                    ];
                }
            }

            return $items;
        }

        // Case: object (stdClass)
        if (is_object($configuration)) {
            $arr = (array) $configuration;
            foreach ($arr as $k => $v) {
                if (is_object($v) || is_array($v)) {
                    $vArr = (array) $v;
                    $items[] = [
                        'key' => $k,
                        'label' => $vArr['label'] ?? $vArr['name'] ?? $k,
                        'value' => $vArr['value'] ?? $vArr['default'] ?? json_encode($vArr),
                        'description' => $vArr['description'] ?? null,
                    ];
                } else {
                    $items[] = [
                        'key' => $k,
                        'label' => $k,
                        'value' => $v,
                        'description' => null,
                    ];
                }
            }

            return $items;
        }

        // Fallback: stringify whatever it is
        return [
            [
                'key' => 'raw',
                'label' => 'Configuration',
                'value' => is_scalar($configuration) ? (string) $configuration : json_encode($configuration),
                'description' => null,
            ],
        ];
    }
}
