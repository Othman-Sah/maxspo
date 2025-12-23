
import React, { useState } from 'react';
import { 
  Users, 
  Search, 
  Filter, 
  Printer, 
  Download, 
  Plus, 
  Mail, 
  Phone, 
  Edit3, 
  Trash2, 
  Star,
  RefreshCw,
  FileText
} from 'lucide-react';
import { Member } from '../types';
import { MOCK_MEMBERS } from '../constants';

interface MembersViewProps {
  onAddMember: () => void;
  onRenew: (member: Member) => void;
  onEdit: (member: Member) => void;
  onDelete: (member: Member) => void;
  onPrint: () => void;
  onExport: () => void;
  onPrintReceipt: (member: Member) => void;
}

const MembersView: React.FC<MembersViewProps> = ({ 
  onAddMember, 
  onRenew, 
  onEdit, 
  onDelete, 
  onPrint, 
  onExport,
  onPrintReceipt
}) => {
  const [searchQuery, setSearchQuery] = useState('');
  const [sportFilter, setSportFilter] = useState('all');
  const [statusFilter, setStatusFilter] = useState('all');

  const filteredMembers = MOCK_MEMBERS.filter(member => {
    const matchesSearch = `${member.firstName} ${member.lastName}`.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         member.email.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         member.phone.includes(searchQuery);
    const matchesSport = sportFilter === 'all' || member.sport === sportFilter;
    const matchesStatus = statusFilter === 'all' || member.status === statusFilter;
    
    return matchesSearch && matchesSport && matchesStatus;
  });

  const getStatusBadge = (status: Member['status']) => {
    switch (status) {
      case 'actif':
        return <span className="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">Actif</span>;
      case 'expirant':
        return <span className="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-600 border border-amber-100 animate-pulse">Expire bientôt</span>;
      case 'expire':
        return <span className="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-600 border border-rose-100">Expiré</span>;
      default:
        return null;
    }
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <Users className="text-indigo-600" size={32} />
            Gestion des Membres
          </h1>
          <p className="text-slate-500 font-medium mt-1">Gérez vos abonnés et suivez les renouvellements (Sauna inclus 🧖)</p>
        </div>
        <div className="flex items-center gap-3">
          <button 
            onClick={onPrint}
            className="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm"
          >
            <Printer size={18} />
            Imprimer
          </button>
          <button 
            onClick={onExport}
            className="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all text-sm shadow-lg shadow-emerald-100"
          >
            <Download size={18} />
            Exporter
          </button>
          <button 
            onClick={onAddMember}
            className="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100"
          >
            <Plus size={18} />
            Nouveau Membre
          </button>
        </div>
      </div>

      {/* Filters Card */}
      <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
        <div className="flex items-center justify-between">
          <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
            <Filter size={16} className="text-indigo-500" />
            Filtres avancés
          </h3>
          <button 
            onClick={() => {setSearchQuery(''); setSportFilter('all'); setStatusFilter('all');}}
            className="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline"
          >
            Réinitialiser
          </button>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
            <input 
              type="text"
              placeholder="Nom, email, téléphone..."
              className="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>

          <select 
            className="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600"
            value={sportFilter}
            onChange={(e) => setSportFilter(e.target.value)}
          >
            <option value="all">Tous les sports</option>
            <option value="Fitness / Cardio">Fitness / Cardio</option>
            <option value="Boxe Anglaise">Boxe Anglaise</option>
            <option value="Yoga & Pilates">Yoga & Pilates</option>
            <option value="CrossFit">CrossFit</option>
          </select>

          <select 
            className="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600"
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value)}
          >
            <option value="all">Tous les statuts</option>
            <option value="actif">Actif</option>
            <option value="expirant">Expire bientôt</option>
            <option value="expire">Expiré</option>
          </select>

          <div className="bg-indigo-50 px-4 py-2.5 rounded-xl border border-indigo-100 flex items-center justify-between">
            <span className="text-xs font-bold text-indigo-700">{filteredMembers.length} membres trouvés</span>
            <div className="flex -space-x-2">
               {[1,2,3].map(i => (
                 <div key={i} className="w-6 h-6 rounded-full border-2 border-white bg-indigo-200 flex items-center justify-center text-[8px] font-bold text-indigo-700">
                   {String.fromCharCode(64 + i)}
                 </div>
               ))}
            </div>
          </div>
        </div>
      </div>

      {/* Members List */}
      <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead className="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
              <tr>
                <th className="px-8 py-5">Membre</th>
                <th className="px-6 py-5">Sport & Accès</th>
                <th className="px-6 py-5">Période</th>
                <th className="px-6 py-5">Statut</th>
                <th className="px-6 py-5">Contact</th>
                <th className="px-8 py-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {filteredMembers.map((member) => (
                <tr key={member.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-5 whitespace-nowrap">
                    <div className="flex items-center">
                      <div className="relative">
                        <div className="h-12 w-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg shadow-sm">
                          {member.firstName[0]}{member.lastName[0]}
                        </div>
                        {member.isLoyal && (
                          <div className="absolute -top-1 -right-1 bg-amber-400 text-white rounded-full p-0.5 shadow-sm border border-white">
                            <Star size={10} className="fill-current" />
                          </div>
                        )}
                      </div>
                      <div className="ml-4">
                        <div className="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                          {member.firstName} {member.lastName}
                        </div>
                        <div className="text-xs font-medium text-slate-500">{member.age} ans • Membre depuis {new Date(member.joinDate).getFullYear()}</div>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <div className="space-y-1">
                      <span className="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100">
                        {member.sport}
                      </span>
                      <div className="text-[10px] text-slate-400 font-bold ml-1 italic">Inclut Sauna 🧖</div>
                    </div>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <div className="flex flex-col">
                      <span className="text-sm font-bold text-slate-700">
                        {new Date(member.expiryDate).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' })}
                      </span>
                      <span className="text-[10px] font-medium text-slate-400">Expire le</span>
                    </div>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    {getStatusBadge(member.status)}
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <div className="flex items-center gap-2">
                      <button onClick={() => alert(`Envoyer mail à ${member.email}`)} className="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title={member.email}>
                        <Mail size={18} />
                      </button>
                      <button onClick={() => alert(`Appeler ${member.phone}`)} className="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title={member.phone}>
                        <Phone size={18} />
                      </button>
                    </div>
                  </td>
                  <td className="px-8 py-5 whitespace-nowrap text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button 
                        onClick={() => onPrintReceipt(member)}
                        className="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                        title="Imprimer Reçu"
                      >
                        <FileText size={18} />
                      </button>
                      <button 
                        onClick={() => onRenew(member)}
                        className="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all active:scale-95"
                      >
                        <RefreshCw size={14} />
                        Renouveler
                      </button>
                      <button 
                        onClick={() => onEdit(member)}
                        className="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                      >
                        <Edit3 size={16} />
                      </button>
                      <button 
                        onClick={() => onDelete(member)}
                        className="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                      >
                        <Trash2 size={16} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          
          {filteredMembers.length === 0 && (
            <div className="py-20 text-center">
              <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <Search size={32} />
              </div>
              <p className="text-slate-500 font-bold">Aucun membre ne correspond à votre recherche.</p>
              <button 
                onClick={() => {setSearchQuery(''); setSportFilter('all'); setStatusFilter('all');}}
                className="mt-2 text-indigo-600 font-bold text-sm hover:underline"
              >
                Effacer les filtres
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default MembersView;
