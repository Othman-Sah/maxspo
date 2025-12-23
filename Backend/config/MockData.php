<?php
/**
 * Mock Data - Constants file with sample data
 */

return [
    'stats' => [
        'totalMembers' => 482,
        'expiringSoon' => 14,
        'monthlyRevenue' => 125400,
        'loyalMembers' => 156,
        'revenueTrend' => 12.5,
        'memberTrend' => 8.2,
    ],

    'activities' => [
        [
            'id' => '1',
            'name' => 'Fitness / Cardio',
            'description' => 'Accès illimité au plateau de musculation et aux machines cardio high-tech.',
            'monthlyPrice' => 250,
            'memberCount' => 210,
            'totalRevenue' => 450000,
            'monthlyRevenue' => 52500,
            'color' => 'from-indigo-500 to-blue-600',
            'icon' => 'Dumbbell'
        ],
        [
            'id' => '2',
            'name' => 'Boxe Anglaise',
            'description' => 'Entraînement technique, cardio intense et sparring supervisé par des coachs certifiés.',
            'monthlyPrice' => 350,
            'memberCount' => 95,
            'totalRevenue' => 280000,
            'monthlyRevenue' => 33250,
            'color' => 'from-rose-500 to-red-600',
            'icon' => 'Target'
        ],
        [
            'id' => '3',
            'name' => 'Yoga & Pilates',
            'description' => 'Travaillez votre souplesse, votre posture et votre bien-être mental.',
            'monthlyPrice' => 300,
            'memberCount' => 124,
            'totalRevenue' => 310000,
            'monthlyRevenue' => 37200,
            'color' => 'from-emerald-500 to-teal-600',
            'icon' => 'Flower2'
        ],
        [
            'id' => '4',
            'name' => 'CrossFit',
            'description' => 'WOD quotidiens mêlant haltérophilie, gymnastique et cardio fonctionnel.',
            'monthlyPrice' => 400,
            'memberCount' => 53,
            'totalRevenue' => 150000,
            'monthlyRevenue' => 21200,
            'color' => 'from-amber-500 to-orange-600',
            'icon' => 'Flame'
        ]
    ],

    'members' => [
        [
            'id' => '1',
            'firstName' => 'Yassine',
            'lastName' => 'Benali',
            'email' => 'yassine@example.com',
            'phone' => '06 12 34 56 78',
            'age' => 28,
            'sport' => 'Fitness / Cardio',
            'status' => 'actif',
            'expiryDate' => '2024-06-25',
            'joinDate' => '2023-01-10',
            'isLoyal' => true,
        ],
        [
            'id' => '2',
            'firstName' => 'Sarah',
            'lastName' => 'Mansouri',
            'email' => 'sarah@example.com',
            'phone' => '06 23 45 67 89',
            'age' => 24,
            'sport' => 'Boxe Anglaise',
            'status' => 'expirant',
            'expiryDate' => '2024-06-12',
            'joinDate' => '2024-03-05',
            'isLoyal' => false,
        ],
        [
            'id' => '3',
            'firstName' => 'Mehdi',
            'lastName' => 'Amrani',
            'email' => 'mehdi@example.com',
            'phone' => '06 34 56 78 90',
            'age' => 32,
            'sport' => 'Yoga & Pilates',
            'status' => 'actif',
            'expiryDate' => '2024-06-18',
            'joinDate' => '2022-11-20',
            'isLoyal' => true,
        ],
        [
            'id' => '4',
            'firstName' => 'Amine',
            'lastName' => 'Kabbaj',
            'email' => 'amine@example.com',
            'phone' => '06 45 67 89 01',
            'age' => 30,
            'sport' => 'CrossFit',
            'status' => 'expire',
            'expiryDate' => '2024-05-30',
            'joinDate' => '2023-05-15',
            'isLoyal' => true,
        ],
        [
            'id' => '5',
            'firstName' => 'Fatima',
            'lastName' => 'Zahra',
            'email' => 'fatima@example.com',
            'phone' => '06 56 78 90 12',
            'age' => 26,
            'sport' => 'Fitness / Cardio',
            'status' => 'actif',
            'expiryDate' => '2024-09-10',
            'joinDate' => '2024-02-12',
            'isLoyal' => false,
        ]
    ],

    'staff' => [
        [
            'id' => 'S1',
            'name' => 'Karim Idrissi',
            'role' => 'Coach Senior',
            'status' => 'present',
            'phone' => '06 11 22 33 44',
            'email' => 'karim@needsport.ma',
            'salary' => 6500,
            'joinDate' => '2022-03-01'
        ],
        [
            'id' => 'S2',
            'name' => 'Laila Benani',
            'role' => 'Coach Yoga',
            'status' => 'present',
            'phone' => '06 22 33 44 55',
            'email' => 'laila@needsport.ma',
            'salary' => 5500,
            'joinDate' => '2023-01-15'
        ],
        [
            'id' => 'S3',
            'name' => 'Omar Tazi',
            'role' => 'Réceptionniste',
            'status' => 'en_pause',
            'phone' => '06 33 44 55 66',
            'email' => 'omar@needsport.ma',
            'salary' => 4000,
            'joinDate' => '2023-05-10'
        ],
        [
            'id' => 'S4',
            'name' => 'Hassan Jalal',
            'role' => 'Maintenance',
            'status' => 'absent',
            'phone' => '06 44 55 66 77',
            'email' => 'hassan@needsport.ma',
            'salary' => 3500,
            'joinDate' => '2022-11-20'
        ]
    ],

    'payments' => [
        [
            'id' => 'P1',
            'memberId' => '1',
            'memberName' => 'Yassine Benali',
            'sport' => 'Fitness / Cardio',
            'amount' => 250,
            'date' => '2024-06-05',
            'method' => 'especes',
            'status' => 'valide'
        ],
        [
            'id' => 'P2',
            'memberId' => '2',
            'memberName' => 'Sarah Mansouri',
            'sport' => 'Boxe Anglaise',
            'amount' => 350,
            'date' => '2024-06-04',
            'method' => 'carte',
            'status' => 'valide'
        ],
        [
            'id' => 'P3',
            'memberId' => '3',
            'memberName' => 'Mehdi Amrani',
            'sport' => 'Yoga & Pilates',
            'amount' => 300,
            'date' => '2024-06-03',
            'method' => 'virement',
            'status' => 'en_attente'
        ]
    ],

    'expenses' => [
        [
            'id' => 'E1',
            'category' => 'Loyer',
            'description' => 'Loyer du local Juin',
            'amount' => 25000,
            'date' => '2024-06-01',
            'status' => 'paye'
        ],
        [
            'id' => 'E2',
            'category' => 'Électricité',
            'description' => 'Facture REDAL',
            'amount' => 4500,
            'date' => '2024-06-10',
            'status' => 'paye'
        ],
        [
            'id' => 'E3',
            'category' => 'Maintenance',
            'description' => 'Entretien Sauna',
            'amount' => 1200,
            'date' => '2024-06-12',
            'status' => 'paye'
        ]
    ],

    'notifications' => [
        [
            'id' => 'n1',
            'type' => 'payment',
            'title' => 'Paiement en retard',
            'description' => 'Amine Kabbaj n\'a pas réglé son abonnement CrossFit (Mai).',
            'time' => 'Il y a 10 min',
            'isRead' => false,
            'priority' => 'high',
            'meta' => ['memberId' => '4']
        ],
        [
            'id' => 'n2',
            'type' => 'session',
            'title' => 'Session Boxe Pro',
            'description' => 'La séance de 18:00 est complète (20/20 participants).',
            'time' => 'Il y a 45 min',
            'isRead' => false,
            'priority' => 'medium'
        ]
    ]
];
?>
