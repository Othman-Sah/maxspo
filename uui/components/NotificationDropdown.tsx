
import React from 'react';
import { 
  Bell, 
  CreditCard, 
  Calendar, 
  Settings, 
  UserPlus, 
  CheckCircle2, 
  Clock,
  ArrowRight
} from 'lucide-react';
import { MOCK_NOTIFICATIONS } from '../constants';
import { AppNotification } from '../types';

interface NotificationDropdownProps {
  onViewAll: () => void;
  onClose: () => void;
}

const NotificationDropdown: React.FC<NotificationDropdownProps> = ({ onViewAll, onClose }) => {
  const unreadCount = MOCK_NOTIFICATIONS.filter(n => !n.isRead).length;

  const getIcon = (type: AppNotification['type']) => {
    switch (type) {
      case 'payment': return <CreditCard size={16} className="text-rose-500" />;
      case 'session': return <Calendar size={16} className="text-indigo-500" />;
      case 'system': return <Settings size={16} className="text-slate-500" />;
      case 'member': return <UserPlus size={16} className="text-emerald-500" />;
    }
  };

  return (
    <div className="absolute top-full right-0 mt-4 w-96 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-50 animate-in fade-in slide-in-from-top-2 duration-300">
      <div className="p-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
        <div className="flex items-center gap-2">
          <h3 className="font-black text-slate-900">Notifications</h3>
          {unreadCount > 0 && (
            <span className="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">
              {unreadCount} NOUVEAUX
            </span>
          )}
        </div>
        <button className="text-xs font-bold text-indigo-600 hover:text-indigo-700">Tout marquer comme lu</button>
      </div>

      <div className="max-h-[400px] overflow-y-auto divide-y divide-slate-50">
        {MOCK_NOTIFICATIONS.slice(0, 5).map((notif) => (
          <div 
            key={notif.id} 
            className={`p-4 flex gap-4 hover:bg-slate-50 transition-colors cursor-pointer group ${!notif.isRead ? 'bg-indigo-50/30' : ''}`}
          >
            <div className={`mt-1 h-10 w-10 shrink-0 rounded-2xl flex items-center justify-center ${
              notif.type === 'payment' ? 'bg-rose-50' : 
              notif.type === 'session' ? 'bg-indigo-50' : 
              notif.type === 'member' ? 'bg-emerald-50' : 'bg-slate-100'
            }`}>
              {getIcon(notif.type)}
            </div>
            <div className="flex-1 min-w-0">
              <div className="flex items-center justify-between mb-0.5">
                <p className="text-sm font-black text-slate-900 truncate">{notif.title}</p>
                {notif.priority === 'high' && (
                  <span className="flex h-2 w-2 rounded-full bg-rose-500 ring-4 ring-rose-100"></span>
                )}
              </div>
              <p className="text-xs text-slate-500 font-medium line-clamp-2 leading-relaxed">
                {notif.description}
              </p>
              <p className="text-[10px] font-bold text-slate-400 mt-2 uppercase flex items-center gap-1">
                <Clock size={10} />
                {notif.time}
              </p>
            </div>
          </div>
        ))}
      </div>

      <button 
        onClick={() => { onViewAll(); onClose(); }}
        className="w-full p-4 bg-slate-900 text-white text-sm font-black flex items-center justify-center gap-2 hover:bg-slate-800 transition-all group"
      >
        Voir tout l'historique
        <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform" />
      </button>
    </div>
  );
};

export default NotificationDropdown;
