<?php

namespace Seat\Web\Http\Composers;


use Illuminate\View\View;

class CharacterFilter
{
    public function compose(View $view)
    {
        $rules = config("web.characterfilter");

        // work with raw arrays since the filter code requires an array of objects, and laravel collections don't like to give us that
        $newrules = [];
        foreach ($rules as $rule) {
            if(is_string($rule['src'])){
                $rule['src'] = route($rule['src']);
            }

            $newrules[] = (object) $rule;

        }

        $view->with("characterFilterRules", $newrules);
    }
}