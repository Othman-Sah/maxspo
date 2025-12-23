
import { Member, SportStat, RevenueData, DashboardStats, Activity, Payment, PaymentMethodStat, AppNotification, StaffMember, Expense, POSItem } from './types';

export const MOCK_STATS: DashboardStats = {
  totalMembers: 482,
  expiringSoon: 14,
  monthlyRevenue: 125400,
  loyalMembers: 156,
  revenueTrend: 12.5,
  memberTrend: 8.2,
};

export const MOCK_REVENUE: RevenueData[] = [
  { month: 'Jan', amount: 98000, expenses: 45000 },
  { month: 'Fév', amount: 105000, expenses: 48000 },
  { month: 'Mar', amount: 112000, expenses: 52000 },
  { month: 'Avr', amount: 108000, expenses: 49000 },
  { month: 'Mai', amount: 115000, expenses: 51000 },
  { month: 'Juin', amount: 125400, expenses: 55000 },
];

export const MOCK_EXPENSES: Expense[] = [
  { id: 'E1', category: 'Loyer', description: 'Loyer du local Juin', amount: 25000, date: '2024-06-01', status: 'paye' },
  { id: 'E2', category: 'Électricité', description: 'Facture REDAL', amount: 4500, date: '2024-06-10', status: 'paye' },
  { id: 'E3', category: 'Maintenance', description: 'Entretien Sauna', amount: 1200, date: '2024-06-12', status: 'paye' },
  { id: 'E4', category: 'Marketing', description: 'Publicité Facebook', amount: 5000, date: '2024-06-15', status: 'prevu' },
  { id: 'E5', category: 'Salaires', description: 'Staff NEEDSPORT', amount: 18000, date: '2024-06-30', status: 'prevu' },
];

export const MOCK_STAFF: StaffMember[] = [
  { id: 'S1', name: 'Karim Idrissi', role: 'Coach Senior', status: 'present', phone: '06 11 22 33 44', email: 'karim@needsport.ma', salary: 6500, joinDate: '2022-03-01' },
  { id: 'S2', name: 'Laila Benani', role: 'Coach Yoga', status: 'present', phone: '06 22 33 44 55', email: 'laila@needsport.ma', salary: 5500, joinDate: '2023-01-15' },
  { id: 'S3', name: 'Omar Tazi', role: 'Réceptionniste', status: 'en_pause', phone: '06 33 44 55 66', email: 'omar@needsport.ma', salary: 4000, joinDate: '2023-05-10' },
  { id: 'S4', name: 'Hassan Jalal', role: 'Maintenance', status: 'absent', phone: '06 44 55 66 77', email: 'hassan@needsport.ma', salary: 3500, joinDate: '2022-11-20' },
];

export const MOCK_POS_ITEMS: POSItem[] = [
  { id: 'P1', name: 'Whey Protein (Single)', category: 'complement', price: 25, stock: 45 },
  { id: 'P2', name: 'Barre Énergétique', category: 'snack', price: 15, stock: 120 },
  { id: 'P3', name: 'Eau Minérale 50cl', category: 'boisson', price: 5, stock: 200 },
  { id: 'P4', name: 'BCAA Drink', category: 'complement', price: 20, stock: 35 },
  { id: 'P5', name: 'Pre-Workout Shot', category: 'complement', price: 30, stock: 25 },
  { id: 'P6', name: 'Isotonic Orange', category: 'boisson', price: 15, stock: 60 },
];

export const MOCK_SPORTS: SportStat[] = [
  { name: 'Fitness / Cardio', count: 210, color: '#6366f1' },
  { name: 'Boxe Anglaise', count: 95, color: '#f43f5e' },
  { name: 'Yoga & Pilates', count: 124, color: '#10b981' },
  { name: 'CrossFit', count: 53, color: '#f59e0b' },
];

export const MOCK_NOTIFICATIONS: AppNotification[] = [
  {
    id: 'n1',
    type: 'payment',
    title: 'Paiement en retard',
    description: 'Amine Kabbaj n\'a pas réglé son abonnement CrossFit (Mai).',
    time: 'Il y a 10 min',
    isRead: false,
    priority: 'high',
    meta: { memberId: '4' }
  },
  {
    id: 'n2',
    type: 'session',
    title: 'Session Boxe Pro',
    description: 'La séance de 18:00 est complète (20/20 participants).',
    time: 'Il y a 45 min',
    isRead: false,
    priority: 'medium'
  },
  {
    id: 'n3',
    type: 'system',
    title: 'Maintenance Sauna',
    description: 'Le capteur de température du sauna nécessite une vérification.',
    time: 'Il y a 2h',
    isRead: true,
    priority: 'low'
  },
  {
    id: 'n4',
    type: 'member',
    title: 'Nouvelle Inscription',
    description: 'Meryem Alaoui vient de rejoindre le club (Yoga & Pilates).',
    time: 'Il y a 3h',
    isRead: true,
    priority: 'low'
  },
  {
    id: 'n5',
    type: 'payment',
    title: 'Relance nécessaire',
    description: '5 membres arrivent à expiration demain sans paiement.',
    time: 'Ce matin',
    isRead: false,
    priority: 'high'
  }
];

export const MOCK_ACTIVITIES: Activity[] = [
  {
    id: '1',
    name: 'Fitness / Cardio',
    description: 'Accès illimité au plateau de musculation et aux machines cardio high-tech.',
    monthlyPrice: 250,
    memberCount: 210,
    totalRevenue: 450000,
    monthlyRevenue: 52500,
    color: 'from-indigo-500 to-blue-600',
    icon: 'Dumbbell'
  },
  {
    id: '2',
    name: 'Boxe Anglaise',
    description: 'Entraînement technique, cardio intense et sparring supervisé par des coachs certifiés.',
    monthlyPrice: 350,
    memberCount: 95,
    totalRevenue: 280000,
    monthlyRevenue: 33250,
    color: 'from-rose-500 to-red-600',
    icon: 'Target'
  },
  {
    id: '3',
    name: 'Yoga & Pilates',
    description: 'Travaillez votre souplesse, votre posture et votre bien-être mental.',
    monthlyPrice: 300,
    memberCount: 124,
    totalRevenue: 310000,
    monthlyRevenue: 37200,
    color: 'from-emerald-500 to-teal-600',
    icon: 'Flower2'
  },
  {
    id: '4',
    name: 'CrossFit',
    description: 'WOD quotidiens mêlant haltérophilie, gymnastique et cardio fonctionnel.',
    monthlyPrice: 400,
    memberCount: 53,
    totalRevenue: 150000,
    monthlyRevenue: 21200,
    color: 'from-amber-500 to-orange-600',
    icon: 'Flame'
  }
];

export const MOCK_PAYMENTS: Payment[] = [
  { id: 'P1', memberId: '1', memberName: 'Yassine Benali', sport: 'Fitness / Cardio', amount: 250, date: '2024-06-05', method: 'especes', status: 'valide' },
  { id: 'P2', memberId: '2', memberName: 'Sarah Mansouri', sport: 'Boxe Anglaise', amount: 350, date: '2024-06-04', method: 'carte', status: 'valide' },
  { id: 'P3', memberId: '3', memberName: 'Mehdi Amrani', sport: 'Yoga & Pilates', amount: 300, date: '2024-06-03', method: 'virement', status: 'en_attente' },
  { id: 'P4', memberId: '5', memberName: 'Fatima Zahra', sport: 'Fitness / Cardio', amount: 250, date: '2024-06-02', method: 'especes', status: 'valide' },
  { id: 'P5', memberId: '4', memberName: 'Amine Kabbaj', sport: 'CrossFit', amount: 400, date: '2024-06-01', method: 'cheque', status: 'valide' },
  { id: 'P6', memberId: '1', memberName: 'Yassine Benali', sport: 'Fitness / Cardio', amount: 250, date: '2024-05-05', method: 'carte', status: 'valide' },
];

export const MOCK_PAYMENT_METHOD_STATS: PaymentMethodStat[] = [
  { method: 'Espèces', count: 145, total: 36250, percentage: 42, color: 'bg-emerald-500', icon: 'Banknote' },
  { method: 'Carte', count: 98, total: 24500, percentage: 28, color: 'bg-blue-500', icon: 'CreditCard' },
  { method: 'Virement', count: 42, total: 10500, percentage: 12, color: 'bg-indigo-500', icon: 'Landmark' },
  { method: 'Chèque', count: 62, total: 15500, percentage: 18, color: 'bg-amber-500', icon: 'PenTool' },
];

export const MOCK_MEMBERS: Member[] = [
  {
    id: '1',
    firstName: 'Yassine',
    lastName: 'Benali',
    email: 'yassine@example.com',
    phone: '06 12 34 56 78',
    age: 28,
    sport: 'Fitness / Cardio',
    status: 'actif',
    expiryDate: '2024-06-25',
    joinDate: '2023-01-10',
    isLoyal: true,
  },
  {
    id: '2',
    firstName: 'Sarah',
    lastName: 'Mansouri',
    email: 'sarah@example.com',
    phone: '06 23 45 67 89',
    age: 24,
    sport: 'Boxe Anglaise',
    status: 'expirant',
    expiryDate: '2024-06-12',
    joinDate: '2024-03-05',
    isLoyal: false,
  },
  {
    id: '3',
    firstName: 'Mehdi',
    lastName: 'Amrani',
    email: 'mehdi@example.com',
    phone: '06 34 56 78 90',
    age: 32,
    sport: 'Yoga & Pilates',
    status: 'actif',
    expiryDate: '2024-06-18',
    joinDate: '2022-11-20',
    isLoyal: true,
  },
  {
    id: '4',
    firstName: 'Amine',
    lastName: 'Kabbaj',
    email: 'amine@example.com',
    phone: '06 45 67 89 01',
    age: 30,
    sport: 'CrossFit',
    status: 'expire',
    expiryDate: '2024-05-30',
    joinDate: '2023-05-15',
    isLoyal: true,
  },
  {
    id: '5',
    firstName: 'Fatima',
    lastName: 'Zahra',
    email: 'fatima@example.com',
    phone: '06 56 78 90 12',
    age: 26,
    sport: 'Fitness / Cardio',
    status: 'actif',
    expiryDate: '2024-09-10',
    joinDate: '2024-02-12',
    isLoyal: false,
  }
];

export const MOCK_EXPIRING_MEMBERS = MOCK_MEMBERS.filter(m => m.status === 'expirant' || m.status === 'expire');
