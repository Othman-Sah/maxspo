
import React, { useState } from 'react';
import { 
  Bell, 
  CreditCard, 
  Calendar, 
  Settings, 
  UserPlus, 
  CheckCircle2, 
  Clock, 
  MessageSquare,
  Waves,
  AlertTriangle,
  History,
  TrendingUp,
  ExternalLink,
  ChevronRight
} from 'lucide-react';
import { MOCK_NOTIFICATIONS } from '../constants';
import { AppNotification } from '../types';

const NotificationsView: React.FC = () => {
  const [filter, setFilter] = useState<'all' | 'payment' | 'session' | 'system'>('all');

  const filtered = MOCK_NOTIFICATIONS.filter(n => filter === 'all' || n.type === filter);

  const getPriorityColor = (priority: AppNotification['priority']) => {
    switch (priority) {
      case 'high': return 'bg-rose-500';
      case 'medium': return 'bg-amber-500';
      case 'low': return 'bg-emerald-500';
    }
  };

  const getIcon = (type: AppNotification['type']) => {
    switch (type) {
      case 'payment': return <CreditCard size={20} className="text-rose-600" />;
      case 'session': return <Calendar size={20} className="text-indigo-600" />;
      case 'system': return <Settings size={20} className="text-slate-600" />;
      case 'member': return <UserPlus size={20} className="text-emerald-600" />;
    }
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 max-w-6xl mx-auto space-y-8 pb-20">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <Bell className="text-indigo-600" size={32} />
            Centre de Notifications
          </h1>
          <p className="text-slate-500 font-medium mt-1">Restez informé de l'activité de votre club en temps réel.</p>
        </div>
        <div className="flex items-center gap-3">
          <button className="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-50">
            Historique complet
          </button>
          <button className="px-6 py-2.5 bg-slate-900 text-white font-bold rounded-xl text-xs shadow-lg shadow-slate-200">
            Paramètres d'alertes
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {/* Navigation / Filters */}
        <div className="space-y-6">
          <div className="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm space-y-1">
            {[
              { id: 'all', label: 'Toutes les alertes', icon: Bell },
              { id: 'payment', label: 'Paiements', icon: CreditCard },
              { id: 'session', label: 'Séances', icon: Calendar },
              { id: 'system', label: 'Système', icon: Settings },
            ].map((tab) => (
              <button
                key={tab.id}
                onClick={() => setFilter(tab.id as any)}
                className={`w-full flex items-center justify-between px-4 py-3 rounded-2xl transition-all font-bold text-sm ${
                  filter === tab.id 
                  ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' 
                  : 'text-slate-500 hover:bg-slate-50'
                }`}
              >
                <div className="flex items-center gap-3">
                  <tab.icon size={18} />
                  {tab.label}
                </div>
                {filter !== tab.id && MOCK_NOTIFICATIONS.filter(n => n.type === tab.id).length > 0 && (
                  <span className="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[10px]">
                    {MOCK_NOTIFICATIONS.filter(n => n.type === tab.id).length}
                  </span>
                )}
              </button>
            ))}
          </div>

          <div className="bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 rounded-3xl text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
            <Waves className="absolute -bottom-4 -right-4 opacity-10 rotate-12" size={100} />
            <div className="relative z-10 space-y-4">
              <h4 className="font-black text-lg">Focus Aujourd'hui</h4>
              <div className="space-y-3">
                <div className="flex items-center gap-2">
                  <Clock size={14} className="text-indigo-200" />
                  <span className="text-xs font-bold">12 séances prévues</span>
                </div>
                <div className="flex items-center gap-2">
                  <AlertTriangle size={14} className="text-amber-300" />
                  <span className="text-xs font-bold">3 impayés critiques</span>
                </div>
                <div className="flex items-center gap-2">
                  <TrendingUp size={14} className="text-emerald-300" />
                  <span className="text-xs font-bold">+8 nouveaux membres</span>
                </div>
              </div>
              <button className="w-full py-3 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-black transition-all">
                Générer rapport du jour
              </button>
            </div>
          </div>
        </div>

        {/* Notifications List */}
        <div className="lg:col-span-3 space-y-4">
          {filtered.length > 0 ? (
            filtered.map((notif) => (
              <div 
                key={notif.id}
                className={`bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden group hover:border-indigo-200 transition-all ${
                  !notif.isRead ? 'border-l-4 border-l-indigo-600' : ''
                }`}
              >
                <div className="p-6 flex flex-col md:flex-row gap-6">
                  <div className={`h-16 w-16 shrink-0 rounded-2xl flex items-center justify-center relative ${
                    notif.type === 'payment' ? 'bg-rose-50' : 
                    notif.type === 'session' ? 'bg-indigo-50' : 
                    notif.type === 'member' ? 'bg-emerald-50' : 'bg-slate-100'
                  }`}>
                    {getIcon(notif.type)}
                    <span className={`absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white ${getPriorityColor(notif.priority)}`}></span>
                  </div>

                  <div className="flex-1 min-w-0 space-y-1">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <span className="text-[10px] font-black uppercase text-slate-400 tracking-widest">
                          {notif.type === 'payment' ? 'Finance' : notif.type === 'session' ? 'Planning' : 'Système'}
                        </span>
                        {!notif.isRead && (
                          <span className="px-1.5 py-0.5 bg-indigo-600 text-white text-[8px] font-black rounded-full uppercase">Nouveau</span>
                        )}
                      </div>
                      <span className="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                        <Clock size={10} /> {notif.time}
                      </span>
                    </div>
                    <h3 className="text-lg font-black text-slate-900 leading-tight">{notif.title}</h3>
                    <p className="text-sm text-slate-500 font-medium leading-relaxed">
                      {notif.description}
                    </p>
                    
                    {/* Action Context */}
                    {notif.type === 'payment' && (
                      <div className="pt-4 flex items-center gap-3">
                        <button className="flex items-center gap-2 px-4 py-2 bg-rose-600 text-white text-xs font-black rounded-xl shadow-lg shadow-rose-100 hover:bg-rose-700 transition-all active:scale-95">
                          <MessageSquare size={14} />
                          Relancer par WhatsApp
                        </button>
                        <button className="text-xs font-bold text-slate-400 hover:text-slate-600">Détails facture</button>
                      </div>
                    )}
                    
                    {notif.type === 'session' && (
                      <div className="pt-4 flex items-center gap-3">
                        <button className="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                          <ExternalLink size={14} />
                          Voir la liste d'appel
                        </button>
                      </div>
                    )}
                  </div>
                  
                  <div className="flex md:flex-col justify-end gap-2">
                    <button className="p-3 bg-slate-50 rounded-2xl text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                      <CheckCircle2 size={20} />
                    </button>
                  </div>
                </div>
              </div>
            ))
          ) : (
            <div className="bg-white py-24 rounded-3xl border-2 border-dashed border-slate-100 text-center space-y-4">
              <div className="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200">
                <Bell size={40} />
              </div>
              <div>
                <h3 className="text-xl font-black text-slate-900">Tout est calme !</h3>
                <p className="text-slate-400 font-medium">Aucune notification ne correspond à ce filtre.</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default NotificationsView;
