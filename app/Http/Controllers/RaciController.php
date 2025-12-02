<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaciController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Affichage de la matrice RACI
     * Section 5.2 du cahier des charges
     */
    public function index()
    {
        $raciMatrix = [
            'Planification des réunions' => [
                'DSI' => 'R',
                'DRHMG' => 'C',
                'DCPRP (Protocole)' => 'A',
                'SG' => 'C',
                'Président' => 'I',
                'Participants' => 'I',
            ],
            'Gestion des documents' => [
                'DSI' => 'R',
                'DRHMG' => 'I',
                'DCPRP (Protocole)' => 'A',
                'SG' => 'C',
                'Président' => 'I',
                'Participants' => 'C',
            ],
            'Convocations et invitations' => [
                'DSI' => 'R',
                'DRHMG' => 'I',
                'DCPRP (Protocole)' => 'A',
                'SG' => 'C',
                'Président' => 'I',
                'Participants' => 'C',
            ],
            'Réservation des salles' => [
                'DSI' => 'C',
                'DRHMG' => 'R',
                'DCPRP (Protocole)' => 'C',
                'SG' => 'I',
                'Président' => 'I',
                'Participants' => 'I',
            ],
            'Validation institutionnelle (ODJ, PV, docs)' => [
                'DSI' => 'C',
                'DRHMG' => 'I',
                'DCPRP (Protocole)' => 'R',
                'SG' => 'A',
                'Président' => 'A',
                'Participants' => 'I',
            ],
            'Archivage et conformité' => [
                'DSI' => 'R',
                'DRHMG' => 'C',
                'DCPRP (Protocole)' => 'C',
                'SG' => 'A',
                'Président' => 'I',
                'Participants' => 'I',
            ],
            'Administration technique et sécurité' => [
                'DSI' => 'R',
                'DRHMG' => 'I',
                'DCPRP (Protocole)' => 'C',
                'SG' => 'I',
                'Président' => 'I',
                'Participants' => 'I',
            ],
            'Formation et support utilisateurs' => [
                'DSI' => 'R',
                'DRHMG' => 'I',
                'DCPRP (Protocole)' => 'C',
                'SG' => 'A',
                'Président' => 'I',
                'Participants' => 'I',
            ],
        ];

        $stakeholders = ['DSI', 'DRHMG', 'DCPRP (Protocole)', 'SG', 'Président', 'Participants'];

        $legend = [
            'R' => ['label' => 'Responsable', 'description' => 'Exécute la tâche', 'color' => 'primary'],
            'A' => ['label' => 'Approbateur', 'description' => 'Valide et porte la responsabilité finale', 'color' => 'success'],
            'C' => ['label' => 'Consulté', 'description' => 'Donne un avis ou expertise', 'color' => 'info'],
            'I' => ['label' => 'Informé', 'description' => 'Est tenu informé de l\'avancement ou du résultat', 'color' => 'secondary'],
        ];

        return view('raci.index', compact('raciMatrix', 'stakeholders', 'legend'));
    }
}

