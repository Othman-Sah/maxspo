
import React, { useState } from 'react';
import { 
  CreditCard, 
  Search, 
  Filter, 
  Printer, 
  Download, 
  TrendingUp, 
  Banknote, 
  Landmark, 
  PenTool,
  CheckCircle2,
  Clock,
  XCircle,
  Calendar,
  ArrowUpRight
} from 'lucide-react';
import { Payment } from '../types';
import { MOCK_PAYMENTS, MOCK_PAYMENT_METHOD_STATS } from '../constants';

interface PaymentsViewProps {
  onPrint: () => void;
  onExport: () => void;
}

const PaymentsView: React.FC<PaymentsViewProps> = ({ onPrint, onExport }) => {
  const [methodFilter, setMethodFilter] = useState('all');
  const [statusFilter, setStatusFilter] = useState('all');
  const [monthFilter, setMonthFilter] = useState(new Date().toISOString().substring(0, 7));

  const filteredPayments = MOCK_PAYMENTS.filter(p => {
    const matchesMethod = methodFilter === 'all' || p.method === methodFilter;
    const matchesStatus = statusFilter === 'all' || p.status === statusFilter;
    const matchesMonth = p.date.startsWith(monthFilter);
    return matchesMethod && matchesStatus && matchesMonth;
  });

  const totalAmount = filteredPayments.reduce((acc, curr) => acc + curr.amount, 0);

  const getStatusBadge = (status: Payment['status']) => {
    switch (status) {
      case 'valide':
        return <span className="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100"><CheckCircle2 size={12}/> Validé</span>;
      case 'en_attente':
        return <span className="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-amber-50 text-amber-600 border border-amber-100"><Clock size={12}/> En attente</span>;
      case 'annule':
        return <span className="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-rose-50 text-rose-600 border border-rose-100"><XCircle size={12}/> Annulé</span>;
      default:
        return null;
    }
  };

  const getMethodIcon = (method: Payment['method']) => {
    switch (method) {
      case 'especes': return <Banknote size={16} className="text-emerald-500" />;
      case 'carte': return <CreditCard size={16} className="text-blue-500" />;
      case 'virement': return <Landmark size={16} className="text-indigo-500" />;
      case 'cheque': return <PenTool size={16} className="text-amber-500" />;
    }
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <CreditCard className="text-emerald-600" size={32} />
            Gestion des Revenus
          </h1>
          <p className="text-slate-500 font-medium mt-1">Suivi détaillé des transactions et performances financières</p>
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
            className="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all text-sm shadow-lg shadow-emerald-100"
          >
            <Download size={18} />
            Exporter CSV
          </button>
        </div>
      </div>

      {/* Stats Summary */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Paiements ce mois</p>
          <div className="flex items-center justify-between">
            <h3 className="text-3xl font-black text-slate-900">{filteredPayments.length}</h3>
            <div className="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
              <Calendar size={20} />
            </div>
          </div>
        </div>
        
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <TrendingUp size={80} />
          </div>
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenus</p>
          <div className="flex items-center justify-between">
            <h3 className="text-3xl font-black text-emerald-600">{totalAmount.toLocaleString('fr-FR')} DH</h3>
            <div className="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <TrendingUp size={20} />
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Panier Moyen</p>
          <div className="flex items-center justify-between">
            <h3 className="text-3xl font-black text-indigo-600">
              {filteredPayments.length > 0 ? Math.round(totalAmount / filteredPayments.length) : 0} DH
            </h3>
            <div className="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
              <ArrowUpRight size={20} />
            </div>
          </div>
        </div>
      </div>

      {/* Analytics Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
         {/* Payment Methods */}
         <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
              <Banknote size={20} className="text-indigo-500" />
              Méthodes de Paiement
            </h3>
            <div className="space-y-5">
              {MOCK_PAYMENT_METHOD_STATS.map((stat) => (
                <div key={stat.method} className="space-y-2">
                  <div className="flex items-center justify-between">
                    <span className="text-sm font-bold text-slate-700">{stat.method}</span>
                    <span className="text-xs font-black text-slate-400">{stat.total.toLocaleString('fr-FR')} DH</span>
                  </div>
                  <div className="h-2.5 w-full bg-slate-50 rounded-full overflow-hidden">
                    <div 
                      className={`h-full ${stat.color} transition-all duration-1000`} 
                      style={{ width: `${stat.percentage}%` }}
                    ></div>
                  </div>
                  <div className="flex justify-between text-[10px] font-bold text-slate-400 uppercase">
                    <span>{stat.count} Transactions</span>
                    <span>{stat.percentage}% du volume</span>
                  </div>
                </div>
              ))}
            </div>
         </div>

         {/* Filtering Section */}
         <div className="bg-slate-900 p-8 rounded-3xl shadow-xl shadow-slate-200 text-white space-y-6">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-black flex items-center gap-2">
                <Filter size={20} className="text-indigo-400" />
                Filtres de Recherche
              </h3>
              <button 
                onClick={() => {setMethodFilter('all'); setStatusFilter('all');}}
                className="text-xs font-bold text-slate-400 hover:text-white transition-colors"
              >
                Réinitialiser
              </button>
            </div>
            
            <div className="space-y-4">
              <div>
                <label className="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Période Mensuelle</label>
                <input 
                  type="month" 
                  className="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-white"
                  value={monthFilter}
                  onChange={(e) => setMonthFilter(e.target.value)}
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Méthode</label>
                  <select 
                    className="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-white"
                    value={methodFilter}
                    onChange={(e) => setMethodFilter(e.target.value)}
                  >
                    <option value="all">Toutes</option>
                    <option value="especes">Espèces</option>
                    <option value="carte">Carte</option>
                    <option value="virement">Virement</option>
                    <option value="cheque">Chèque</option>
                  </select>
                </div>
                <div>
                  <label className="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Statut</label>
                  <select 
                    className="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-white"
                    value={statusFilter}
                    onChange={(e) => setStatusFilter(e.target.value)}
                  >
                    <option value="all">Tous</option>
                    <option value="valide">Validés</option>
                    <option value="en_attente">En attente</option>
                    <option value="annule">Annulés</option>
                  </select>
                </div>
              </div>

              <div className="pt-4 border-t border-slate-800">
                <div className="flex items-center justify-between p-4 bg-indigo-600 rounded-2xl">
                  <div>
                    <p className="text-[10px] font-black uppercase text-indigo-200">Total Filtré</p>
                    <h4 className="text-2xl font-black">{totalAmount.toLocaleString('fr-FR')} DH</h4>
                  </div>
                  <div className="text-right">
                    <p className="text-[10px] font-black uppercase text-indigo-200">{filteredPayments.length} Transactions</p>
                    <p className="text-xs font-bold text-white/80">Affichées</p>
                  </div>
                </div>
              </div>
            </div>
         </div>
      </div>

      {/* Payments Table */}
      <div className="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead className="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
              <tr>
                <th className="px-8 py-6">Date</th>
                <th className="px-6 py-6">Membre</th>
                <th className="px-6 py-6">Activité</th>
                <th className="px-6 py-6">Méthode</th>
                <th className="px-6 py-6">Montant</th>
                <th className="px-6 py-6">Statut</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {filteredPayments.map((payment) => (
                <tr key={payment.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-5 whitespace-nowrap">
                    <div className="flex flex-col">
                      <span className="text-sm font-black text-slate-900">
                        {new Date(payment.date).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' })}
                      </span>
                      <span className="text-[10px] font-bold text-slate-400">Transaction #{payment.id}</span>
                    </div>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <div className="flex items-center gap-3">
                      <div className="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-sm">
                        {payment.memberName.charAt(0)}
                      </div>
                      <span className="text-sm font-bold text-slate-700">{payment.memberName}</span>
                    </div>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <span className="px-2.5 py-1 text-[10px] font-black rounded-lg bg-slate-100 text-slate-500 border border-slate-200">
                      {payment.sport}
                    </span>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <div className="flex items-center gap-2 text-sm font-bold text-slate-600">
                      {getMethodIcon(payment.method)}
                      <span className="capitalize">{payment.method}</span>
                    </div>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    <span className="text-lg font-black text-emerald-600">{payment.amount} DH</span>
                  </td>
                  <td className="px-6 py-5 whitespace-nowrap">
                    {getStatusBadge(payment.status)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          
          {filteredPayments.length === 0 && (
            <div className="py-24 text-center">
              <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                <CreditCard size={40} />
              </div>
              <h3 className="text-xl font-black text-slate-900">Aucune transaction trouvée</h3>
              <p className="text-slate-400 font-medium mt-2">Essayez de modifier vos filtres pour voir d'autres résultats.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default PaymentsView;
