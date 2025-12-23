
import React from 'react';
import { 
  Dumbbell, 
  Plus, 
  TrendingUp, 
  Users, 
  CreditCard, 
  Waves, 
  Edit3, 
  Trash2, 
  Flame, 
  Target, 
  Flower2,
  LucideIcon
} from 'lucide-react';
import { MOCK_ACTIVITIES } from '../constants';
import { Activity } from '../types';

interface ActivitiesViewProps {
  onAddActivity: () => void;
  onEdit: (activity: Activity) => void;
  onDelete: (activity: Activity) => void;
}

const iconMap: Record<string, LucideIcon> = {
  Dumbbell,
  Target,
  Flower2,
  Flame
};

const ActivitiesView: React.FC<ActivitiesViewProps> = ({ onAddActivity, onEdit, onDelete }) => {
  const totalRevenue = MOCK_ACTIVITIES.reduce((acc, curr) => acc + curr.monthlyRevenue, 0);
  const totalMembres = MOCK_ACTIVITIES.reduce((acc, curr) => acc + curr.memberCount, 0);

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <Dumbbell className="text-indigo-600" size={32} />
            Gestion des Sports
          </h1>
          <p className="text-slate-500 font-medium mt-1">Configurez vos offres et suivez la rentabilité par activité (Sauna 🧖 inclus par défaut)</p>
        </div>
        <button 
          onClick={onAddActivity}
          className="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 group active:scale-95"
        >
          <Plus size={20} className="group-hover:rotate-90 transition-transform duration-300" />
          Ajouter une Activité
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
          <div className="h-14 w-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><Users size={28} /></div>
          <div><p className="text-slate-500 text-xs font-bold uppercase tracking-wider">Membres Inscrits</p><h3 className="text-2xl font-black text-slate-900">{totalMembres}</h3></div>
        </div>
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
          <div className="h-14 w-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><CreditCard size={28} /></div>
          <div><p className="text-slate-500 text-xs font-bold uppercase tracking-wider">Revenu Mensuel</p><h3 className="text-2xl font-black text-slate-900">{totalRevenue.toLocaleString('fr-FR')} DH</h3></div>
        </div>
        <div className="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-100 flex items-center gap-5 text-white">
          <div className="h-14 w-14 rounded-xl bg-white/20 flex items-center justify-center"><Waves size={28} /></div>
          <div><p className="text-indigo-100 text-xs font-bold uppercase tracking-wider italic">Accès Premium</p><h3 className="text-xl font-black">Sauna Illimité 🧖</h3></div>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {MOCK_ACTIVITIES.map((activity) => {
          const IconComponent = iconMap[activity.icon] || Dumbbell;
          return (
            <div key={activity.id} className="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
              <div className={`h-2 bg-gradient-to-r ${activity.color}`}></div>
              <div className="p-8">
                <div className="flex items-start justify-between mb-6">
                  <div className={`h-16 w-16 rounded-2xl bg-gradient-to-br ${activity.color} text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500`}><IconComponent size={32} /></div>
                  <span className="flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded-full border border-amber-100"><Waves size={10} /> Sauna Inclus</span>
                </div>
                <h3 className="text-2xl font-black text-slate-900 mb-2">{activity.name}</h3>
                <div className="bg-slate-50 rounded-2xl p-5 mb-6">
                  <div className="flex items-baseline gap-1"><span className={`text-4xl font-black bg-gradient-to-r ${activity.color} bg-clip-text text-transparent`}>{activity.monthlyPrice}</span><span className="text-slate-500 font-bold">DH</span></div>
                </div>
                <div className="flex gap-3">
                  <button 
                    onClick={() => onEdit(activity)} 
                    className="flex-1 flex items-center justify-center gap-2 py-3 bg-slate-50 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-colors border border-slate-100"
                  >
                    <Edit3 size={16} /> Modifier
                  </button>
                  <button 
                    onClick={() => onDelete(activity)} 
                    className="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors border border-transparent hover:border-rose-100"
                  >
                    <Trash2 size={18} />
                  </button>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default ActivitiesView;
