
import React from 'react';
import { 
  LayoutDashboard, 
  Users, 
  Dumbbell, 
  CreditCard, 
  Settings, 
  LogOut,
  PlusCircle,
  Trophy,
  Zap,
  CalendarDays,
  LineChart,
  UserCheck,
  ShoppingCart
} from 'lucide-react';

interface SidebarProps {
  activeTab: string;
  setActiveTab: (tab: string) => void;
}

const Sidebar: React.FC<SidebarProps> = ({ activeTab, setActiveTab }) => {
  const mainItem = { id: 'dashboard', icon: LayoutDashboard, label: 'Tableau de bord' };
  
  const navItems = [
    { id: 'members', icon: Users, label: 'Membres' },
    { id: 'sports', icon: Dumbbell, label: 'Activités' },
    { id: 'schedule', icon: CalendarDays, label: 'Planning' },
    { id: 'financials', icon: LineChart, label: 'Finances' },
    { id: 'staff', icon: UserCheck, label: 'Équipe Staff' },
    { id: 'pos', icon: ShoppingCart, label: 'Caisse POS' },
    { id: 'payments', icon: CreditCard, label: 'Journal Paiements' },
  ];

  return (
    <aside className="w-64 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0 shrink-0">
      <div className="p-6 flex items-center gap-3">
        <div className="bg-indigo-600 p-2 rounded-xl text-white shadow-lg shadow-indigo-200">
          <Trophy size={24} />
        </div>
        <span className="text-xl font-bold text-slate-900 tracking-tight">NEEDSPORT</span>
      </div>

      <nav className="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
        {/* Dashboard - First Item */}
        <button
          onClick={() => setActiveTab(mainItem.id)}
          className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium ${
            activeTab === mainItem.id 
              ? 'bg-indigo-50 text-indigo-600' 
              : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
          }`}
        >
          <mainItem.icon size={20} />
          {mainItem.label}
        </button>

        {/* Quick Actions - Now under Dashboard */}
        <div className="py-4 border-y border-slate-50 my-2">
          <p className="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Actions rapides</p>
          <div className="space-y-2 px-1">
            <button 
              onClick={() => setActiveTab('add-member')}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold text-xs ${
                activeTab === 'add-member'
                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100'
                : 'bg-slate-900 text-white hover:bg-slate-800 shadow-md shadow-slate-100'
              }`}
            >
              <PlusCircle size={16} />
              Nouveau membre
            </button>
            <button 
              onClick={() => setActiveTab('add-activity')}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold text-xs border-2 ${
                activeTab === 'add-activity'
                ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
                : 'bg-white border-slate-100 text-slate-600 hover:border-emerald-200 hover:text-emerald-600'
              }`}
            >
              <Zap size={16} />
              Nouvelle Activité
            </button>
          </div>
        </div>

        {/* Rest of Navigation */}
        {navItems.map((item) => (
          <button
            key={item.id}
            onClick={() => setActiveTab(item.id)}
            className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium ${
              activeTab === item.id 
                ? 'bg-indigo-50 text-indigo-600' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
            }`}
          >
            <item.icon size={20} />
            {item.label}
          </button>
        ))}
      </nav>

      <div className="p-4 border-t border-slate-100 space-y-1">
        <button 
          onClick={() => setActiveTab('settings')}
          className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium ${
            activeTab === 'settings'
            ? 'bg-slate-100 text-slate-900'
            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
          }`}
        >
          <Settings size={20} />
          Paramètres
        </button>
        <button 
          onClick={() => {
            if(confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
               alert('Déconnexion en cours...');
               window.location.reload();
            }
          }}
          className="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all font-medium"
        >
          <LogOut size={20} />
          Déconnexion
        </button>
      </div>
    </aside>
  );
};

export default Sidebar;
