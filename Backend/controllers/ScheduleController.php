<?php
/**
 * Schedule Controller
 * Handles schedule data
 */

class ScheduleController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        // In a real app, you'd query the DB. For now, we use a local mock structure.
    }

    /**
     * Get the schedule for the week.
     */
    public function getWeeklySchedule() {
        // This data matches the structure from the React component's mock data.
        return [
            ['day' => 'Lundi', 'block' => 'morning', 'activity' => 'CrossFit', 'color' => 'bg-amber-500', 'icon' => 'flame', 'capacity' => '12/15'],
            ['day' => 'Lundi', 'block' => 'evening', 'activity' => 'Boxe Anglaise', 'color' => 'bg-rose-500', 'icon' => 'target', 'capacity' => 'Complet'],
            ['day' => 'Mardi', 'block' => 'morning', 'activity' => 'Yoga & Pilates', 'color' => 'bg-emerald-500', 'icon' => 'flower-2', 'capacity' => '8/20'],
            ['day' => 'Mardi', 'block' => 'afternoon', 'activity' => 'Fitness / Cardio', 'color' => 'bg-indigo-600', 'icon' => 'dumbbell', 'capacity' => 'Libre'],
            ['day' => 'Mercredi', 'block' => 'evening', 'activity' => 'CrossFit', 'color' => 'bg-amber-500', 'icon' => 'flame', 'capacity' => '14/15'],
            ['day' => 'Jeudi', 'block' => 'morning', 'activity' => 'Boxe Anglaise', 'color' => 'bg-rose-500', 'icon' => 'target', 'capacity' => '10/20'],
            ['day' => 'Vendredi', 'block' => 'evening', 'activity' => 'Yoga & Pilates', 'color' => 'bg-emerald-500', 'icon' => 'flower-2', 'capacity' => '15/20'],
            ['day' => 'Samedi', 'block' => 'morning', 'activity' => 'Fitness / Cardio', 'color' => 'bg-indigo-600', 'icon' => 'dumbbell', 'capacity' => 'Libre'],
        ];
    }
    
    public function getDays() {
        return ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    }

    public function getTimeBlocks() {
        return [
            ['id' => 'morning', 'label' => 'Matin', 'time' => '08:00 - 12:00'],
            ['id' => 'afternoon', 'label' => 'Après-midi', 'time' => '14:00 - 17:00'],
            ['id' => 'evening', 'label' => 'Soirée', 'time' => '18:00 - 21:00']
        ];
    }
}
?>