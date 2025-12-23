
import React from 'react';
import { 
  UserCheck, 
  Phone, 
  Mail, 
  Plus, 
  MoreVertical, 
  BadgeCheck,
  Clock,
  UserX,
  UserMinus,
  Edit3
} from 'lucide-react';
import { MOCK_STAFF } from '../constants';
import { StaffMember } from '../types';

interface StaffViewProps {
  onAddStaff: () => void;
  onEditStaff: (staff: StaffMember) => void;
  onDeleteStaff: (staff: StaffMember) => void;
}

const StaffView: React.FC<StaffViewProps> = ({ onAddStaff, onEditStaff, onDeleteStaff }) => {
  const getStatusBadge = (status: StaffMember['status']) => {
    switch (status) {
      case 'present': return <span className="px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1.5"><BadgeCheck size={12}/> Présent</span>;
      case 'absent': return <span className="px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-1.5"><UserX size={12}/> Absent</span>;
      case 'en_pause': return <span className="px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-amber-50 text-amber-600 border border-amber-100 flex items-center gap-1.5"><Clock size={12}/> En Pause</span>;
    }
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <UserCheck className="text-indigo-600" size={32} />
            Gestion de l'Équipe
          </h1>
          <p className="text-slate-500 font-medium mt-1">Gérez vos coachs, réceptionnistes et personnel technique.</p>
        </div>
        <button 
          onClick={onAddStaff}
          className="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 group active:scale-95"
        >
          <Plus size={20} className="group-hover:rotate-90 transition-transform duration-300" />
          Ajouter un Staff
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {MOCK_STAFF.map((staff) => (
          <div key={staff.id} className="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col group overflow-hidden relative">
            <div className="absolute top-0 right-0 p-4">
               <button className="p-2 text-slate-300 hover:text-slate-900 transition-colors">
                 <MoreVertical size={20} />
               </button>
            </div>
            
            <div className="flex items-center gap-4 mb-6">
              <div className="h-16 w-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-2xl group-hover:scale-105 transition-transform">
                {staff.name.split(' ').map(n => n[0]).join('')}
              </div>
              <div className="min-w-0">
                <h3 className="text-lg font-black text-slate-900 truncate">{staff.name}</h3>
                <p className="text-xs font-bold text-slate-400 uppercase tracking-tighter">{staff.role}</p>
              </div>
            </div>

            <div className="space-y-3 mb-6 flex-1">
              <div className="flex items-center justify-between p-3 bg-slate-50 rounded-2xl">
                <span className="text-[10px] font-black uppercase text-slate-400">Statut Actuel</span>
                {getStatusBadge(staff.status)}
              </div>
              
              <div className="flex items-center gap-3 text-slate-500">
                <Phone size={14} />
                <span className="text-sm font-bold">{staff.phone}</span>
              </div>
              <div className="flex items-center gap-3 text-slate-500">
                <Mail size={14} />
                <span className="text-sm font-bold truncate">{staff.email}</span>
              </div>
            </div>

            <div className="pt-4 border-t border-slate-50 flex items-center justify-between">
              <div>
                <p className="text-[10px] font-black uppercase text-slate-400">Salaire</p>
                <p className="text-sm font-black text-slate-900">{staff.salary.toLocaleString('fr-FR')} DH</p>
              </div>
              <div className="flex gap-2">
                <button 
                  onClick={() => onEditStaff(staff)}
                  className="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors"
                >
                  <Edit3 size={16} />
                </button>
                <button 
                  onClick={() => onDeleteStaff(staff)}
                  className="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors"
                >
                  <UserMinus size={16} />
                </button>
              </div>
            </div>
          </div>
        ))}
        
        {/* Add Staff Placeholder */}
        <div 
          onClick={onAddStaff}
          className="border-2 border-dashed border-slate-100 rounded-[32px] p-6 flex flex-col items-center justify-center text-center space-y-4 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer group py-12"
        >
          <div className="h-16 w-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center group-hover:bg-white group-hover:text-indigo-500 transition-all">
            <Plus size={32} />
          </div>
          <div>
            <h4 className="text-sm font-black text-slate-900">Nouveau membre</h4>
            <p className="text-xs text-slate-400 font-medium">Recruter un nouveau coach</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default StaffView;
