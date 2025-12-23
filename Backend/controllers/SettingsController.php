<?php
/**
 * Settings Controller
 * Handles settings data
 */

class SettingsController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getProfileInfo() {
        // In a real app, this would come from the logged-in user's session or DB record
        return [
            'initials' => 'AC',
            'name' => 'Admin Coach',
            'role' => 'Super Administrateur',
            'id' => 'ADMIN-01',
            'email' => 'super-admin@needsport.ma',
            'city' => 'Casablanca, Maroc',
        ];
    }
    
    public function getGeneralSettings() {
        return [
            'clubName' => 'NEEDSPORT Pro',
            'slogan' => 'La performance au quotidien',
            'language' => 'fr',
            'timezone' => '(GMT+01:00) Casablanca',
        ];
    }
}
?>