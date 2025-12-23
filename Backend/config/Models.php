<?php
/**
 * Data Models - PHP equivalents of TypeScript interfaces
 */

class Member {
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $age;
    public $sport;
    public $status;
    public $expiryDate;
    public $joinDate;
    public $photo;
    public $isLoyal;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function isExpired() {
        return isExpired($this->expiryDate);
    }

    public function isExpiringSoon() {
        return isExpiringsSoon($this->expiryDate);
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class Activity {
    public $id;
    public $name;
    public $description;
    public $monthlyPrice;
    public $memberCount;
    public $totalRevenue;
    public $monthlyRevenue;
    public $color;
    public $icon;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class Payment {
    public $id;
    public $memberId;
    public $memberName;
    public $sport;
    public $amount;
    public $date;
    public $method; // especes, carte, virement, cheque
    public $status; // valide, en_attente, annule

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class Expense {
    public $id;
    public $category;
    public $description;
    public $amount;
    public $date;
    public $status; // paye, prevu

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class StaffMember {
    public $id;
    public $name;
    public $role;
    public $status; // present, absent, en_pause
    public $phone;
    public $email;
    public $salary;
    public $joinDate;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class POSItem {
    public $id;
    public $name;
    public $category; // snack, boisson, complement
    public $price;
    public $stock;
    public $image;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class Notification {
    public $id;
    public $type; // payment, session, system, member
    public $title;
    public $description;
    public $time;
    public $isRead;
    public $priority; // low, medium, high
    public $meta;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class DashboardStats {
    public $totalMembers;
    public $expiringSoon;
    public $monthlyRevenue;
    public $loyalMembers;
    public $revenueTrend;
    public $memberTrend;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

class User {
    public $id;
    public $email;
    public $password;
    public $firstName;
    public $lastName;
    public $role;
    public $createdAt;
    public $lastLogin;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
?>
