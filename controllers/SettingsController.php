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

    /**
     * Get a setting from database
     */
    private function getSetting($name, $default = null) {
        try {
            $stmt = $this->db->conn->prepare("SELECT value FROM settings WHERE name = ?");
            $stmt->execute([$name]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? json_decode($result['value'], true) : $default;
        } catch (Exception $e) {
            return $default;
        }
    }

    /**
     * Save a setting to database
     */
    private function setSetting($name, $value) {
        try {
            $stmt = $this->db->conn->prepare("INSERT INTO settings (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$name, json_encode($value), json_encode($value)]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to save setting: " . $e->getMessage());
        }
    }

    public function getProfileInfo() {
        $profile = $this->getSetting('profile_info', [
            'initials' => 'AC',
            'name' => 'Admin Coach',
            'role' => 'Super Administrateur',
            'id' => 'ADMIN-01',
            'email' => 'super-admin@needsport.ma',
            'city' => 'Casablanca, Maroc',
        ]);
        return $profile;
    }
    
    public function getGeneralSettings() {
        return $this->getSetting('general_settings', [
            'clubName' => 'NEEDSPORT Pro',
            'slogan' => 'La performance au quotidien',
            'language' => 'fr',
            'timezone' => '(GMT+01:00) Casablanca',
        ]);
    }

    public function getThemeSettings() {
        return $this->getSetting('theme_settings', [
            'themeColor' => 'indigo',
            'logo' => null,
        ]);
    }

    public function getPaymentSettings() {
        return $this->getSetting('payment_settings', [
            'currency' => 'DH',
            'taxRate' => 20,
        ]);
    }

    public function getAllSettings() {
        return [
            'general' => $this->getGeneralSettings(),
            'theme' => $this->getThemeSettings(),
            'profile' => $this->getProfileInfo(),
            'payments' => $this->getPaymentSettings(),
        ];
    }

    public function saveGeneralSettings($clubName, $slogan, $language, $timezone) {
        $settings = [
            'clubName' => $clubName,
            'slogan' => $slogan,
            'language' => $language,
            'timezone' => $timezone,
        ];
        $this->setSetting('general_settings', $settings);
        return ['success' => true, 'message' => 'Paramètres généraux enregistrés', 'data' => $settings];
    }

    public function saveBrandingSettings($themeColor, $logo = null) {
        $settings = [
            'themeColor' => $themeColor,
            'logo' => $logo,
        ];
        $this->setSetting('theme_settings', $settings);
        return ['success' => true, 'message' => 'Paramètres de branding enregistrés', 'data' => $settings];
    }

    public function saveProfileInfo($name, $email, $city) {
        $profile = $this->getProfileInfo();
        $profile['name'] = $name;
        $profile['email'] = $email;
        $profile['city'] = $city;
        $this->setSetting('profile_info', $profile);
        return ['success' => true, 'message' => 'Profil enregistré', 'data' => $profile];
    }

    public function savePaymentSettings($currency, $taxRate) {
        $settings = [
            'currency' => $currency,
            'taxRate' => $taxRate,
        ];
        $this->setSetting('payment_settings', $settings);
        return ['success' => true, 'message' => 'Paramètres de paiement enregistrés', 'data' => $settings];
    }
}
?>