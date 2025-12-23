
export interface Member {
  id: string;
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  age: number;
  sport: string;
  status: 'actif' | 'expirant' | 'expire';
  expiryDate: string;
  joinDate: string;
  photo?: string;
  isLoyal: boolean;
}

export interface Activity {
  id: string;
  name: string;
  description: string;
  monthlyPrice: number;
  memberCount: number;
  totalRevenue: number;
  monthlyRevenue: number;
  color: string;
  icon: string;
}

export interface Payment {
  id: string;
  memberId: string;
  memberName: string;
  sport: string;
  amount: number;
  date: string;
  method: 'especes' | 'carte' | 'virement' | 'cheque';
  status: 'valide' | 'en_attente' | 'annule';
}

export interface Expense {
  id: string;
  category: string;
  description: string;
  amount: number;
  date: string;
  status: 'paye' | 'prevu';
}

export interface StaffMember {
  id: string;
  name: string;
  role: string;
  status: 'present' | 'absent' | 'en_pause';
  phone: string;
  email: string;
  salary: number;
  joinDate: string;
}

export interface POSItem {
  id: string;
  name: string;
  category: 'snack' | 'boisson' | 'complement';
  price: number;
  stock: number;
  image?: string;
}

export interface AppNotification {
  id: string;
  type: 'payment' | 'session' | 'system' | 'member';
  title: string;
  description: string;
  time: string;
  isRead: boolean;
  priority: 'low' | 'medium' | 'high';
  meta?: any;
}

export interface PaymentMethodStat {
  method: string;
  count: number;
  total: number;
  percentage: number;
  color: string;
  icon: string;
}

export interface SportStat {
  name: string;
  count: number;
  color: string;
}

export interface RevenueData {
  month: string;
  amount: number;
  expenses: number;
}

export interface DashboardStats {
  totalMembers: number;
  expiringSoon: number;
  monthlyRevenue: number;
  loyalMembers: number;
  revenueTrend: number;
  memberTrend: number;
}
