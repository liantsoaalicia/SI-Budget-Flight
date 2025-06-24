<?php
namespace app\constants;

class TicketStatus
{
    const ARRIVED      = 0; // Ticket has arrived
    const OPEN         = 1;
    const IN_PROGRESS  = 2;
    const RESOLVED     = 3;
    const CLOSED       = 4;

    /**
     * Get all status labels as [int => string]
     */
    public static function all(): array {
        return [
            self::ARRIVED      => 'Arrivé',
            self::OPEN         => 'Ouvert',
            self::IN_PROGRESS  => 'En cours',
            self::RESOLVED     => 'Résolu',
            self::CLOSED       => 'Fermé',
        ];
    }

    /**
     * Get label by status value
     */
    public static function label(int $status): string {
        return self::all()[$status] ?? 'Inconnu';
    }
}
