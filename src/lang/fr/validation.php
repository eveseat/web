<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => "Le champ :attribute doit �tre accept�.",
    'active_url'           => "Le champ :attribute n'est pas une URL valide.",
    'after'                => "Le champ :attribute doit �tre une date post�rieure au :date.",
    'alpha'                => "Le champ :attribute doit contenir uniquement des lettres.",
    'alpha_dash'           => "Le champ :attribute doit contenir uniquement des lettres, des chiffres et des tirets.",
    'alpha_num'            => "Le champ :attribute doit contenir uniquement des lettres et des chiffres.",
    'array'                => "Le champ :attribute doit �tre un tableau.",
    'before'               => "Le champ :attribute doit �tre une date ant�rieure au :date.",
    'between'              => [
        'numeric' => "La valeur de :attribute doit �tre comprise entre :min et :max.",
        'file'    => "Le fichier :attribute doit avoir une taille comprise entre :min et :max kilo-octets.",
        'string'  => "Le texte dans :attribute doit avoir une taille comprise entre :min et :max caract�res.",
        'array'   => "Le tableau :attribute doit avoir entre :min et :max �l�ments.",
    ],
    'boolean'              => "Le champ :attribute doit �tre vrai ou faux.",
    'confirmed'            => "Le champ de confirmation :attribute ne correspond pas.",
    'date'                 => "La valeur du champ :attribute n'est pas une date valide.",
    'date_format'          => "La valeur du champ :attribute ne correspond pas au format :format.",
    'different'            => "Les champs :attribute et :other doivent �tre diff�rents.",
    'digits'               => "Le champ :attribute doit avoir :digits chiffres.",
    'digits_between'       => "Le champ :attribute doit avoir entre :min et :max chiffres.",
    'email'                => "Le champ :attribute doit �tre une adresse mail valide.",
    'exists'               => "Le champ :attribute s�l�ctionn� n'est pas valide.",
    'filled'               => "Le champ :attribute est obligatoire.",
    'image'                => "Le champ :attribute doit �tre une image.",
    'in'                   => "Le champ :attribute n'est pas valide.",
    'integer'              => "La valeur du champ :attribute doit �tre un entier.",
    'ip'                   => "La valeur du champ :attribute doit �tre une adresse IP valide.",
    'json'                 => "La valeur du champ :attribute doit �tre une cha�ne JSON valide.",
    'max'                  => [
        'numeric' => "La valeur de :attribute ne doit pas d�passer :max.",
        'file'    => "Le fichier :attribute ne doit pas d�passer :max kilo-octets.",
        'string'  => "La longueur de :attribute ne doit pas d�passer :max caract�res.",
        'array'   => "Le tableau :attribute ne doit pas avoir plus de :max �l�ments.",
    ],
    'mimes'                => "Le champ :attribute doit �tre un fichier de type : :values.",
    'min'                  => [
        'numeric' => "La valeur de :attribute doit �tre sup�rieure � :min.",
        'file'    => "Le fichier :attribute doit faire au moins :min kilo-octets.",
        'string'  => "La longueur de :attribute doit �tre d'au moins :min caract�res.",
        'array'   => "Le tableau :attribute doit avoir au moins :min �l�ments.",
    ],
    'not_in'               => "Le champ :attribute s�l�ctionn� n'est pas valide.",
    'numeric'              => "Le champ :attribute doit contenir un nombre.",
    'regex'                => "Le format du champ :attribute n'est pas valide.",
    'required'             => "Le champ :attribute est obligatoire.",
    'required_if'          => "Le champ :attribute est obligatoire lorsque la valeur de :other est :value.",
    'required_with'        => "Le champ :attribute est obligatoire lorsque :values est pr�sent.",
    'required_with_all'    => "Le champ :attribute est obligatoire lorsque :values sont pr�sent.",
    'required_without'     => "Le champ :attribute est obligatoire lorsque :values n'est pas pr�sent.",
    'required_without_all' => "Le champ :attribute est obligatoire lorsqu'aucune des valeurs :values n'est pr�sente.",
    'same'                 => "Les champs :attribute et :other doivent �tre identiques.",
    'size'                 => [
        'numeric' => "La valeur de :attribute doit �tre :size.",
        'file'    => "La taille du fichier :attribute doit-�tre de :size kilo-octets.",
        'string'  => "Le cha�ne :attribute doit avoir :size caract�res.",
        'array'   => "Le tableau :attribute doit contenir :size �l�ments.",
    ],
    'string'               => "Le champ :attribute doit �tre une cha�ne de caract�res.",
    'timezone'             => "Le champ :attribute doit �tre un fuseau horaire valide.",
    'unique'               => "La valeur de :attribute est d�j� utilis�e.",
    'url'                  => "Le format de l'URL :attribute n'est pas valide.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => "custom-message",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
