
import React from 'react';
import { 
  CalendarDays, 
  ChevronLeft, 
  ChevronRight, 
  Plus, 
  Settings2, 
  Clock, 
  Users, 
  Info,
  Dumbbell,
  Target,
  Flower2,
  Flame,
  Search
} from 'lucide-react';

interface ScheduleViewProps {
  onModify: () => void;
}

const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
const TIME_BLOCKS = [
  { id: 'morning', label: 'Matin', time: '08:00 - 12:00' },
  { id: 'afternoon', label: 'Après-midi', time: '14:00 - 17:00' },
  { id: 'evening', label: 'Soirée', time: '18:00 - 21:00' }
];

const MOCK_SCHEDULE_DATA = [
  { day: 'Lundi', block: 'morning', activity: 'CrossFit', color: 'bg-amber-500', icon: Flame, capacity: '12/15' },
  { day: 'Lundi', block: 'evening', activity: 'Boxe Anglaise', color: 'bg-rose-500', icon: Target, capacity: 'Complet' },
  { day: 'Mardi', block: 'morning', activity: 'Yoga & Pilates', color: 'bg-emerald-500', icon: Flower2, capacity: '8/20' },
  { day: 'Mardi', block: 'afternoon', activity: 'Fitness / Cardio', color: 'bg-indigo-600', icon: Dumbbell, capacity: 'Libre' },
  { day: 'Mercredi', block: 'evening', activity: 'CrossFit', color: 'bg-amber-500', icon: Flame, capacity: '14/15' },
  { day: 'Jeudi', block: 'morning', activity: 'Boxe Anglaise', color: 'bg-rose-500', icon: Target, capacity: '10/20' },
  { day: 'Vendredi', block: 'evening', activity: 'Yoga & Pilates', color: 'bg-emerald-500', icon: Flower2, capacity: '15/20' },
  { day: 'Samedi', block: 'morning', activity: 'Fitness / Cardio', color: 'bg-indigo-600', icon: Dumbbell, capacity: 'Libre' },
];

const ScheduleView: React.FC<ScheduleViewProps> = ({ onModify }) => {
  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8 pb-12">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <CalendarDays className="text-indigo-600" size={32} />
            Planning de la Semaine
          </h1>
          <p className="text-slate-500 font-medium mt-1">Gérez les créneaux horaires et l'occupation des salles par activité.</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="flex bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
            <button className="p-2 hover:bg-slate-50 rounded-lg text-slate-400"><ChevronLeft size={18} /></button>
            <div className="px-4 flex items-center font-bold text-sm text-slate-700">10 Juin - 16 Juin</div>
            <button className="p-2 hover:bg-slate-50 rounded-lg text-slate-400"><ChevronRight size={18} /></button>
          </div>
          <button 
            onClick={onModify}
            className="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 group"
          >
            <Settings2 size={18} className="group-hover:rotate-180 transition-transform duration-500" />
            Modifier le planning
          </button>
        </div>
      </div>

      {/* Schedule Table */}
      <div className="bg-white rounded-[32px] border border-slate-100 shadow-xl overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full border-collapse">
            <thead>
              <tr className="bg-slate-50/50 border-b border-slate-100">
                <th className="p-6 w-40"></th>
                {DAYS.map(day => (
                  <th key={day} className="p-6 text-center">
                    <span className="text-xs font-black uppercase text-slate-400 tracking-widest block mb-1">{day}</span>
                    <span className="text-lg font-black text-slate-900">1{DAYS.indexOf(day)}</span>
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {TIME_BLOCKS.map(timeBlock => (
                <tr key={timeBlock.id} className="border-b border-slate-50 last:border-0">
                  <td className="p-8 bg-slate-50/30">
                    <div className="flex flex-col">
                      <span className="text-sm font-black text-slate-900">{timeBlock.label}</span>
                      <span className="text-[10px] font-bold text-slate-400 uppercase flex items-center gap-1 mt-1">
                        <Clock size={10} /> {timeBlock.time}
                      </span>
                    </div>
                  </td>
                  {DAYS.map(day => {
                    const slot = MOCK_SCHEDULE_DATA.find(s => s.day === day && s.block === timeBlock.id);
                    return (
                      <td key={`${day}-${timeBlock.id}`} className="p-2 min-w-[160px]">
                        {slot ? (
                          <div className={`${slot.color} p-4 rounded-2xl text-white shadow-lg shadow-slate-200 transition-all hover:scale-[1.02] cursor-pointer group relative overflow-hidden`}>
                            {/* Icon Watermark */}
                            <slot.icon size={48} className="absolute -right-2 -bottom-2 opacity-10 group-hover:scale-125 transition-transform" />
                            
                            <div className="relative z-10 space-y-3">
                              <div className="flex items-center justify-between">
                                <slot.icon size={16} />
                                <span className={`text-[8px] font-black uppercase px-2 py-0.5 rounded-full ${slot.capacity === 'Complet' ? 'bg-rose-600' : 'bg-black/20'}`}>
                                  {slot.capacity}
                                </span>
                              </div>
                              <h4 className="text-xs font-black leading-tight">{slot.activity}</h4>
                            </div>
                          </div>
                        ) : (
                          <div className="h-24 w-full border-2 border-dashed border-slate-50 rounded-2xl flex items-center justify-center group hover:border-indigo-100 hover:bg-indigo-50/30 transition-all cursor-pointer">
                            <Plus size={16} className="text-slate-200 group-hover:text-indigo-400" />
                          </div>
                        )}
                      </td>
                    );
                  })}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Legend & Info */}
      <div className="flex flex-wrap items-center justify-between gap-6 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
        <div className="flex flex-wrap items-center gap-6">
          <div className="flex items-center gap-2">
            <div className="w-3 h-3 rounded-full bg-indigo-600"></div>
            <span className="text-xs font-bold text-slate-500">Fitness</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-3 h-3 rounded-full bg-rose-500"></div>
            <span className="text-xs font-bold text-slate-500">Boxe</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-3 h-3 rounded-full bg-emerald-500"></div>
            <span className="text-xs font-bold text-slate-500">Yoga</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-3 h-3 rounded-full bg-amber-500"></div>
            <span className="text-xs font-bold text-slate-500">CrossFit</span>
          </div>
        </div>
        <div className="flex items-center gap-2 text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
          <Info size={12} />
          Mise à jour en temps réel selon les réservations
        </div>
      </div>
    </div>
  );
};

export default ScheduleView;
