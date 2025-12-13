<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lignes de langage de validation
    |--------------------------------------------------------------------------
    |
    | Les lignes de langage suivantes contiennent les messages d'erreur par
    | défaut utilisés par la classe de validation de Laravel. Certains de
    | ces messages ont plusieurs variantes (comme les règles size).
    |
    */

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'active_url'           => 'Le champ :attribute n’est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'alpha'                => 'Le champ :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
    'alpha_num'            => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'before'               => 'Le champ :attribute doit être une date antérieure au :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale au :date.',

    'between'              => [
        'numeric' => 'La valeur de :attribute doit être comprise entre :min et :max.',
        'file'    => 'La taille du fichier :attribute doit être comprise entre :min et :max kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le tableau :attribute doit contenir entre :min et :max éléments.',
    ],

    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation du champ :attribute ne correspond pas.',
    'date'                 => 'Le champ :attribute n’est pas une date valide.',
    'date_equals'          => 'Le champ :attribute doit être une date égale au :date.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit contenir :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'email'                => 'Le champ :attribute doit être une adresse email valide.',
    'exists'               => 'Le champ :attribute sélectionné est invalide.',
    'file'                 => 'Le champ :attribute doit être un fichier.',
    'image'                => 'Le champ :attribute doit être une image (JPG, PNG, etc.).',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',

    'gt'                   => [
        'numeric' => 'La valeur de :attribute doit être supérieure à :value.',
        'file'    => 'La taille du fichier :attribute doit être supérieure à :value kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir plus de :value caractères.',
        'array'   => 'Le tableau :attribute doit contenir plus de :value éléments.',
    ],

    'gte'                  => [
        'numeric' => 'La valeur de :attribute doit être supérieure ou égale à :value.',
        'file'    => 'La taille du fichier :attribute doit être supérieure ou égale à :value kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir au moins :value caractères.',
        'array'   => 'Le tableau :attribute doit contenir au moins :value éléments.',
    ],

    'in'                   => 'Le champ :attribute sélectionné est invalide.',
    'integer'              => 'Le champ :attribute doit être un entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'                 => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'                 => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne JSON valide.',

    'lt'                   => [
        'numeric' => 'La valeur de :attribute doit être inférieure à :value.',
        'file'    => 'La taille du fichier :attribute doit être inférieure à :value kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir moins de :value caractères.',
        'array'   => 'Le tableau :attribute doit contenir moins de :value éléments.',
    ],

    'lte'                  => [
        'numeric' => 'La valeur de :attribute doit être inférieure ou égale à :value.',
        'file'    => 'La taille du fichier :attribute doit être inférieure ou égale à :value kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir au plus :value caractères.',
        'array'   => 'Le tableau :attribute ne doit pas contenir plus de :value éléments.',
    ],

    'max'                  => [
        'numeric' => 'La valeur de :attribute ne doit pas dépasser :max.',
        'file'    => 'La taille du fichier :attribute ne doit pas dépasser :max kilo-octets.',
        'string'  => 'Le texte de :attribute ne doit pas dépasser :max caractères.',
        'array'   => 'Le tableau :attribute ne doit pas contenir plus de :max éléments.',
    ],

    'mimes'                => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'            => 'Le champ :attribute doit être un fichier de type : :values.',

    'min'                  => [
        'numeric' => 'La valeur de :attribute doit être au moins :min.',
        'file'    => 'La taille du fichier :attribute doit être d’au moins :min kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le tableau :attribute doit contenir au moins :min éléments.',
    ],

    'not_in'               => 'Le champ :attribute sélectionné est invalide.',
    'not_regex'            => 'Le format du champ :attribute est invalide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'present'              => 'Le champ :attribute doit être présent.',
    'regex'                => 'Le format du champ :attribute est invalide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_if'          => 'Le champ :attribute est obligatoire lorsque :other a la valeur :value.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'Le champ :attribute est obligatoire lorsque :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire lorsque :values sont présents.',
    'required_without'     => 'Le champ :attribute est obligatoire lorsque :values n’est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire lorsqu’aucune des valeurs :values n’est présente.',
    'same'                 => 'Les champs :attribute et :other doivent correspondre.',

    'size'                 => [
        'numeric' => 'La valeur de :attribute doit être :size.',
        'file'    => 'La taille du fichier :attribute doit être de :size kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir :size caractères.',
        'array'   => 'Le tableau :attribute doit contenir :size éléments.',
    ],

    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'             => 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'               => 'La valeur du champ :attribute est déjà utilisée.',
    'uploaded'             => 'Le fichier :attribute n’a pas pu être téléversé.',
    'url'                  => 'Le format de l’URL :attribute est invalide.',
    'uuid'                 => 'Le champ :attribute doit être un UUID valide.',

    /*
    |--------------------------------------------------------------------------
    | Messages de validation personnalisés pour des attributs spécifiques
    |--------------------------------------------------------------------------
    |
    */

    'custom' => [
        'email' => [
            'unique' => 'Cette adresse email est déjà utilisée.',
        ],
        'password' => [
            'confirmed' => 'Les mots de passe ne correspondent pas.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Noms d'attributs personnalisés
    |--------------------------------------------------------------------------
    |
    | Ces lignes de langage permettent de remplacer les noms d’attributs
    | par quelque chose de plus lisible pour l’utilisateur final.
    |
    */

    'attributes' => [
        'name'              => 'nom complet',
        'first_name'        => 'prénom',
        'last_name'         => 'nom de famille',
        'email'             => 'adresse email',
        'password'          => 'mot de passe',
        'password_confirmation' => 'confirmation du mot de passe',
        'service'           => 'service',
        'delegation_id'     => 'délégation',
        'title'             => 'titre',
        'description'       => 'description',
        'start_at'          => 'date de début',
        'end_at'            => 'date de fin',
        'room_id'           => 'salle',
        'meeting_type_id'   => 'type de réunion',
        'document_type_id'  => 'type de document',
        'file'              => 'fichier',
        'status'            => 'statut',
        'photo'             => 'photo',
        'head_of_delegation_photo' => 'photo du chef de délégation',
    ],

];







