
import React from 'react';
import { 
  User, 
  Settings, 
  LogOut, 
  Shield, 
  CreditCard,
  ChevronRight,
  ExternalLink
} from 'lucide-react';

interface ProfileDropdownProps {
  onNavigate: (tab: string) => void;
  onClose: () => void;
}

const ProfileDropdown: React.FC<ProfileDropdownProps> = ({ onNavigate, onClose }) => {
  const menuItems = [
    { id: 'settings-profile', label: 'Mon Profil', icon: User, desc: 'Infos personnelles' },
    { id: 'settings', label: 'Paramètres', icon: Settings, desc: 'Configuration du club' },
    { id: 'settings-security', label: 'Sécurité', icon: Shield, desc: 'Mot de passe & 2FA' },
  ];

  const handleLogout = () => {
    if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
      alert('Déconnexion en cours...');
      window.location.reload();
    }
  };

  return (
    <div className="absolute top-full right-0 mt-4 w-72 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-50 animate-in fade-in slide-in-from-top-2 duration-300">
      {/* Header Info */}
      <div className="p-6 bg-slate-50 border-b border-slate-100">
        <div className="flex items-center gap-4">
          <div className="h-12 w-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-100">
            AC
          </div>
          <div className="min-w-0">
            <p className="text-sm font-black text-slate-900 truncate">Admin Coach</p>
            <p className="text-xs font-bold text-slate-400">super-admin@needsport.ma</p>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <div className="p-2">
        {menuItems.map((item) => (
          <button
            key={item.id}
            onClick={() => {
              onNavigate(item.id.includes('settings') ? 'settings' : item.id);
              onClose();
            }}
            className="w-full flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-all group text-left"
          >
            <div className="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-all">
              <item.icon size={20} />
            </div>
            <div className="flex-1">
              <p className="text-sm font-black text-slate-900 leading-tight">{item.label}</p>
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{item.desc}</p>
            </div>
            <ChevronRight size={14} className="text-slate-200 group-hover:text-slate-400 transition-colors" />
          </button>
        ))}
      </div>

      {/* Footer Actions */}
      <div className="p-2 border-t border-slate-50">
        <button 
          onClick={handleLogout}
          className="w-full flex items-center gap-3 p-3 rounded-2xl hover:bg-rose-50 text-rose-500 transition-all group text-left"
        >
          <div className="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-rose-300 group-hover:text-rose-500 group-hover:border-rose-100 transition-all">
            <LogOut size={20} />
          </div>
          <div>
            <p className="text-sm font-black leading-tight">Déconnexion</p>
            <p className="text-[10px] font-bold uppercase opacity-60">Quitter la session</p>
          </div>
        </button>
      </div>
      
      <div className="p-4 bg-indigo-600 flex items-center justify-between text-white">
        <span className="text-[10px] font-black uppercase tracking-widest">Version Pro 2.4</span>
        <ExternalLink size={12} className="opacity-50" />
      </div>
    </div>
  );
};

export default ProfileDropdown;
