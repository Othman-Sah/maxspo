
import React, { useState, useEffect, useCallback } from 'react';
import { 
  BookText, 
  Filter, 
  Printer, 
  Download, 
  TrendingUp, 
  TrendingDown,
  DollarSign,
  CheckCircle2,
  Clock,
  XCircle,
  ArrowUpRight,
  PlusCircle,
  Search
} from 'lucide-react';
import { Transaction, ExpenseCategory } from '../types';

interface JournalViewProps {
  onPrint: () => void;
  onExport: () => void;
  openModal: (config: any) => void;
}

interface JournalData {
  transactions: Transaction[];
  summary: {
    totalRevenue: number;
    totalExpenses: number;
    netIncome: number;
  };
  meta: {
    expenseCategories: ExpenseCategory[];
    filters: Record<string, string>;
  };
}

const JournalView: React.FC<JournalViewProps> = ({ onPrint, onExport, openModal }) => {
  const [data, setData] = useState<JournalData | null>(null);
  const [loading, setLoading] = useState(true);
  const [filters, setFilters] = useState({
    type: 'all',
    status: 'all',
    month: new Date().toISOString().substring(0, 7),
    search: ''
  });

  const fetchJournalData = useCallback(async () => {
    setLoading(true);
    const params = new URLSearchParams(filters);
    try {
      const response = await fetch(`http://localhost/lA/Backend/api/journal.php?${params.toString()}`);
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const result = await response.json();
      setData(result);
    } catch (error) {
      console.error('Failed to fetch journal data:', error);
      // Here you might want to set an error state and display it to the user
    } finally {
      setLoading(false);
    }
  }, [filters]);

  useEffect(() => {
    fetchJournalData();
  }, [fetchJournalData]);

  const handleFilterChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFilters(prev => ({ ...prev, [name]: value }));
  };

  const handleAddExpense = () => {
    openModal({
      title: "Déclarer une dépense",
      type: 'info',
      confirmLabel: "Enregistrer la dépense",
      onConfirm: async (formData: any) => {
        try {
          const response = await fetch('http://localhost/lA/Backend/api/journal.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
          });
          if(response.ok) {
            fetchJournalData(); // Refresh data
            return true; // Indicate success to close modal
          }
        } catch (error) {
          console.error("Error adding expense:", error);
        }
        return false; // Indicate failure
      },
      content: (
        <div className="space-y-4">
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Catégorie</label>
            <select name="category" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm">
              {data?.meta.expenseCategories.map(c => <option key={c}>{c}</option>)}
              <option>Loyer / Charges</option>
              <option>Électricité / Eau</option>
              <option>Maintenance Matériel</option>
            </select>
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Description</label>
            <input name="description" type="text" placeholder="Ex: Entretien Climatisation" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Montant (DH)</label>
            <input name="amount" type="number" placeholder="0.00" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-black text-lg text-rose-500" />
          </div>
        </div>
      )
    });
  };

  const getStatusBadge = (status: Transaction['status']) => {
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

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <BookText className="text-indigo-600" size={32} />
            Journal Financier
          </h1>
          <p className="text-slate-500 font-medium mt-1">Vue unifiée des revenus et des dépenses.</p>
        </div>
        <div className="flex items-center gap-3">
          <button onClick={handleAddExpense} className="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100">
            <PlusCircle size={18} />
            Ajouter Dépense
          </button>
          <button onClick={() => onExport("Journal_Financier")} className="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm">
            <Download size={18} />
            Exporter
          </button>
        </div>
      </div>

      {/* Stats Summary */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Revenus (Brut)</p>
          <div className="flex items-center justify-between">
            <h3 className="text-3xl font-black text-emerald-600">{data?.summary.totalRevenue.toLocaleString('fr-FR') || 0} DH</h3>
            <div className="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><TrendingUp size={20} /></div>
          </div>
        </div>
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Dépenses</p>
          <div className="flex items-center justify-between">
            <h3 className="text-3xl font-black text-rose-600">{data?.summary.totalExpenses.toLocaleString('fr-FR') || 0} DH</h3>
            <div className="h-10 w-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center"><TrendingDown size={20} /></div>
          </div>
        </div>
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Résultat Net</p>
          <div className="flex items-center justify-between">
            <h3 className="text-3xl font-black text-slate-900">{data?.summary.netIncome.toLocaleString('fr-FR') || 0} DH</h3>
            <div className="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center"><DollarSign size={20} /></div>
          </div>
        </div>
      </div>

      {/* Filters and Tabs */}
      <div className="flex items-center justify-between bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
        <div className="flex items-center gap-2">
            {['all', 'payment', 'expense'].map(type => (
                <button 
                    key={type}
                    onClick={() => setFilters(f => ({...f, type}))}
                    className={`px-5 py-2.5 rounded-xl text-sm font-bold transition-all ${filters.type === type ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50'}`}
                >
                    {type === 'all' && 'Tous'}
                    {type === 'payment' && 'Revenus'}
                    {type === 'expense' && 'Dépenses'}
                </button>
            ))}
        </div>
        <div className="flex items-center gap-4 px-4">
            <div className="relative">
                <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                <input type="text" name="search" placeholder="Rechercher..." value={filters.search} onChange={handleFilterChange} className="bg-slate-50 border-transparent rounded-lg pl-9 pr-3 py-2 text-sm w-48 focus:bg-white focus:border-indigo-500"/>
            </div>
            <input type="month" name="month" value={filters.month} onChange={handleFilterChange} className="bg-slate-50 border-transparent rounded-lg px-3 py-2 text-sm font-bold"/>
            <button onClick={() => fetchJournalData()} className="px-4 py-2 text-sm font-bold bg-slate-800 text-white rounded-lg hover:bg-slate-700">Filtrer</button>
        </div>
      </div>


      {/* Transactions Table */}
      <div className="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          {loading ? (
            <div className="py-24 text-center text-slate-500">Chargement...</div>
          ) : !data || data.transactions.length === 0 ? (
            <div className="py-24 text-center">
              <h3 className="text-xl font-black text-slate-900">Aucune transaction trouvée</h3>
              <p className="text-slate-400 font-medium mt-2">Essayez de modifier vos filtres.</p>
            </div>
          ) : (
            <table className="w-full text-left border-collapse">
              <thead className="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                  <th className="px-8 py-6">Type</th>
                  <th className="px-6 py-6">Date</th>
                  <th className="px-6 py-6">Description</th>
                  <th className="px-6 py-6">Catégorie</th>
                  <th className="px-6 py-6 text-right">Montant</th>
                  <th className="px-6 py-6">Statut</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50">
                {data.transactions.map((t) => (
                  <tr key={`${t.type}-${t.id}`} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-8 py-5">
                      {t.type === 'payment' ? 
                        <span className="font-bold text-sm text-emerald-600">Revenu</span> : 
                        <span className="font-bold text-sm text-rose-600">Dépense</span>
                      }
                    </td>
                    <td className="px-6 py-5 text-sm text-slate-700 font-medium">{new Date(t.date).toLocaleDateString('fr-FR')}</td>
                    <td className="px-6 py-5 text-sm text-slate-600">{t.description}</td>
                    <td className="px-6 py-5">
                      <span className="px-2.5 py-1 text-[10px] font-black rounded-lg bg-slate-100 text-slate-500 border border-slate-200">{t.category}</span>
                    </td>
                    <td className={`px-6 py-5 text-right font-black text-lg ${t.type === 'payment' ? 'text-emerald-600' : 'text-rose-600'}`}>
                      {t.type === 'payment' ? '+' : '-'}{t.amount.toLocaleString('fr-FR')} DH
                    </td>
                    <td className="px-6 py-5">{getStatusBadge(t.status)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </div>
    </div>
  );
};

export default JournalView;
